<?php
include 'db.php';
session_start();

// Admin login check
if (!isset($_SESSION['user_id']) || !$_SESSION['admin']) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sanitize inputs
    $name = mysqli_real_escape_string($conn, $name);
    $email = mysqli_real_escape_string($conn, $email);
    
    // Hash the password using bcrypt
    $hashed_password = password_hash($password, PASSWORD_DEFAULT); 

    $sql = "INSERT INTO users (name, email, password, isAdmin) VALUES ('$name', '$email', '$hashed_password', 1)";

    if ($conn->query($sql) === TRUE) {
        echo "<p>New admin created successfully!</p>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create New Admin</title>
</head>
<body>
    <h2>Create New Admin</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Create Admin</button>
    </form>

    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>