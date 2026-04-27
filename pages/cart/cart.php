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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/cart.css">
</head>

<body>
    <div class="cart-wrapper">
        <div class="cart-container">
            <!-- Left Side: Item List -->
            <div class="cart-main">
                <div class="cart-header">
                    <h2>Shopping Cart</h2>
                    <span><?php echo count($cart_items); ?> Items</span>
                </div>

                <?php if (!empty($error_message)): ?>
                    <div class="error-message">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <?= htmlspecialchars($error_message) ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST" id="cart-form">
                    <div class="cart-items">
                        <?php if (count($cart_items) > 0): ?>
                            <?php foreach ($cart_items as $item): ?>
                                <div class="cart-item">
                                    <img src="<?= $item['image_url'] ?>" alt="Product Image">
                                    <div class="item-info">
                                        <p><strong><?= htmlspecialchars($item['product_name']) ?></strong></p>
                                        <p class="item-price">₱ <?= number_format($item['price'], 2) ?></p>
                                        <p class="item-quantity">Quantity: <?= $item['quantity'] ?> Units</p>
                                    </div>
                                    <div class="item-actions">
                                        <button type="submit" name="edit" value="<?= $item['cart_item_id'] ?>" class="qty-btn" title="Add More">
                                            <i class="fa-solid fa-plus"></i>
                                        </button>
                                        <button type="submit" name="delete" value="<?= $item['cart_item_id'] ?>" class="remove-btn">
                                            <i class="fa-solid fa-trash-can"></i> Remove
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="cart_item_ids[]" value="<?= $item['cart_item_id'] ?>">
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fa-solid fa-cart-shopping"></i>
                                <p>Your shopping cart is currently empty.</p>
                                <a href="../customer/dashboard.php" style="color: var(--primary-green); font-weight: 600; text-decoration: none; display: block; margin-top:15px;">Continue Shopping</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Right Side: Sidebar Status/Summary -->
            <div class="cart-sidebar">
                <form action="" method="POST">
                    <!-- Hidden fields to preserve cart selection if needed -->
                    <?php foreach ($cart_items as $item): ?>
                        <input type="hidden" name="cart_item_ids[]" value="<?= $item['cart_item_id'] ?>">
                    <?php endforeach; ?>

                    <div class="sidebar-section address-section">
                        <h3>Delivery Information</h3>
                        <label for="delivery_address">Shipping Address</label>
                        <textarea name="delivery_address" id="delivery_address" placeholder="Enter your full delivery address here..." required><?= htmlspecialchars($delivery_address) ?></textarea>
                    </div>

                    <div class="sidebar-section summary-section">
                        <h3>Order Summary</h3>
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>₱ <?= number_format($total, 2) ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span style="color:#059669; font-weight:600;">FREE</span>
                        </div>
                        <div class="summary-total">
                            <span>Total</span>
                            <span>₱ <?= number_format($total, 2) ?></span>
                        </div>
                    </div>

                    <div class="cart-actions">
                        <?php if (count($cart_items) > 0): ?>
                            <button type="submit" name="confirm" class="confirm-btn">
                                Proceed to Checkout
                            </button>
                        <?php else: ?>
                            <button type="button" class="confirm-btn" disabled>
                                Empty Cart
                            </button>
                        <?php endif; ?>
                        
                        <a class="cancel-btn" href="../customer/dashboard.php">
                            <i class="fa-solid fa-arrow-left"></i> Back to Shopping
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

