<?php
include 'db.php';
session_start();

// Admin login check
if (!isset($_SESSION['user_id']) || !$_SESSION['admin']) {
    header("Location: login.php");
    exit();
}

// Get the order ID from the URL
if (isset($_GET['id'])) {
    $order_id = $_GET['id'];
} else {
    echo "Order ID not specified.";
    exit();
}

// Fetch order details 
$sql = "SELECT o.*, u.name AS customer_name, p.name AS product_name, p.price AS product_price
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN products p ON o.product_id = p.id
        WHERE o.id = '$order_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $customer_name = $row['customer_name'];
    $product_name = $row['product_name'];
    $quantity = $row['quantity'];
    $total_price = $row['total_price'];
    $payment_method = $row['payment_method'];
    $address = $row['address'];
    $status = $row['status'];
} else {
    echo "Order not found.";
    exit();
}

// Handle form submission to update the order
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_order'])) {
    // Get updated order details from the form
    $new_quantity = $_POST['quantity'];
    $new_total_price = $_POST['total_price'];
    $new_payment_method = $_POST['payment_method'];
    $new_address = $_POST['address'];
    $new_status = $_POST['status'];

    // Sanitize inputs 
    $new_quantity = mysqli_real_escape_string($conn, $new_quantity);
    $new_total_price = mysqli_real_escape_string($conn, $new_total_price);
    $new_payment_method = mysqli_real_escape_string($conn, $new_payment_method);
    $new_address = mysqli_real_escape_string($conn, $new_address);
    $new_status = mysqli_real_escape_string($conn, $new_status);

    // Update the order in the database
    $sql = "UPDATE orders SET 
            quantity = '$new_quantity',
            total_price = '$new_total_price',
            payment_method = '$new_payment_method',
            address = '$new_address',
            status = '$new_status'
            WHERE id = '$order_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Order updated successfully!</p>";

        // Re-fetch the order details after the update
        $sql = "SELECT o.*, u.name AS customer_name, p.name AS product_name, p.price AS product_price
                FROM orders o
                JOIN users u ON o.user_id = u.id
                JOIN products p ON o.product_id = p.id
                WHERE o.id = '$order_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();

        // Update variables with the new values
        $customer_name = $row['customer_name'];
        $product_name = $row['product_name'];
        $quantity = $row['quantity'];
        $total_price = $row['total_price'];
        $payment_method = $row['payment_method'];
        $address = $row['address'];
        $status = $row['status'];

    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>BakeEase Bakery - Edit Order</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Edit Order (Order ID: <?php echo $order_id; ?>)</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=$order_id"; ?>">
        <label for="customer_name">Customer Name:</label>
        <input type="text" id="customer_name" name="customer_name" value="<?php echo $customer_name; ?>" readonly><br><br>

        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" value="<?php echo $product_name; ?>" readonly><br><br>

        <label for="quantity">Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="<?php echo $quantity; ?>" required><br><br>

        <label for="total_price">Total Price:</label>
        <input type="number" id="total_price" name="total_price" value="<?php echo $total_price; ?>" required step="0.01"><br><br>

        <label for="payment_method">Payment Method:</label>
        <select id="payment_method" name="payment_method" required>
            <option value="cod" <?php if ($payment_method == 'cod') echo 'selected'; ?>>Cash on Delivery</option>
            <option value="credit_card" <?php if ($payment_method == 'credit_card') echo 'selected'; ?>>Credit Card</option>
            <option value="paypal" <?php if ($payment_method == 'paypal') echo 'selected'; ?>>PayPal</option>
        </select><br><br>

        <label for="address">Address:</label>
        <textarea id="address" name="address" required><?php echo $address; ?></textarea><br><br>

        <label for="status">Order Status:</label>
        <select id="status" name="status" required>
            <option value="pending" <?php if ($status == 'pending') echo 'selected'; ?>>Pending</option>
            <option value="processing" <?php if ($status == 'processing') echo 'selected'; ?>>Processing</option>
            <option value="shipped" <?php if ($status == 'shipped') echo 'selected'; ?>>Shipped</option>
            <option value="delivered" <?php if ($status == 'delivered') echo 'selected'; ?>>Delivered</option>
            <option value="cancelled" <?php if ($status == 'cancelled') echo 'selected'; ?>>Cancelled</option>
        </select><br><br>

        <button type="submit" name="update_order">Update Order</button>
    </form>

    <a href="manage_orders.php">Back to Manage Orders</a>
</body>
</html>