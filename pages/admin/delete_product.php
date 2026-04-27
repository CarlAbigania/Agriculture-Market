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

// Check if products exist
if (isset($_GET['product_id'])) {
    $product_id = intval($_GET['product_id']); // Sanitize the input

    // Prepare and execute the delete query
    $delete_sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        echo "<script>alert('Product deleted successfully.'); window.location.href='delete_product.php';</script>";
    } else {
        echo "<script>alert('Failed to delete the product.'); window.location.href='delete_product.php';</script>";
    }

    $stmt->close();
}

// Fetch all products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Delete</title>
    <link rel="stylesheet" href="../../assets/css/admin_dashboard.css">
    <style>
        body {
            background-color: #005129; /* Green background */
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            text-align: center;
        }

        h1 {
            margin-top: 5vh;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
            position: absolute;
            left: 20px;
            top: 20px; 
        }

        .back-btn:hover {
            background-color: #444;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: #fff;
            color: #333;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #000000;
        }

        table th {
            background-color: #333;
            color: #fff;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table a {
            color: #005129;
            text-decoration: none;
        }

        table a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Delete a Product</h1>

        <!-- Back Button Positioned on the Left -->
        <a class="back-btn" href="view_products.php">Back</a>

        <?php
        // Check if products exist
        if ($result->num_rows > 0) {
            echo "<table><tr><th>Product Name</th><th>Price</th><th>Stock</th><th>Actions</th></tr>";
            while ($product = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($product['product_name']) . "</td>";
                echo "<td>₱" . htmlspecialchars($product['price']) . "</td>";
                echo "<td>" . htmlspecialchars($product['stock']) . "</td>";
                echo "<td><a href='#' onclick='confirmDelete(" . $product['product_id'] . ")'>Delete</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No products found.</p>";
        }

        $conn->close();
        ?>

        <script>
            function confirmDelete(productId) {
                // Show confirmation popup
                if (confirm("Are you sure you want to delete this product?")) {
                    // If the user clicked "OK", redirect to delete_product.php with the product_id
                    window.location.href = 'delete_product.php?product_id=' + productId;
                }
            }
        </script>
    </div>

</body>
</html>
