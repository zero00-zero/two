<?php
include 'db.php';
session_start();

// Admin login check 
if (!isset($_SESSION['user_id']) || !$_SESSION['admin']) {
    header("Location: login.php"); 
    exit();
}

// Get the user ID from the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
} else {
    echo "User ID not specified.";
    exit();
}

// Delete the user from the database
$sql = "DELETE FROM users WHERE id = '$user_id' AND isAdmin = 0"; // Make sure to delete only non-admin users

if ($conn->query($sql) === TRUE) {
    echo "User deleted successfully.";
} else {
    echo "Error deleting user: " . $conn->error;
}

// Redirect back to the manage users page
header("Location: manage_users.php"); 
exit();
?>