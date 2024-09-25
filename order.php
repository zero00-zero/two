<?php
include 'db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    $user_id = $_SESSION['user_id'];
    $payment_method = $_POST['payment']; 
    $address = $_POST['address'];

    $total_price = 0;
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $sql = "SELECT price FROM products WHERE id='$product_id'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $subtotal = $row['price'] * $quantity;
        $total_price += $subtotal;

        $sql = "INSERT INTO orders (user_id, product_id, quantity, total_price, payment_method, address) 
                VALUES ('$user_id', '$product_id', '$quantity', '$subtotal', '$payment_method', '$address')";
        $conn->query($sql);
    }

    // Update loyalty points (if you have a loyalty program)
    // ... your code to update loyalty points ... 

    // Clear the cart
    $_SESSION['cart'] = [];

    // Get the last inserted order ID
    $order_id = $conn->insert_id;

    // Redirect to order_confirmation.php with the order ID
    header("Location: order_confirmation.php?order_id=$order_id"); 
    exit();
}

// Fetch cart items (assuming you're storing cart data in the session)
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeEase Bakery - Order</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="logo">
            <h1>BakeEase Bakery</h1>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="loyalty.php">Loyalty Program</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['user_id'])) { ?>
                    <li><a href="profile.php">Profile</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php } else { ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php } ?>
                <li><a href="cart.php">Cart (<?php echo count($cart_items); ?>)</a></li> 
            </ul>
        </nav>
    </header>

    <!-- Order Section -->
    <section class="order">
        <h2>Place Your Order</h2>

        <h3>Order Summary</h3>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_price = 0; // Initialize total price
                foreach ($cart_items as $product_id => $quantity) {
                    // Fetch product details from the database
                    $sql = "SELECT * FROM products WHERE id = '$product_id'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $subtotal = $row['price'] * $quantity;
                        $total_price += $subtotal; // Add to the total price

                        echo "<tr>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $quantity . "</td>";
                        echo "<td>$" . $row['price'] . "</td>";
                        echo "<td>$" . $subtotal . "</td>";
                        echo "</tr>";
                    }
                } 
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Total:</td>
                    <td>$<?php echo number_format($total_price, 2); ?></td>
                </tr>
            </tfoot>
        </table>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="address">Delivery Address:</label>
            <textarea id="address" name="address" required></textarea><br><br>

            <label for="payment">Payment Method:</label>
            <select id="payment" name="payment" required>
                <option value="cod">Cash on Delivery</option>
                <option value="credit_card">Credit Card</option>
                <option value="paypal">PayPal</option>
            </select><br><br>

            <button type="submit" name="place_order" class="btn">Place Order</button>
        </form>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>© 2023 BakeEase Bakery. All rights reserved.</p>
    </footer>
</body>
</html>