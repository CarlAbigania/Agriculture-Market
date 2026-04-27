<?php
// Start the session
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../auth/login.php");
    exit;
}

// Database connection
require_once '../../includes/db.php';

// Default category if not set
$categoryId = isset($_GET['category_id']) ? $_GET['category_id'] : '';

// Prepare the query to fetch products based on category
$sql = "SELECT p.product_id, p.product_name, p.category_id, p.price, p.stock, p.image_url, p.description, c.category_name
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.category_id";

// If a category is selected, filter products by that category
if ($categoryId != '') {
    $sql .= " WHERE p.category_id = " . intval($categoryId);
}

$sql .= " ORDER BY c.category_name";  // Sorting by category name

// Execute the query
$result = $conn->query($sql);

// Fetch all categories for the filter dropdown
$categoryQuery = "SELECT category_id, category_name FROM categories";
$categoriesResult = $conn->query($categoryQuery);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Products</title>
    <link rel="stylesheet" href="../../assets/css/view_products.css">
</head>

<body>
    <header>
        <img src="../../assets/images/agrimarket_logo.png" alt="AgroMarket Logo">
        <p>Welcome, Admin!</p>
        <!-- Log Out Button -->
        <button class="logout-btn" onclick="confirmLogout()">Log Out</button>
    </header>

    <div class="hero">
        <h1>- WELCOME TO<br><br><span style="font-size: 50px;">AGRIMARKET ONLINE SHOP</span><br><br></h1>
        <p>"AgriMarket: Bridging Farmers and Communities with Fresh, Local Goodness!"</p>
    </div>

    <div class="quote">
        <h1>Product List</h1>
        <p>Select a category to view the products.</p>
    </div>

    <div class="categories">
        <a href="dashboard.php">CREATE NEW PRODUCT</a>
        <a href="view_products.php">VIEW PRODUCTS</a>
        <a href="view_feedback.php">VIEW FEEDBACKS</a>
        <a href="view_orders.php">VIEW ORDERS</a>
    </div>

    <h2>Product Table</h2>

    <!-- Category Filter Dropdown -->
    <form action="view_products.php" method="GET">
        <label for="category_id">Select Category:</label>
        <select name="category_id" id="category_id" onchange="this.form.submit()">
            <option value="">All Categories</option>
            <?php while ($category = $categoriesResult->fetch_assoc()) { ?>
                <option value="<?php echo $category['category_id']; ?>" <?php echo $category['category_id'] == $categoryId ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['category_name']); ?>
                </option>
            <?php } ?>
        </select>
    </form>

    <!-- Product Table -->
    <div class="table-container">
        <div class="table-header">
            <div class="header-item">Product Name</div>
            <div class="header-item">Category</div>
            <div class="header-item">Price</div>
            <div class="header-item">Stock</div>
            <div class="header-item">Image</div>
            <div class="header-item">Description</div>
            <div class="header-item">Actions</div>
        </div>

        <div class="table-body">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($product = $result->fetch_assoc()): ?>
                    <div class="table-row">
                        <div class="table-cell"><?php echo htmlspecialchars($product['product_name']); ?></div>
                        <div class="table-cell"><?php echo htmlspecialchars($product['category_name']); ?></div>
                        <div class="table-cell">₱<?php echo htmlspecialchars($product['price']); ?></div>
                        <div class="table-cell"><?php echo htmlspecialchars($product['stock']); ?></div>
                        <div class="table-cell">
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" width="50">
                        </div>
                        <div class="table-cell"><?php echo htmlspecialchars($product['description']); ?></div>
                        <div class="table-cell">
                            <a href="edit_product.php?product_id=<?php echo $product['product_id']; ?>" class="btn-action">Edit</a> |
                            <a href="delete_product.php" class="btn-action">Delete</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="table-row">
                    <div class="table-cell" colspan="7">No products found.</div>
                </div>
            <?php endif; ?>
        </div>
    </div>


    <!-- footer -->
    <footer class="footer-container">
        <div class="footer-content">
            <div class="footer-logo">
                <div class="logo-background"></div>
                <p>Lorem ipsum dolor sit amet consectetur. Tortor viverra elementum mauris suscipit porttitor interdum
                    mauris egestas. Et consectetur nunc proin vitae congue odio proin purus. Nisi tristique tincidunt
                    diam et. Tellus leo eu felis odio fusce massa nisl sit integer. Vel gravida lacus nec.</p>
            </div>

            <div class="footer-links">
                <h4>Quick Links</h4>
                <a href="../home/home.php">Home</a>
                <a href="../home/farm.php">Farm</a>
                <a href="dashboard.php">Market</a>
            </div>

            <div class="footer-contacts">
                <h4>Contacts</h4>
                <p><strong>Address:</strong> Plot 5, Idu Industrial Estate, Abuja</p>
                <p><strong>Phone Numbers:</strong> 2348012345678, 23470123456789</p>
                <p><strong>Email:</strong> hello@agromarket.com</p>
            </div>
        </div>
    </footer>

    <script>
        // JavaScript function to confirm logout
        function confirmLogout() {
            const userConfirmation = confirm("Are you sure you want to log out?");
            if (userConfirmation) {
                // If confirmed, log out and redirect to login page
                window.location.href = '../../includes/logout.php'; // Change this URL to your logout script
            }
        }
    </script>

</body>

</html>
