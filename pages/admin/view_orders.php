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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../../assets/css/view_products.css">
    <link rel="stylesheet" href="../../assets/css/view_orders.css">
    <style>
        .empty-state {
            text-align: center;
            padding: 80px 40px;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            margin: 40px auto;
            max-width: 600px;
            border: 1px solid #eee;
        }

        .empty-state i {
            font-size: 80px;
            color: #005129;
            margin-bottom: 25px;
            display: block;
        }

        .empty-state p {
            font-size: 1.5rem;
            color: #333;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .empty-state .sub-text {
            font-size: 1.1rem;
            color: #666;
            font-weight: 400;
            margin: 0;
        }
    </style>
</head>

<body>
    <header>
        <img src="../../assets/images/agrimarket_logo.png" alt="AgriMarket Logo">
        <p>Welcome, Admin!</p>
        <button class="logout-btn" onclick="confirmLogout()">Log Out</button>
    </header>

    <div class="hero">
        <h1>- WELCOME TO<br><br><span style="font-size: 50px;">AGRIMARKET ONLINE SHOP</span><br><br></h1>
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
        <a href="view_orders.php">VIEW ORDERS</a> <!-- New link added here -->
    </div>

    <h2>All Orders</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Order Date</th>
                    <th>Total</th>
                    <th>Delivery Address</th>
                    <th>Action</th> <!-- Column for delete action -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['order_id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= date('F j, Y, g:i a', strtotime($row['order_date'])) ?></td>
                        <td>₱ <?= number_format($row['total'], 2) ?></td>
                        <td><?= htmlspecialchars($row['delivery_address']) ?></td>
                        <td><a href="?delete_order_id=<?= $row['order_id'] ?>" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a></td> <!-- Delete link -->
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
        function confirmLogout() {
            const userConfirmation = confirm("Are you sure you want to log out?");
            if (userConfirmation) {
                window.location.href = '../../includes/logout.php';
            }
        }
    </script>
</body>

</html>

<?php
// Close the connection
$conn->close();
?>
