
<?php
include 'db.php'; 
session_start();

// Check if the user is logged in AND is an admin
if (!isset($_SESSION['user_id']) || !$_SESSION['admin']) { 
    header("Location: login.php"); // Or redirect to index.php 
    exit();
}



// Get the product ID from the URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
} else {
    echo "Product ID not specified.";
    exit();
}

// Fetch the product details for editing
$sql = "SELECT * FROM products WHERE id='$product_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $product_name = $row['name'];
    $product_description = $row['description'];
    $product_price = $row['price'];
    $product_image = $row['image']; // Existing image path
} else {
    echo "Product not found.";
    exit();
}

// Handle form submission for updating the product
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_product'])) {
    $product_name = $_POST['product_name'];
    $product_description = $_POST['product_description'];
    $product_price = $_POST['product_price'];

    // Handle image upload (optional - only if a new image is selected)
    if ($_FILES['product_image']['name'] != "") { 
        $target_dir = "images/";
        $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if($check !== false) {
            // echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
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
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["product_image"]["name"])). " has been uploaded.";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        $product_image = $target_file; // Update image path if new image uploaded
    }

    // Sanitize inputs
    $product_name = mysqli_real_escape_string($conn, $product_name);
    $product_description = mysqli_real_escape_string($conn, $product_description);
    $product_price = mysqli_real_escape_string($conn, $product_price);
    // $product_image = mysqli_real_escape_string($conn, $product_image); // No need to sanitize if already handled during upload

    $sql = "UPDATE products 
            SET name='$product_name', description='$product_description', price='$product_price', image='$product_image' 
            WHERE id='$product_id'";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Product updated successfully!</p>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>BakeEase Bakery - Edit Product</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Edit Product</h2>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=$product_id"; ?>" enctype="multipart/form-data">
        <label for="product_name">Name:</label>
        <input type="text" id="product_name" name="product_name" value="<?php echo $product_name; ?>" required><br><br>

        <label for="product_description">Description:</label>
        <textarea id="product_description" name="product_description" required><?php echo $product_description; ?></textarea><br><br>

        <label for="product_price">Price:</label>
        <input type="number" id="product_price" name="product_price" value="<?php echo $product_price; ?>" step="0.01" required><br><br>

        <label for="product_image">Current Image:</label>
        <img src="<?php echo $product_image; ?>" alt="<?php echo $product_name; ?>" width="100"><br><br>

        <label for="product_image">New Image (Optional):</label>
        <input type="file" id="product_image" name="product_image" accept="image/*"><br><br> 

        <button type="submit" name="update_product">Update Product</button>
    </form>

    <a href="manage_products.php">Back to Manage Products</a>
</body>
</html>