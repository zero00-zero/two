<?php
include 'db.php'; // Include your database connection file

session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeEase Bakery</title>
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
            <button class="nav-toggle">Menu</button> 
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h2>Welcome to BakeEase Bakery!</h2>
        <p>Indulge in the aroma of freshly baked goods and treat yourself to our delectable creations.</p>
        <a href="products.php" class="btn">Explore Our Products</a>
    </section>

    <!-- Product Gallery Section (Simplified for now) -->
    <section class="product-gallery">
        <h2>Our Featured Products</h2>
        <div class="products">
            <?php
            // Fetch only featured products
            $sql = "SELECT * FROM products WHERE featured = 1"; 
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='product-card'>";
                    echo "<img src='" . $row['image'] . "' alt='" . $row['name'] . "'>";
                    echo "<h3>" . $row['name'] . "</h3>";
                    echo "<p>" . $row['description'] . "</p>";
                    echo "<p>$" . $row['price'] . "</p>";
                    echo "<form method='post' action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "'>";
                    echo "<input type='hidden' name='product_id' value='" . $row['id'] . "'>";
                    echo "<input type='number' name='quantity' value='1' min='1'>";
                    echo "<button type='submit' name='add_to_cart' class='btn'>Add to Cart</button>";
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "<p>No products available.</p>";
            }
            ?>
        </div>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>© 2023 BakeEase Bakery. All rights reserved.</p>
    </footer>

    <script src="script.js"></script> 
</body>
</html>