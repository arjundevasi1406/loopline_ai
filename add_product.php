<?php include('db.php'); ?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = $_POST['product_name'];
    $image = $_FILES['product_image'];

    if ($image['error'] == 0) {
        $imagePath = 'uploads/' . basename($image['name']);
        move_uploaded_file($image['tmp_name'], $imagePath);

        $sql = "INSERT INTO products (product_name, product_image) VALUES ('$productName', '$imagePath')";
        if ($conn->query($sql) === TRUE) {
            echo "Product added successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error uploading image.";
    }
}
?>
<link rel="stylesheet" href="css/index.css">

<form action="add_product.php" method="POST" enctype="multipart/form-data">
    <label>Product Name:</label>
    <input type="text" name="product_name" required>
    <label>Product Image:</label>
    <input type="file" name="product_image" required>
    <button type="submit">Add Product</button>
</form>