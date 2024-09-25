<?php
include 'db.php';

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_cart'])) {
    foreach ($_POST['quantity'] as $product_id => $quantity) {
        if ($quantity > 0) {
            $_SESSION['cart'][$product_id] = $quantity;
        } else {
            unset($_SESSION['cart'][$product_id]);
        }
    }
}

$total_price = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeEase Bakery - Cart</title>
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
        <li><a href="gallery.php">Products</a></li>
        <li><a href="order.php">Order</a></li>
        <li><a href="loyalty.php">Loyalty Program</a></li>
        <li><a href="contact.php">Contact</a></li>
        <?php
        if (isset($_SESSION['user_id'])) {
            echo '<li><a href="profile.php">Profile</a></li>';
            echo '<li><a href="logout.php">Logout</a></li>';
        } else {
            echo '<li><a href="login.php">Login</a></li>';
            echo '<li><a href="register.php">Register</a></li>';
        }
        ?>
        <li><a href="cart.php">Cart (<?php echo count($_SESSION['cart']); ?>)</a></li>
    </ul>
</nav>
    </header>

    <!-- Cart Section -->
    <section class="cart">
        <h2>Your Cart</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <table>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
                <?php
                foreach ($_SESSION['cart'] as $product_id => $quantity) {
                    $sql = "SELECT * FROM products WHERE id='$product_id'";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $subtotal = $row['price'] * $quantity;
                    $total_price += $subtotal;

                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td><input type='number' name='quantity[$product_id]' value='$quantity' min='0'></td>";
                    echo "<td>$" . $row['price'] . "</td>";
                    echo "<td>$" . $subtotal . "</td>";
                    echo "</tr>";
                }
                ?>
                <tr>
                    <td colspan="3">Total</td>
                    <td>$<?php echo $total_price; ?></td>
                </tr>
            </table>
            <button type="submit" name="update_cart" class="btn">Update Cart</button>
            <a href="order.php" class="btn">Proceed to Checkout</a>
        </form>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2023 BakeEase Bakery. All rights reserved.</p>
    </footer>
</body>
</html>