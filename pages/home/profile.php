<?php
// Start the session
session_start();

// Include database connection
include '../../includes/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../userlogin/login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email, phone_number, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="profile.css"> <!-- Add your custom CSS here -->
</head>
<body>
    <header>
        <a href="../home/home.php">Back to Home</a>
    </header>

    <main>
        <section class="profile-container">
            <h1>Welcome, <?php echo htmlspecialchars($user['name']); ?>!</h1>
            <div class="profile-details">
                <p><strong>Full Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Phone Number:</strong> <?php echo htmlspecialchars($user['phone_number']); ?></p>
                <p><strong>Role:</strong> <?php echo htmlspecialchars(ucfirst($user['role'])); ?></p>
            </div>
        </section>
    </main>

</body>
</html>
