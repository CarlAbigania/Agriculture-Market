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

// Delete order logic
if (isset($_GET['delete_order_id'])) {
    $order_id = $_GET['delete_order_id'];

    // SQL query to delete order
    $delete_sql = "DELETE FROM orders WHERE order_id = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "<script>alert('Order deleted successfully');</script>";
    } else {
        echo "<script>alert('Error deleting order');</script>";
    }
}

// Fetch all orders with customer details (name)
$sql = "SELECT o.order_id, o.order_date, o.total, o.delivery_address, u.name
        FROM orders o
        JOIN users u ON o.customer_id = u.id
        ORDER BY o.order_date DESC";  // Orders by the latest order date
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - View Orders</title>
    <link rel="stylesheet" href="../../assets/css/admin_dashboard.css">
</head>

<body>
    <header>
        <img src="../../assets/images/agrimarket_logo.png" alt="AgriMarket Logo">
        <p>Welcome, Admin!</p>
        <button class="logout-btn" onclick="confirmLogout()">Log Out</button>
    </header>

    <div class="hero">
        <h1>WELCOME TO<br><span>AGRIMARKET ONLINE SHOP</span></h1>
        <p>"AgriMarket: Bridging Farmers and Communities with Fresh, Local Goodness!"</p>
    </div>

    <div class="quote">
        <h1>View All Orders</h1>
        <p>Here you can view all orders placed by users.</p>
    </div>

    <div class="categories">
        <a href="dashboard.php">CREATE NEW PRODUCT</a>
        <a href="view_products.php">VIEW PRODUCTS</a>
        <a href="view_feedback.php">VIEW FEEDBACKS</a>
        <a href="view_orders.php" class="active">VIEW ORDERS</a>
    </div>

    <h2>All Orders</h2>
    <div class="table-container">
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Order Date</th>
                    <th>Total</th>
                    <th>Delivery Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?= $row['order_id'] ?></td>
                        <td><strong><?= htmlspecialchars($row['name']) ?></strong></td>
                        <td><?= date('F j, Y, g:i a', strtotime($row['order_date'])) ?></td>
                        <td>₱ <?= number_format($row['total'], 2) ?></td>
                        <td><?= htmlspecialchars($row['delivery_address']) ?></td>
                        <td><a href="?delete_order_id=<?= $row['order_id'] ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <i class="fa-solid fa-box-open"></i>
            <p>No orders have been placed yet.</p>
            <p class="sub-text">New orders will appear here once customers start shopping.</p>
        </div>
    <?php endif; ?>
    </div>


    <script>
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
// Close the connection
$conn->close();
?>
