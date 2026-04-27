<?php
// Start the session
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "online_shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all feedback messages
$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Feedback</title>
    <link rel="stylesheet" href="../../assets/css/view_products.css">
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
        <h1>View customer feedbacks</h1>
        <p>Show Agrimarket's valued customer opinions</p>
    </div>

    <div class="categories">
        <a href="dashboard.php">CREATE NEW PRODUCT</a>
        <a href="view_products.php">VIEW PRODUCTS</a>
        <a href="view_feedback.php">VIEW FEEDBACKS</a>
        <a href="view_orders.php">VIEW ORDERS</a>
    </div>

    <h2>Customer Feedback</h2>

    <div class="table-container">
    <div class="table-header">
        <div class="header-item">Name</div>
        <div class="header-item">Email</div>
        <div class="header-item">Message</div>
        <div class="header-item">Date</div>
        <div class="header-item">Actions</div>
    </div>

    <div class="table-body">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($feedback = $result->fetch_assoc()): ?>
                <div class="table-row">
                    <div class="table-cell"><?php echo htmlspecialchars($feedback['name']); ?></div>
                    <div class="table-cell"><?php echo htmlspecialchars($feedback['email']); ?></div>
                    <div class="table-cell"><?php echo htmlspecialchars($feedback['message']); ?></div>
                    <div class="table-cell"><?php echo htmlspecialchars($feedback['created_at']); ?></div>
                    <div class="table-cell">
                        <a href="delete_feedback.php" class="btn-action">Delete</a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="table-row">
                <div class="table-cell" colspan="4">No feedback found.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

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

    <script>
        // JavaScript function to confirm logout
        function confirmLogout() {
            const userConfirmation = confirm("Are you sure you want to log out?");
            if (userConfirmation) {
                // If confirmed, log out and redirect to login page
                window.location.href = '../../includes/logout.php'; // Change this URL to your logout script
            }
        }
    </script>

</body>
</html>
