<?php
session_start();
require_once '../../includes/db.php';

$error_message = '';

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_logged_in'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'customer') {
                header("Location: ../home/home.php");
            } elseif ($user['role'] == 'admin') {
                header("Location: ../admin/dashboard.php");
            }
            exit();
        } else {
            $error_message = 'Invalid email or password.';
        }
    } else {
        $error_message = 'Invalid email or password.';
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AgriMarket</title>
    <link rel="stylesheet" href="../../assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="image-section">
            <img src="../../assets/images/agrimarket_logo.png" alt="Agri Market Logo" class="logo">
            <div class="info">
                <p style="font-style: italic;">"AgriMarket: Bridging Farmers and Communities with Fresh, Local Goodness!"</p>
            </div>
        </div>

        <div class="form-section">
            <h2>Login to AgriMarket</h2>
            <?php if (!empty($error_message)): ?>
                <p style="color: red; font-weight: bold;"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required><br><br>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required><br><br>

                <label>
                    <input type="checkbox" id="show-password" onclick="togglePassword()"> Show Password
                </label><br><br>

                <button type="submit" name="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register</a></p>
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
