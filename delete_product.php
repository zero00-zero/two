
<?php
include 'db.php'; 
session_start();

// Check if the user is logged in AND is an admin
if (!isset($_SESSION['user_id']) || !$_SESSION['admin']) { 
    header("Location: login.php"); // Or redirect to index.php 
    exit();
}



// Get the product ID from the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
} else {
    echo "Product ID not specified.";
    exit();
}

// Delete the product from the database
$sql = "DELETE FROM products WHERE id='$product_id'"; 

if ($conn->query($sql) === TRUE) {
    echo "<p>Product deleted successfully!</p>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Redirect back to manage_products.php
header("Location: manage_products.php"); 
exit(); 
?>