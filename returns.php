<?php
include 'db.php';
$orderId = isset($_GET['order_id'])?
intval($_GET['order_id']):0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returns</title>
    <link rel="stylesheet" href="css/returns.css">
</head>
<body>
    <div class="container">
        <h1>Initiate Return</h1>
        <form action="process_returns.php" method="POST" enctype="multipart/form-data">
            <label for="return-image">Upload Image of the Product:</label>
            <input type="file" id="return-image" name="returnImage" accept="image/*" required>
            <input type="hidden" name="orderId" value="<?php echo $orderId; ?>">
            <button type="submit">Submit</button>
        </form>
        <?php if (isset($_GET['result'])): ?>
            <div class="result">
                Result: <?php echo htmlspecialchars($_GET['result']); ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="container">
        <h1>Run AI Model</h1>
        <form method="POST">
            <button type="submit" name="runModel">Run AI Model</button>
        </form>

        <div class="result">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['runModel'])) {
                // Execute the Python script
                $pythonScript = escapeshellcmd("python AI/model.py");
                $output = shell_exec($pythonScript);

                // Display the result
                if ($output) {
                    echo "<h2>Result:</h2>";
                    echo "<pre>" . htmlspecialchars($output) . "</pre>";
                } else {
                    echo "<p>Error: Unable to execute the model.</p>";
                }
            }
            ?>
        </div>
    </div>

</body>
</html>
        </div>
    </div>

</body>
</html>
