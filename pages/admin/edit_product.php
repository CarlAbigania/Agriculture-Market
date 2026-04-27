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

// Check if the product ID is provided
if (!isset($_GET['product_id']) || !is_numeric($_GET['product_id'])) {
    echo "Invalid product ID.";
    exit;
}

$productId = $_GET['product_id'];

// Fetch the product details
$sql = "SELECT * FROM products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $productId);
$stmt->execute();
$result = $stmt->get_result();

$product = $result->fetch_assoc();
$stmt->close();

// If no product is found
if (!$product) {
    echo "Product not found.";
    exit;
}

// Handle form submission (update product details)
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get updated values from the form
    $productName = $_POST['product_name'];
    $categoryId = $_POST['category_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = $_POST['description'];
    $imagePath = $product['image_url']; // Keep existing image unless new one is uploaded

    // Handle image upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
        $uploadDir = "../../assets/uploads/";
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

    // Update product in the database
    $updateSql = "UPDATE products 
                  SET product_name = ?, category_id = ?, price = ?, stock = ?, description = ?, image_url = ?
                  WHERE product_id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("sidissi", $productName, $categoryId, $price, $stock, $description, $imagePath, $productId);

    if ($stmt->execute()) {
        echo '<div class="success-message">Product updated successfully!</div>';
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
    <title>Update Product</title>
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
        <h1>Update Product</h1>
        <p>Modify the product information and save the changes.</p>
    </div>

    <div class="categories">
    <a href="dashboard.php">CREATE NEW PRODUCT</a>
    <a href="view_products.php">VIEW PRODUCTS</a>
    </div>

    <h2>Update Product Details</h2>

    <form action="edit_product.php?product_id=<?php echo $productId; ?>" method="post" enctype="multipart/form-data">
        <label for="product_name">Product Name:</label><br>
        <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($product['product_name']); ?>" required><br><br>

        <label for="category_id">Category ID:</label><br>
        <input type="number" id="category_id" name="category_id" value="<?php echo htmlspecialchars($product['category_id']); ?>"><br><br>

        <label for="price">Price:</label><br>
        <input type="number" step="0.01" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required><br><br>

        <label for="stock">Stock:</label><br>
        <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>"><br><br>

        <label for="product_image">Product Image:</label><br>
        <input type="file" id="product_image" name="product_image" accept="image/*"><br><br>
        <small style="color: red;">Leave blank if you don't want to change the image.</small><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description"><?php echo htmlspecialchars($product['description']); ?></textarea><br><br>

        <button type="submit">Update Product</button>
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
</body>
</html>
