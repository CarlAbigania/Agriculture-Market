<?php
// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'agrimarket');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Define the root URL/Path if needed
// Define the root URL/Path if needed
if (!defined('BASE_URL')) {
    if (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST'] === 'agri.local') {
        define('BASE_URL', '/');
    } else {
        define('BASE_URL', '/Agriculture-Market/');
    }
}
?>
