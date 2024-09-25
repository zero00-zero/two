

<?php
session_start();

// Check if the user is logged in AND is an admin
if (!isset($_SESSION['user_id']) || !$_SESSION['admin']) { 
    header("Location: login.php"); // Or redirect to index.php 
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>BakeEase Bakery - Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo $_SESSION['user_name']; ?>!</p>

    <ul>
        <li><a href="manage_products.php">Manage Products</a></li>
        <li><a href="manage_orders.php">Manage Orders</a></li>
        <li><a href="manage_users.php">Manage Users</a></li> 
        <li><a href="admin_logout.php">Logout</a></li>
    </ul>
</body>
</html>