<?php
session_start();

// Redirect to home if logged in, otherwise to login page
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
        header("Location: pages/admin/dashboard.php");
    } else {
        header("Location: pages/home/home.php");
    }
} else {
    header("Location: pages/auth/login.php");
}
exit();
?>
