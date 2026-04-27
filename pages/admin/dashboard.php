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


$conn->close();
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
        <h1>- WELCOME TO<br><br><span style="font-size: 50px;">AGRIMARKET ONLINE SHOP</span><br><br></h1>
        <p>"AgriMarket: Bridging Farmers and Communities with Fresh, Local Goodness!"</p>
    </div>

    <div class="quote">
        <h1>Create and modify products</h1>
        <p>Create new and freshly made local products for the market</p>
    </div>

    <div class="categories">
        <a href="dashboard.php">CREATE NEW PRODUCT</a>
        <a href="view_products.php">VIEW PRODUCTS</a>
        <a href="view_feedback.php">VIEW FEEDBACKS</a>
        <a href="view_orders.php">VIEW ORDERS</a>
    </div>

    <h2>Add a Product</h2>
    <form action="dashboard.php" method="post" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label><br>
        <input type="text" id="product_name" name="product_name" required><br><br>

        <label for="category_id">Category ID:</label><br>
        <input type="number" id="category_id" name="category_id"><br><br>

        <label for="price">Price:</label><br>
        <input type="number" step="0.01" id="price" name="price" required><br><br>

        <label for="stock">Stock:</label><br>
        <input type="number" id="stock" name="stock"><br><br>

        <label for="product_image">Product Image:</label><br>
        <input type="file" id="product_image" name="product_image" accept="image/*" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description"></textarea><br><br>

        <button type="submit">Add Product</button>
    </form>

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
                <a href="dashboard.php">Create new products</a>
                <a href="view_products.php">View products</a>
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
