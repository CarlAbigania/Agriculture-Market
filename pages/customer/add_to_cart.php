<?php
session_start();
include '../../includes/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}

// Get user_id from session
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
header("Location: ../login.php");
    exit();
}

// Retrieve data from the form
$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);

// Check if the product is already in the cart for this user
$sql_check = "SELECT quantity FROM cart_items WHERE customer_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Update quantity if the product exists in the cart
    $row = $result->fetch_assoc();
    $new_quantity = $row['quantity'] + $quantity;

    if ($new_quantity > 0) {
        $sql_update = "UPDATE cart_items SET quantity = ? WHERE customer_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql_update);
        $stmt->bind_param("iii", $new_quantity, $user_id, $product_id);
        $stmt->execute();
    }
} else {
    // Insert a new record if the product does not exist in the cart
    $sql_insert = "INSERT INTO cart_items (customer_id, product_id, quantity) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql_insert);
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $stmt->execute();
}

$stmt->close();
$conn->close();

// Redirect back to the customer panel with success message
header("Location: dashboard.php?success=Product added to cart");
exit();
?>
