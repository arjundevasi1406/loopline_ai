<?php include('db.php'); ?>
<link rel="stylesheet" href="css/customer.css">

<div class="product-grid">
<?php
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='product'>
                <img src='{$row['product_image']}' alt='{$row['product_name']}'>
                <h3>{$row['product_name']}</h3>
                <form action='orders.php' method='POST'>
                    <input type='hidden' name='product_id' value='{$row['id']}'>
                    <button type='submit'>Buy Now</button>
                </form>
              </div>";
    }
} else {
    echo "No products available.";
}
?>
</div>