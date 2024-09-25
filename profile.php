<?php
include 'db.php';

session_start();

if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $sql = "UPDATE users SET name='$name', email='$email', password='$password' WHERE id='$user_id'";

  if ($conn->query($sql) === TRUE) {
    echo "Profile updated successfully!";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);

// Check if user is found
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
} else {
  echo "Error: User not found.";
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BakeEase Bakery - Profile</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    .profile-section {
      margin-bottom: 20px;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .profile-section h3 {
      margin-top: 0;
    }

    .profile-section form {
      display: flex;
      flex-direction: column;
    }

    .profile-section label {
      margin-bottom: 5px;
    }

    .profile-section input,
    .profile-section textarea {
      margin-bottom: 10px;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    .profile-section button {
      padding: 10px;
      background-color: #333;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .profile-section button:hover {
      background-color: #555;
    }
  </style>
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
        <li><a href="cart.php">Cart (<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>)</a></li>
      </ul>
    </nav>
  </header>

  <!-- Profile Section -->
  <section class="profile">
    <h2>User Profile</h2>
    <div class="profile-section">
      <h3>Personal Information</h3>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit" name="update_profile" class="btn">Update Profile</button>
      </form>
    </div>

    <div class="profile-section">
      <h3>Order History</h3>
      <table>
        <tr>
          <th>Order ID</th>
          <th>Product ID</th>
          <th>Quantity</th>
          <th>Total Price</th>
          <th>Payment Method</th>
          <th>Address</th>
          <th>Status</th>
        </tr>
        <?php
        $sql = "SELECT * FROM orders WHERE user_id='$user_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
          // Use a different variable name (e.g., $orderRow)
          while ($orderRow = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $orderRow['id'] . "</td>";
            echo "<td>" . $orderRow['product_id'] . "</td>";
            echo "<td>" . $orderRow['quantity'] . "</td>";
            echo "<td>$" . $orderRow['total_price'] . "</td>";
            echo "<td>" . $orderRow['payment_method'] . "</td>";
            echo "<td>" . $orderRow['address'] . "</td>";
            echo "<td>" . $orderRow['status'] . "</td>";
            echo "</tr>";
          }
        } else {
          echo "<tr><td colspan='7'>No orders found.</td></tr>";
        }
        ?>
      </table>
    </div>

    <div class="profile-section">
      <h3>Loyalty Points</h3>
      <p>Your current loyalty points:
        <?php
        if (isset($row['loyalty_points'])) {
          echo $row['loyalty_points'];
        } else {
          echo "0"; // Default value if loyalty_points is not set
        }
        ?>
      </p>
    </div>
    <div class="profile-section">
      <h3>Account Settings</h3>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required>
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <button type="submit" name="change_password" class="btn">Change Password</button>
      </form>
    </div>
  </section>

  <!-- Footer Section -->
  <footer>
    <p>&copy; 2023 BakeEase Bakery. All rights reserved.</p>
  </footer>
</body>

</html>