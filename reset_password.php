<?php
include 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $sql = "UPDATE users SET password='$password', reset_token=NULL WHERE reset_token='$token'";
        if ($conn->query($sql) === TRUE) {
            echo "Password reset successfully!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
} else {
    echo "Invalid token.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeEase Bakery - Reset Password</title>
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
</nav>
    </header>

    <!-- Reset Password Section -->
    <section class="reset-password">
        <h2>Reset Password</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?token=$token"; ?>">
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" name="reset_password" class="btn">Reset Password</button>
        </form>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2023 BakeEase Bakery. All rights reserved.</p>
    </footer>
</body>
</html>