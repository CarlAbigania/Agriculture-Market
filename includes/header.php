<?php
session_start();
// Use absolute-like path relative to the file itself for includes
include_once __DIR__ . '/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: " . BASE_URL . "pages/auth/login.php");
    exit();
}

// Get user_id from session
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    // If user_id is not set, redirect to login
    header("Location: " . BASE_URL . "pages/auth/login.php");
    exit();
}

// Query to count the total number of items in the cart for the specific user
$sql_cart_count = "SELECT SUM(quantity) AS total_items FROM cart_items WHERE customer_id = ?";
$stmt = $conn->prepare($sql_cart_count);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$total_items = $row['total_items'] ?? 0;

$stmt->close();

// Get the category_id from the URL, default to 1 (Vegetables) if not set
$categoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 1;

// Get category name based on category_id
$categoryNames = [
    1 => 'Fruits',
    2 => 'Food',
    3 => 'Vegetables',
    4 => 'Poultry',
    5 => 'Fish',
    6 => 'Meats'
];

$categoryName = $categoryNames[$categoryId] ?? 'Unknown';

// Fetch products for the selected category
$stmt = $conn->prepare("
    SELECT p.product_id, p.product_name, p.description, p.price, p.image_url
    FROM products p
    WHERE p.category_id = ?
");
$stmt->bind_param("i", $categoryId); // Bind category_id as integer
$stmt->execute();
$result = $stmt->get_result();

// Check if products exist
$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$stmt->close();
$conn->close();
?>
