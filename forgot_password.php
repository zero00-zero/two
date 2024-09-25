<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reset_password'])) {
    $email = $_POST['email'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $sql = "UPDATE users SET reset_token='$token' WHERE email='$email'";
        $conn->query($sql);

        // Send password reset email
        $subject = "Password Reset";
        $headers = "From: info@bakeeasebakery.com\r\n";
        $headers .= "Reply-To: info@bakeeasebakery.com\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $reset_link = "http://yourdomain.com/reset_password.php?token=$token";
        $email_message = "Click the link below to reset your password:<br><a href='$reset_link'>$reset_link</a>";

        if (mail($email, $subject, $email_message, $headers)) {
            echo "A password reset link has been sent to your email address.";
        } else {
            echo "Failed to send the password reset email.";
        }
    } else {
        echo "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeEase Bakery - Forgot Password</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header Section -->
    <header>
    <nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="products.php">Products</a></li>
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

    <!-- Forgot Password Section -->
    <section class="forgot-password">
        <h2>Forgot Password</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit" name="reset_password" class="btn">Reset Password</button>
        </form>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 