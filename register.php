<?php
include 'db.php';

// Start the session at the beginning of the script
session_start(); 

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; // Initialize the cart if it doesn't exist
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if the email already exists in the database
    $check_email_sql = "SELECT * FROM users WHERE email = '$email'";
    $check_email_result = $conn->query($check_email_sql);

    if ($check_email_result->num_rows > 0) {
        // Email already exists
        echo "<p style='color: red;'>Error: This email address is already registered.</p>";
    } else {
        // Email is unique, proceed with registration
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

        if ($conn->query($sql) === TRUE) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeEase Bakery - Register</title>
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
            // Check if cart is set before using count()
            if (isset($_SESSION['cart'])) {
                echo "(" . count($_SESSION['cart']) . ")"; 
            } else {
                echo "(0)"; 
            }
            ?>
        </a></li>
    </ul>
</nav>
    </header>

    <!-- Registration Form -->
    <section class="registration">
        <h2>Register</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" class="btn">Register</button>
        </form>
    </section>

    <!-- Footer Section -->
    <footer>
        <p>© 2023 BakeEase Bakery. All rights reserved.</p>
    </footer>
</body>
</html>