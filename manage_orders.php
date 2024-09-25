<?php
include 'db.php';
session_start();

// Admin login check (same as before)
if (!isset($_SESSION['user_id']) || !$_SESSION['admin']) {
    header("Location: login.php");
    exit();
}

// Fetch orders from the database (with optional sorting or filtering)
$sql = "SELECT o.*, u.name AS customer_name 
        FROM orders o 
        JOIN users u ON o.user_id = u.id
        ORDER BY o.id DESC"; // Order by order ID in descending order (newest first)
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>BakeEase Bakery - Manage Orders</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Manage Orders</h2>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Payment Method</th>
                <th>Address</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['customer_name'] . "</td>";
                    echo "<td>" . $row['product_id'] . "</td>";
                    echo "<td>" . $row['quantity'] . "</td>";
                    echo "<td>$" . $row['total_price'] . "</td>";
                    echo "<td>" . $row['payment_method'] . "</td>";
                    echo "<td>" . $row['address'] . "</td>";
                    echo "<td>" . $row['status'] . "</td>";
                    echo "<td>";
                    echo "<a href='edit_order.php?id=" . $row['id'] . "'>Edit</a> | ";
                    echo "<a href='delete_order.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this order?\")'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No orders found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>