<?php
include 'db.php';
session_start();

// Admin login check 
if (!isset($_SESSION['user_id']) || !$_SESSION['admin']) {
    header("Location: login.php");
    exit();
}

// Pagination Settings
$results_per_page = 10; // Number of results to display per page
$current_page = isset($_GET['page']) ? $_GET['page'] : 1; 
$start_from = ($current_page - 1) * $results_per_page;

// Search Functionality
$search_query = isset($_GET['search']) ? $_GET['search'] : "";

// Build the SQL query (including search and pagination)
$sql = "SELECT * FROM users 
        WHERE isAdmin = 0 
        AND (name LIKE '%$search_query%' OR email LIKE '%$search_query%')
        LIMIT $start_from, $results_per_page"; 
$result = $conn->query($sql);

// Get total number of users for pagination
$total_users_sql = "SELECT COUNT(*) FROM users WHERE isAdmin = 0";
$total_users_result = $conn->query($total_users_sql);
$total_users = $total_users_result->fetch_row()[0];
$total_pages = ceil($total_users / $results_per_page); 

?>

<!DOCTYPE html>
<html>
<head>
    <title>BakeEase Bakery - Manage Users</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Manage Users</h2>

    <!-- Search Form -->
    <form method="get" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" name="search" placeholder="Search by name or email" value="<?php echo $search_query; ?>">
        <button type="submit">Search</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Loyalty Points</th>
                <th>Actions</th> 
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['loyalty_points'] . "</td>";
                    echo "<td>";
                    echo "<a href='edit_user.php?id=" . $row['id'] . "'>Edit</a> | ";
                    echo "<a href='delete_user.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No users found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="pagination">
        <?php
        // Display pagination links 
        for ($i = 1; $i <= $total_pages; $i++) { 
            echo "<a href='?page=" . $i . "&search=" . $search_query . "' " . ($i == $current_page ? "class='active'" : "") . ">" . $i . "</a> "; 
        }
        ?>
    </div>

    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>
</html>