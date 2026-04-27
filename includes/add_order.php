<?php
include 'db.php'; // Include the database connection

// Get the JSON data from the frontend
$data = json_decode(file_get_contents("php://input"), true);

$customer_id = $data['customer_id'];
$total_amount = $data['total_amount'];
$order_status = $data['order_status'];
$products = $data['products']; // Array of products with product_id, quantity, and price

// Insert the order into the orders table
$sql = "INSERT INTO orders (customer_id, total_amount, order_status) VALUES ('$customer_id', '$total_amount', '$order_status')";
if ($conn->query($sql) === TRUE) {
    $order_id = $conn->insert_id;

    // Insert the order details
    foreach ($products as $product) {
        $product_id = $product['product_id'];
        $quantity = $product['quantity'];
        $price = $product['price'];

        $order_details_sql = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES ('$order_id', '$product_id', '$quantity', '$price')";
        $conn->query($order_details_sql);
    }

    echo json_encode(["status" => "success", "order_id" => $order_id]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}

$conn->close();
?>
