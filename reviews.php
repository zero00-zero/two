<?php
include 'db.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get product_id from the URL (Moved to the top)
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
} else {
    header("Location: products.php"); // Redirect to products page if product_id is not set
    exit(); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $review = $_POST['review'];

    $sql = "INSERT INTO reviews (user_id, product_id, rating, review) VALUES ('$user_id', '$product_id', '$rating', '$review')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Review submitted successfully!');</script>"; // JavaScript alert for success
    } else {
        echo "<script>alert('Error submitting review. Please try again later.');</script>"; // JavaScript alert for error
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BakeEase Bakery - Reviews</title>
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

    <!-- Reviews Section -->
    <section class="reviews">
        <h2>Write a Review</h2> 
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>"> 
            <label for="rating">Rating (1-5):</label>
            <input type="number" id="rating" name="rating" min="1" max="5" required>
            <label for="review">Review:</label>
            <textarea id="review" name="review" required></textarea>
            <button type="submit" name="submit_review" class="btn">Submit Review</button>
        </form>

        <h2>Your Past Reviews for this Product</h2>

        <?php
        $sql = "SELECT * FROM reviews WHERE user_id='$user_id' AND product_id='$product_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='review'>"; 
                echo "<p><strong>Rating:</strong> " . $row['rating'] . "/5</p>";
                echo "<p>" . $row['review'] . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>You haven't submitted any reviews for this product yet.</p>";
        }
        ?>
        
    </section>

    <!-- Footer Section -->
    <footer>
        <p>© 2023 BakeEase Bakery. All rights reserved.</p>
    </footer>
</body>
</html>