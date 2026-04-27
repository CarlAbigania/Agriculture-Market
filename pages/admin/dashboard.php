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

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $productName = $_POST["product_name"];
    $categoryId = empty($_POST["category_id"]) ? null : $_POST["category_id"]; // Handle NULL
    $price = $_POST["price"];
    $stock = $_POST["stock"] ?? 0;
    $description = $_POST["description"] ?? '';
    $uploadDir = "../../assets/uploads/";
    $imagePath = "";

    // Handle image upload
    if (isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] === 0) {
        $imageFileType = strtolower(pathinfo($_FILES["product_image"]["name"], PATHINFO_EXTENSION));
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];

        if (in_array($imageFileType, $allowedTypes)) {
            $imagePath = $uploadDir . basename($_FILES["product_image"]["name"]);
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            move_uploaded_file($_FILES["product_image"]["tmp_name"], $imagePath);
        } else {
            echo "Only image files (JPG, JPEG, PNG, GIF) are allowed.";
            exit;
        }
    }

    // Prepare SQL query
    $stmt = $conn->prepare("INSERT INTO products (product_name, category_id, price, stock, image_url, description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param(
        "sidiss",
        $productName,
        $categoryId, // Automatically NULL if empty
        $price,
        $stock,
        $imagePath,
        $description
    );

    // Execute query
    if ($stmt->execute()) {
        echo '<div class="success-message">Product added successfully!</div>';
    } else {
        echo '<div class="error-message">Error adding the product: ' . $stmt->error . '</div>';
    }

    $stmt->close();
}

// Fetch categories for the dropdown
$categoriesResult = $conn->query("SELECT * FROM categories ORDER BY category_name ASC");
$categories = [];
if ($categoriesResult && $categoriesResult->num_rows > 0) {
    while ($row = $categoriesResult->fetch_assoc()) {
        $categories[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Add Product</title>
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
        <h1>Create and modify products</h1>
        <p>Create new and freshly made local products for the market</p>
    </div>

    <div class="categories">
        <a href="dashboard.php" class="active">CREATE NEW PRODUCT</a>
        <a href="view_products.php">VIEW PRODUCTS</a>
        <a href="view_feedback.php">VIEW FEEDBACKS</a>
        <a href="view_orders.php">VIEW ORDERS</a>
    </div>

    <h2>Add a Product</h2>
    <form action="dashboard.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="product_name">Product Name</label>
            <input type="text" id="product_name" name="product_name" placeholder="Enter product name" required>
        </div>

        <div class="form-group">
            <label for="category_id">Product Category</label>
            <select id="category_id" name="category_id">
                <option value="">Select a Category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['category_id']; ?>">
                        <?php echo htmlspecialchars($category['category_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="price">Price (₱)</label>
            <input type="number" step="0.01" id="price" name="price" placeholder="0.00" required>
        </div>

        <div class="form-group">
            <label for="stock">Stock Quantity</label>
            <input type="number" id="stock" name="stock" placeholder="0">
        </div>

        <div class="form-group">
            <label for="product_image">Product Image</label>
            <input type="file" id="product_image" name="product_image" accept="image/*" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" placeholder="Describe your product..."></textarea>
        </div>

        <button type="submit">Add Product to Inventory</button>
    </form>


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

<?php
$conn->close();
?>
