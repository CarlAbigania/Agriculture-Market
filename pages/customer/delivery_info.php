<?php
session_start();
require_once '../../includes/db.php';

$error_message = '';
$success_message = '';

if (!isset($_SESSION['user_logged_in']) || $_SESSION['user_logged_in'] !== true) {
    header("Location: ../auth/login.php");
    exit();
}

$userEmail = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_address'])) {
    $newDeliveryAddress = $_POST['delivery_address'];

    if (empty($newDeliveryAddress)) {
        $error_message = 'Delivery address cannot be empty.';
    } else {
        $stmt = $conn->prepare("UPDATE users SET delivery_address = ? WHERE email = ?");
        $stmt->bind_param("ss", $newDeliveryAddress, $userEmail);
        if ($stmt->execute()) {
            $success_message = 'Delivery address updated successfully!';
        } else {
            $error_message = 'Error updating address. Please try again.';
        }
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Delivery Information</title>
</head>
<body>
    <div class="container" style="border: 2px solid #8e8e8e; padding: 5vh; border-radius: 15px; max-width: 500px; margin: 50px auto;">
        <h2>Update Your Delivery Information</h2>
        <?php if (!empty($success_message)): ?>
            <p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($success_message); ?></p>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <p style="color: red; font-weight: bold;"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>

        <form action="delivery_info.php" method="POST">
            <label for="delivery_address">New Delivery Address:</label><br>
            <textarea name="delivery_address" required></textarea><br><br>
            <button type="submit" name="update_address">Update Address</button>
        </form>
        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
