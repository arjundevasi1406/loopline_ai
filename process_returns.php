<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orderId = $_POST['orderId'];


    $returnImage = $_FILES['returnImage'];

    $sql = "SELECT seller_image_path FROM orders WHERE id = '$orderId'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $sellerImagePath = $row['seller_image_path'];

        
        $sellerImageTarget = 'AI/images/image1.jfif'; 
        $returnImageTarget = 'AI/images/image2.jfif'; 

        // Copy the seller's image to the AI/images folder as image1.jfif
        if (!copy($sellerImagePath, $sellerImageTarget)) {
            echo "Error: Could not copy seller's image to $sellerImageTarget.";
            exit();
        }

        // Move the uploaded return image to AI/images folder as image2.jfif
        if (move_uploaded_file($returnImage['tmp_name'], $returnImageTarget)) {
            
            
            $updateSql = "UPDATE orders SET return_image_path = '$returnImageTarget' WHERE id = '$orderId'";
            if ($conn->query($updateSql) === TRUE) {
                echo "Return image uploaded and order updated successfully.";
                header("Location: returns.php?result=Return image processed successfully!");


             
                if ($stmt->execute()) {
                    // Execute the Python script
                    $pythonScript = escapeshellcmd("python3 AI/model.py");
                    $output = shell_exec($pythonScript);
    
                    // Save the result in the database
                    $stmt = $conn->prepare("UPDATE orders SET result = ? WHERE id = ?");
                    $stmt->bind_param("si", $output, $orderId);
                    $stmt->execute();
    
                    // Redirect to return.php with the result
                    header("Location: return.php?orderId=$orderId&result=" . urlencode($output));    
                exit();
            } else {
                echo "Error updating order: " . $conn->error;
            }
        } else {
            echo "Error uploading the return image.";
        }
    } else {
        echo "Order not found.";
    }
} else {
    echo "Invalid request method.";
}
}


?>