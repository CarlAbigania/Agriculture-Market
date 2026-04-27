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

// Check if feedback ID is provided for deletion
if (isset($_GET['feedback_id'])) {
    $feedback_id = intval($_GET['feedback_id']); // Sanitize the input

    // Debugging: Log the feedback_id
    error_log("Attempting to delete feedback with ID: $feedback_id");

    // Prepare and execute the delete query
    $delete_sql = "DELETE FROM contact_messages WHERE id = ?";
    $stmt = $conn->prepare($delete_sql);

    if ($stmt) {
        $stmt->bind_param("i", $feedback_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo "<script>alert('Feedback deleted successfully.'); window.location.href='view_feedback.php';</script>";
            } else {
                echo "<script>alert('No feedback found with the given ID.'); window.location.href='view_feedback.php';</script>";
            }
        } else {
            echo "<script>alert('Failed to execute the delete query: " . $stmt->error . "'); window.location.href='view_feedback.php';</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Failed to prepare the SQL statement: " . $conn->error . "'); window.location.href='view_feedback.php';</script>";
    }
}

// Fetch all feedback entries
$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Feedback</title>
    <style>
        body {
            background-color: #005129;
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            text-align: center;
        }

        h1 {
            margin-top: 5vh;
        }

        .back-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
            position: absolute;
            left: 20px;
            top: 20px; 
        }

        .back-btn:hover {
            background-color: #444;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: #fff;
            color: #333;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #000000;
        }

        table th {
            background-color: #333;
            color: #fff;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        table a {
            color: #005129;
            text-decoration: none;
        }

        table a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Manage Feedback</h1>

        <!-- Back Button Positioned on the Left -->
        <a class="back-btn" href="view_products.php">Back</a>

        <?php
        // Check if feedback entries exist
        if ($result->num_rows > 0) {
            echo "<table><tr><th>Name</th><th>Email</th><th>Message</th><th>Date</th><th>Actions</th></tr>";
            while ($feedback = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($feedback['name']) . "</td>";
                echo "<td>" . htmlspecialchars($feedback['email']) . "</td>";
                echo "<td>" . htmlspecialchars($feedback['message']) . "</td>";
                echo "<td>" . htmlspecialchars($feedback['created_at']) . "</td>";
                echo "<td><a href='#' onclick='confirmDelete(" . $feedback['id'] . ")'>Delete</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No feedback entries found.</p>";
        }

        $conn->close();
        ?>
    </div>

        <script>
            function confirmDelete(feedbackId) {
            // Show confirmation popup
                if (confirm("Are you sure you want to delete this feedback?")) {
            // If the user clicked "OK", redirect to delete_feedback.php with the feedback_id
                window.location.href = 'delete_feedback.php?feedback_id=' + feedbackId;
            }
        }
        </script>

</body>
</html>
