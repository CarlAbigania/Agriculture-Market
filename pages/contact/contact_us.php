<?php
session_start();
require_once '../../includes/db.php';

$success_message = '';
$error_message = '';

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['Message'];

    if (empty($name) || empty($email) || empty($message)) {
        $error_message = 'All fields are required.';
    } else {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);
        if ($stmt->execute()) {
            $success_message = 'Your message has been sent successfully!';
        } else {
            $error_message = 'Failed to send message. Please try again.';
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
    <title>Contact Us - AgriMarket</title>
    <link rel="stylesheet" href="../../assets/css/contact.css">
</head>
<body>
    <div class="login-container">
        <div class="image-section">
            <a href="../home/home.php" class="x-btn">X</a>
            <h2>Send Us a Message</h2>
            <div class="info">
                <?php if (!empty($success_message)): ?>
                    <p style="color: #00B85E; font-weight: bold;"><?php echo htmlspecialchars($success_message); ?></p>
                <?php endif; ?>
                <?php if (!empty($error_message)): ?>
                    <p style="color: red; font-weight: bold;"><?php echo htmlspecialchars($error_message); ?></p>
                <?php endif; ?>
                <form action="contact_us.php" method="POST">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>

                    <label for="delivery_address">Message</label><br>
                    <textarea name="Message" required></textarea><br><br>

                    <button type="submit" name="submit">Send</button>
                </form>
            </div>
        </div>

        <div class="form-section">
            <h2>Get in Touch by any of the following means:</h2>
            <h3>Address</h3>
            <p>------------------------</p>
            <h3>Email</h3>
            <p>agrImarket@example.com</p>
            <h3>Phone Number</h3>
            <p>07012345678</p>
        </div>
    </div>
</body>
</html>
