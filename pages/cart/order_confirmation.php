<?php
session_start();
include '../../includes/db.php'; // Include the DB connection

// Make sure the user is logged in and has an order ID
if (!isset($_SESSION['user_id']) || !isset($_GET['order_id'])) {
    header("Location: ../customer/dashboard.php");
    exit;
}

$customer_id = $_SESSION['user_id'];
$order_id = intval($_GET['order_id']);

// Fetch the order details including the delivery address
$sql_order = "SELECT order_id, customer_id, order_date, total, delivery_address FROM orders WHERE order_id = ? AND customer_id = ?";
$stmt = $conn->prepare($sql_order);
$stmt->bind_param("ii", $order_id, $customer_id);
$stmt->execute();
$order_result = $stmt->get_result();

if ($order_result->num_rows === 0) {
    echo "Order not found.";
    exit;
}

$order = $order_result->fetch_assoc();


// Fetch the order items
$sql_order_items = "SELECT oi.quantity, p.product_name, oi.price
                    FROM order_items oi
                    JOIN products p ON oi.product_id = p.product_id
                    WHERE oi.order_id = ?";
$stmt = $conn->prepare($sql_order_items);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_items_result = $stmt->get_result();

// Calculate total for the order
$total = 0;
$order_items = [];
while ($item = $order_items_result->fetch_assoc()) {
    $order_items[] = $item;
    $total += $item['quantity'] * $item['price'];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="../../assets/css/cart.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .order-confirmation-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            font-size: 24px;
            color: #333;
        }

        .order-details,
        .order-items {
            margin: 20px 0;
        }

        .order-details h3,
        .order-items h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }

        .order-details p {
            font-size: 16px;
            color: #555;
            margin: 5px 0;
        }

        .order-items table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .order-items th,
        .order-items td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .order-items th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .order-items td {
            color: #555;
        }

        .order-items tr:last-child td {
            border-bottom: none;
        }

        .btn,
        .cancel-btn {
            display: inline-block;
            padding: 12px 20px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            border-radius: 5px;
            text-align: center;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .btn:hover,
        .cancel-btn:hover {
            background-color: #218838;
        }

        .cancel-btn {
            background-color: #dc3545;
        }

        .cancel-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <div class="order-confirmation-container">
        <h2>Order Confirmation</h2>

        <div class="order-details">
            <h3>Order ID: <?= $order['order_id'] ?></h3>
            <p><strong>Order Date:</strong> <?= date('F j, Y, g:i a', strtotime($order['order_date'])) ?></p>
            <p><strong>Total Amount:</strong> ₱ <?= number_format($order['total'], 2) ?></p>
            <p><strong>Delivery Address:</strong> <?= htmlspecialchars($order['delivery_address']) ?></p>
        </div>


        <div class="order-items">
            <h3>Order Items</h3>
            <?php if (count($order_items) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order_items as $item): ?>
                            <tr>
                                <td><?= $item['product_name'] ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td>₱ <?= number_format($item['price'], 2) ?></td>
                                <td>₱ <?= number_format($item['quantity'] * $item['price'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- Grand total -->
                        <tr>
                            <td colspan="3" style="text-align: right; font-weight: bold;">Grand Total</td>
                            <td>₱ <?= number_format($total, 2) ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No items in this order.</p>
            <?php endif; ?>
        </div>


        <div class="order-actions">
            <a href="../customer/dashboard.php" class="cancel-btn">Back to Panel</a>
        </div>
    </div>
</body>

</html>
