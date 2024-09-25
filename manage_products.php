
<?php
include 'db.php'; 
session_start();

// Check if the user is logged in AND is an admin
if (!isset($_SESSION['user_id']) || !$_SESSION['admin']) { 
    header("Location: login.php"); // Or redirect to index.php 
    exit();
}


// Handle product creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];

    // Image Upload Handling (You need to implement this part)
    // Example (assuming images are stored in an 'images' subfolder):
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["product_image"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($_FILES["product_image"]["name"])) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    $product_image_path = $target_file;

    // Sanitize inputs
    $product_name = mysqli_real_escape_string($conn, $product_name);
    $product_description = mysqli_real_escape_string($conn, $product_description);
    $product_price = mysqli_real_escape_string($conn, $product_price);

    // Insert the new product into the database
    $sql = "INSERT INTO products (name, description, price, image) 
            VALUES ('$product_name', '$product_description', '$product_price', '$product_image_path')";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Product added successfully!</p>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle featured products updates
// Handle product creation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
  // ... (your product creation logic - same as before) ...
}

// Handle featured products updates
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_featured'])) {
  // Check if any featured products are selected
  if (isset($_POST['featured']) && count($_POST['featured']) > 0) {
      foreach ($_POST['featured'] as $product_id => $is_featured) {
          $is_featured = ($is_featured == '1') ? 1 : 0;

          // Sanitize input
          $product_id = mysqli_real_escape_string($conn, $product_id);

          $sql = "UPDATE products SET featured = '$is_featured' WHERE id = '$product_id'";
          if ($conn->query($sql) === TRUE) {
              // Display success message only once after all updates
              echo "<p>Featured products updated successfully.</p>"; 
          } else {
              echo "Error: " . $sql . "<br>" . $conn->error;
          }
      }
  } else {
      // Display error message if no products are selected
      echo "<p style='color: red;'>Error: Please select at least one product to feature.</p>"; 
  }
}

// Display the list of products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>
    <title>BakeEase Bakery - Manage Products</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h2>Manage Products</h2>

    <!-- Add Product Form -->
    <h3>Add New Product</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
        <label for="product_name">Name:</label>
        <input type="text" id="product_name" name="product_name" required><br><br>

        <label for="product_description">Description:</label>
        <textarea id="product_description" name="product_description" required></textarea><br><br>

        <label for="product_price">Price:</label>
        <input type="number" id="product_price" name="product_price" step="0.01" required><br><br>

        <label for="product_image">Image:</label>
        <input type="file" id="product_image" name="product_image" accept="image/*" required><br><br>

        <button type="submit" name="add_product">Add Product</button>
    </form>

    <!-- Product List -->
    <h3>Existing Products</h3>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Image</th>
                    <th>Featured</th>
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
                        echo "<td>" . $row['description'] . "</td>";
                        echo "<td>" . $row['price'] . "</td>";
                        echo "<td>" . $row['image'] . "</td>";
                        echo "<td><input type='checkbox' name='featured[" . $row['id'] . "]' value='1' " . ($row['featured'] ? 'checked' : '') . "></td>";
                        echo "<td>";
                        echo "<a href='edit_product.php?id=" . $row['id'] . "'>Edit</a> | ";
                        echo "<a href='delete_product.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this product?\")'>Delete</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No products found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <button type="submit" name="update_featured">Update Featured Products</button>
    </form>

    <a href="admin_dashboard.php">Back to Dashboard</a>
</body>

</html>