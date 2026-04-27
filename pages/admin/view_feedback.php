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
    <link rel="stylesheet" href="../../assets/css/admin_dashboard.css">
</head>
<body>
    <header>
        <img src="../../assets/images/agrimarket_logo.png" alt="AgroMarket Logo">
        <p>Welcome, Admin!</p>
        <!-- Log Out Button -->
        <button class="logout-btn" onclick="confirmLogout()">Log Out</button>
    </header>
    <div class="hero">
        <h1>WELCOME TO<br><span>AGRIMARKET ONLINE SHOP</span></h1>
        <p>"AgriMarket: Bridging Farmers and Communities with Fresh, Local Goodness!"</p>
    </div>

    <div class="quote">
        <h1>View customer feedbacks</h1>
        <p>Show Agrimarket's valued customer opinions</p>
    </div>

    <div class="categories">
        <a href="dashboard.php">CREATE NEW PRODUCT</a>
        <a href="view_products.php">VIEW PRODUCTS</a>
        <a href="view_feedback.php" class="active">VIEW FEEDBACKS</a>
        <a href="view_orders.php">VIEW ORDERS</a>
    </div>

    <h2>Customer Feedback</h2>

    <div class="table-container">
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($feedback = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($feedback['name']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['email']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['message']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['created_at']); ?></td>
                        <td>
                            <a href="delete_feedback.php?id=<?php echo $feedback['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Delete this feedback?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="empty-state">
            <i class="fa-solid fa-comment-slash"></i>
            <p>No customer feedback yet.</p>
            <p class="sub-text">Messages from customers will appear here.</p>
        </div>
    <?php endif; ?>
    </div>


    <script>
        // JavaScript function to confirm logout
        function confirmLogout() {
            const userConfirmation = confirm("Are you sure you want to log out?");
            if (userConfirmation) {
                // If confirmed, log out and redirect to login page
                window.location.href = '../../includes/logout.php'; // Change this URL to your logout script
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
