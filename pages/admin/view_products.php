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
    <link rel="stylesheet" href="../../assets/css/admin_dashboard.css">
</head>

<body>
    <header>
        <img src="../../assets/images/agrimarket_logo.png" alt="AgroMarket Logo">
        <p>Welcome, Admin!</p>
        <!-- Log Out Button -->
        <button class="logout-btn" onclick="confirmLogout()">Log Out</button>
    </header>

    <div class="hero">
        <h1>WELCOME TO<br><span>AGRIMARKET ONLINE SHOP</span></h1>
        <p>"AgriMarket: Bridging Farmers and Communities with Fresh, Local Goodness!"</p>
    </div>

    <div class="quote">
        <h1>Product List</h1>
        <p>Select a category to view the products.</p>
    </div>

    <div class="categories">
        <a href="dashboard.php">CREATE NEW PRODUCT</a>
        <a href="view_products.php" class="active">VIEW PRODUCTS</a>
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
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Image</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($product = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                        <td>₱<?php echo htmlspecialchars($product['price']); ?></td>
                        <td><?php echo htmlspecialchars($product['stock']); ?></td>
                        <td>
                            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" class="table-img">
                        </td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td>
                            <a href="edit_product.php?product_id=<?php echo $product['product_id']; ?>" class="btn-action">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <i class="fa-solid fa-boxes-stacked"></i>
            <p>Your product inventory is currently empty.</p>
            <p class="sub-text">Add your first product to get started!</p>
        </div>
    <?php endif; ?>
    </div>



    <script>
        // JavaScript function to confirm logout
        function confirmLogout() {
            const userConfirmation = confirm("Are you sure you want to log out?");
            if (userConfirmation) {
                window.location.href = '../../includes/logout.php';
            }
        }

        // Auto-dismiss toasts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const messages = document.querySelectorAll('.success-message, .error-message');
            messages.forEach(msg => {
                setTimeout(() => {
                    msg.style.animation = 'toastFadeOut 0.5s forwards';
                    setTimeout(() => msg.remove(), 500);
                }, 5000);
            });
        });
    </script>

</body>

</html>
