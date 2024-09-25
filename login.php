<?php
include 'db.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query the users table
    $sql = "SELECT * FROM users WHERE email='$email'"; 
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];

            // Check if the user is an admin
            if ($row['isAdmin'] == 1) { 
                $_SESSION['admin'] = true; 
                header("Location: admin_dashboard.php"); 
            } else {
                $_SESSION['admin'] = false; 
                header("Location: index.php"); 
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found."; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeEase Bakery - Login</title>
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
        <li><a href="cart.php">Cart 
            <?php 
            // Check if the cart session variable is set 
            if (isset($_SESSION['cart'])) {
                echo "(" . count($_SESSION['cart']) . ")"; 
            } else {
                echo "(0)"; // Display 0 if cart is not set
            }
            ?>
        </a></li>
    </ul>
</nav>
    </header>

    <!-- Login Form -->
    <section class="login">
        <h2>Login</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" class="btn">Login</button>
        </form>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>© 2023 BakeEase Bakery. All rights reserved.</p>
    </footer>
</body>
</html>