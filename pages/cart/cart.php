<?php
session_start();
require_once '../../includes/db.php'; // Include the DB connection

$customer_id = $_SESSION['user_id'] ?? null;
$error_message = '';
$delivery_address = $_SESSION['delivery_address'] ?? ''; // Retrieve address from session

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update the delivery address
    if (isset($_POST['delivery_address'])) {
        $delivery_address = trim($_POST['delivery_address']);
        $_SESSION['delivery_address'] = $delivery_address; // Store in session
        if (empty($delivery_address)) {
            $error_message = 'Delivery address cannot be empty.';
        }
    }

    // Increment the quantity by 1 when the Add button is clicked
    if (isset($_POST['edit'])) {
        $cart_item_id = intval($_POST['edit']);

        $sql = "UPDATE cart_items
                SET quantity = quantity + 1
                WHERE cart_item_id = ? AND customer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $cart_item_id, $customer_id);
        $stmt->execute();
    }

    // Decrease the quantity by 1 or delete the item when Remove is clicked
    if (isset($_POST['delete'])) {
        $cart_item_id = intval($_POST['delete']);

        // Fetch the current quantity of the item
        $sql = "SELECT quantity
                FROM cart_items
                WHERE cart_item_id = ? AND customer_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $cart_item_id, $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $item = $result->fetch_assoc();

        if ($item && $item['quantity'] > 1) {
            // Reduce quantity if greater than 1
            $sql_update = "UPDATE cart_items
                           SET quantity = quantity - 1
                           WHERE cart_item_id = ? AND customer_id = ?";
            $stmt = $conn->prepare($sql_update);
            $stmt->bind_param("ii", $cart_item_id, $customer_id);
            $stmt->execute();
        } else {
            // Delete the item if quantity is 1
            $sql_delete = "DELETE FROM cart_items
                           WHERE cart_item_id = ? AND customer_id = ?";
            $stmt = $conn->prepare($sql_delete);
            $stmt->bind_param("ii", $cart_item_id, $customer_id);
            $stmt->execute();
        }
    }

    if (isset($_POST['confirm'])) {
        $delivery_address = trim($_POST['delivery_address']);

        if (empty($delivery_address)) {
            $error_message = 'Delivery address must be specified before confirming.';
        } else {
            $order_date = date("Y-m-d H:i:s");
            $total = 0;

            // Calculate the total price
            foreach ($_POST['cart_item_ids'] as $cart_item_id) {
                $cart_item_id = intval($cart_item_id);

                $sql_item = "SELECT products.price, cart_items.quantity
                             FROM cart_items
                             JOIN products ON cart_items.product_id = products.product_id
                             WHERE cart_items.cart_item_id = ? AND cart_items.customer_id = ?";
                $stmt = $conn->prepare($sql_item);
                $stmt->bind_param("ii", $cart_item_id, $customer_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $item = $result->fetch_assoc();

                if ($item) {
                    $total += $item['price'] * $item['quantity'];
                }
            }

            // Insert the order into the database
            $sql_order = "INSERT INTO orders (customer_id, order_date, total, delivery_address)
                          VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_order);
            $stmt->bind_param("isds", $customer_id, $order_date, $total, $delivery_address);
            $stmt->execute();
            $order_id = $stmt->insert_id; // Get the order ID

            // Insert order items into the database
            foreach ($_POST['cart_item_ids'] as $cart_item_id) {
                $cart_item_id = intval($cart_item_id);

                $sql_item = "SELECT products.product_id, cart_items.quantity, products.price
                             FROM cart_items
                             JOIN products ON cart_items.product_id = products.product_id
                             WHERE cart_items.cart_item_id = ? AND cart_items.customer_id = ?";
                $stmt = $conn->prepare($sql_item);
                $stmt->bind_param("ii", $cart_item_id, $customer_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $item = $result->fetch_assoc();

                if ($item) {
                    $sql_order_item = "INSERT INTO order_items (order_id, product_id, quantity, price)
                                       VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql_order_item);
                    $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
                    $stmt->execute();
                }
            }

            // Clear the cart after confirmation
            $sql_clear_cart = "DELETE FROM cart_items WHERE customer_id = ?";
            $stmt = $conn->prepare($sql_clear_cart);
            $stmt->bind_param("i", $customer_id);
            $stmt->execute();

            // Redirect to the receipt page (order confirmation)
            header("Location: order_confirmation.php?order_id=" . $order_id);
            exit;
        }
    }
}

// Fetch updated cart items
$sql = "SELECT
            cart_items.cart_item_id,
            products.product_name AS product_name,
            products.price,
            products.image_url,
            cart_items.quantity
        FROM cart_items
        JOIN products ON cart_items.product_id = products.product_id
        WHERE cart_items.customer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
$total = 0;
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $total += $row['price'] * $row['quantity'];
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="../../assets/css/cart.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .cart-container {
            width: 90%;
            max-width: 800px;
            margin: 30px auto;
            background: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
        }

        .cart-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .cart-items {
            margin-bottom: 20px;
        }

        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f7f7f7;
        }

        .cart-item img {
            width: 80px;
            height: 80px;
            border-radius: 5px;
            object-fit: cover;
            margin-right: 15px;
        }

        .item-info {
            flex-grow: 1;
        }

        .item-info p {
            margin: 5px 0;
            font-size: 14px;
        }

        .actions button {
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 14px;
        }

        .actions button:hover {
            background-color: #0056b3;
        }

        .cart-total {
            text-align: center;
            font-size: 18px;
            margin-top: 20px;
            font-weight: bold;
        }

        .address-section {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .address-section label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .address-section textarea {
            width: 100%;
            height: 80px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            resize: none;
        }

        .address-section .update-btn {
            margin-top: 10px;
            display: inline-block;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 15px;
            cursor: pointer;
            font-size: 14px;
        }

        .address-section .update-btn:hover {
            background-color: #218838;
        }

        .cart-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .cart-actions a,
        .cart-actions button {
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            color: white;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .cart-actions a.cancel-btn {
            background-color: #dc3545;
        }

        .cart-actions a.cancel-btn:hover {
            background-color: #b52a37;
        }

        .cart-actions button.confirm-btn {
            background-color: #007bff;
        }

        .cart-actions button.confirm-btn:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: #dc3545;
            text-align: center;
            margin-bottom: 10px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="cart-container">
        <h2>Items in Your Cart</h2>

        <?php if (!empty($error_message)): ?>
            <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <form action="" method="POST">
    <div class="cart-items">
        <?php if (count($cart_items) > 0): ?>
            <?php foreach ($cart_items as $item): ?>
                <div class="cart-item">
                    <img src="<?= $item['image_url'] ?>" alt="Product Image">
                    <div class="item-info">
                        <p><strong><?= $item['product_name'] ?></strong></p>
                        <p>₱ <?= number_format($item['price'], 2) ?> per Bag</p>
                        <p>Quantity: <?= $item['quantity'] ?> Bags</p>
                    </div>
                    <div class="actions">
                        <button type="submit" name="edit" value="<?= $item['cart_item_id'] ?>" class="edit-btn">Add</button>
                        <button type="submit" name="delete" value="<?= $item['cart_item_id'] ?>" class="delete-btn">Remove</button>
                    </div>
                </div>
                <input type="hidden" name="cart_item_ids[]" value="<?= $item['cart_item_id'] ?>">
            <?php endforeach; ?>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>

    <div class="cart-total">
        <p>Total: ₱ <?= number_format($total, 2) ?></p>
    </div>

    <div class="address-section">
        <label for="delivery_address">Delivery Address:</label>
        <textarea name="delivery_address" id="delivery_address" required><?= htmlspecialchars($delivery_address) ?></textarea>
    </div>

    <div class="cart-actions">
        <a class="cancel-btn" href="../customer/dashboard.php">Cancel</a>

        <?php if (count($cart_items) > 0): ?>
            <button type="submit" name="confirm" class="confirm-btn">Confirm</button>
        <?php else: ?>
            <button type="button" class="confirm-btn" disabled>Confirm</button>
        <?php endif; ?>
    </div>
</form>

    </div>
</body>

</html>

