<?php include('db.php'); ?>

<link rel="stylesheet" href="css/orders.css">

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productId = $_POST['product_id'];

    // Fetch the product image from the 'products' table
    $sql = "SELECT product_image FROM products WHERE id = '$productId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $productImage = $row['product_image'];
        
        // Insert the order into the 'orders' table with product_id and product_image
        $insertOrderSql = "INSERT INTO orders (product_id, seller_image_path) VALUES ('$productId', '$productImage')";
        
        if ($conn->query($insertOrderSql) === TRUE) {
            echo "Order placed successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Product not found.";
    }
}

// Fetch orders with associated product images
$sql = "SELECT orders.id, products.product_name, products.product_image 
        FROM orders 
        JOIN products ON orders.product_id = products.id";

$result = $conn->query($sql);

echo "<div class='order-grid'>";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='order'>
                <img src='{$row['product_image']}' alt='{$row['product_name']}'>
                <h3>{$row['product_name']}</h3>
                <form action='returns.php' method='GET'>
                    <input type='hidden' name='order_id' value='{$row['id']}'>
                    <button type='submit'>Initiate Return</button>
                </form>
              </div>";
    }
} else {
    echo "No orders found.";
}
echo "</div>";
?>
