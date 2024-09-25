<?php
include 'db.php';
session_start();

// ... (Admin login check) ...

// Get the order ID from the URL
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
} else {
    echo "Order ID not specified.";
    exit();
}

// Delete the order from the database
$sql = "DELETE FROM orders WHERE id='$order_id'"; 

if ($conn->query($sql) === TRUE) {
    echo "<p>Order deleted successfully!</p>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Redirect back to manage_orders.php
header("Location: manage_orders.php"); 
exit(); 
?>