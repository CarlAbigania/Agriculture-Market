<?php
session_start();
require_once '../../includes/db.php';

$error_message = '';
$success = false;

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password = $_POST['password'];

    if (empty($name) || empty($email) || empty($phone_number) || empty($password)) {
        $error_message = 'All fields are required.';
    } elseif (!preg_match("/^[0-9]{10,15}$/", $phone_number)) {
        $error_message = 'Invalid phone number format.';
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $role = 'customer';

        $checkQuery = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = 'This email is already registered.';
        } else {
            $query = "INSERT INTO users (name, email, phone_number, password, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssss", $name, $email, $phone_number, $hashed_password, $role);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $success = true;
            } else {
                $error_message = 'Error during registration. Please try again.';
            }
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
    <title>Register - AgriMarket</title>
    <link rel="stylesheet" href="../../assets/css/register.css">
</head>
<body>
    <div class="login-container">
        <div class="image-section">
            <img src="../../assets/images/agrimarket_logo.png" alt="Agri Market Logo" class="logo">
            <div class="info">
                <p style="font-style: italic;">"AgriMarket: Bridging Farmers and Communities with Fresh, Local Goodness!"</p>
            </div>
        </div>

        <?php if ($success): ?>
        <div id="success-popup" class="popup" style="display: flex;">
            <div class="popup-content">
                <img src="../../assets/images/success-icon.png" alt="Success Icon" class="success-icon">
                <h2>You Rock!</h2>
                <p>Your information is received.</p>
                <button onclick="window.location.href='login.php'">Welcome!</button>
            </div>
        </div>
        <?php endif; ?>

        <div class="form-section">
            <h2>Register</h2>
            <?php if (!empty($error_message)): ?>
                <p style="color: red; font-weight: bold;"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            <form action="register.php" method="POST">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required><br><br>

                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" required><br><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="password">Password:</label>
                <div class="password-wrapper">
                    <input type="password" id="password" name="password" required><br><br>
                    <label>
                        <input type="checkbox" id="show-password" onclick="togglePassword()"> Show Password
                    </label>
                </div><br><br>

                <button type="submit" name="submit">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </div>
    </div>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var checkbox = document.getElementById("show-password");
            passwordField.type = checkbox.checked ? "text" : "password";
        }
    </script>
</body>
</html>
