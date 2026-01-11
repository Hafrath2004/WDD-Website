<?php
/**
 * Setup script for Suppliers and Orders tables
 * Run this file in your browser to create the necessary database tables
 */

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ironix";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

// Read SQL file
$sqlFile = __DIR__ . '/create_suppliers_orders_tables.sql';
if (!file_exists($sqlFile)) {
    die("SQL file not found: $sqlFile");
}

$sql = file_get_contents($sqlFile);

// Split SQL into individual statements
$statements = array_filter(array_map('trim', explode(';', $sql)));

$successCount = 0;
$errorCount = 0;
$errors = [];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Suppliers & Orders Tables</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h1 {
            color: #243b55;
            margin-bottom: 10px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }
        .success {
            color: #43a047;
            padding: 10px;
            background: #e8f5e9;
            border-radius: 6px;
            margin: 10px 0;
        }
        .error {
            color: #e53935;
            padding: 10px;
            background: #ffebee;
            border-radius: 6px;
            margin: 10px 0;
        }
        .summary {
            margin-top: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        .summary h2 {
            margin-top: 0;
            color: #243b55;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #f5a623;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
            font-weight: 600;
        }
        .btn:hover {
            background: #ffb300;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><i class="fas fa-database"></i> Setup Suppliers & Orders Tables</h1>
        <p class="subtitle">Creating database tables for supplier management and order tracking...</p>

        <?php
        foreach ($statements as $statement) {
            if (empty($statement)) continue;
            
            // Handle ALTER TABLE statements that might fail if column exists
            if (stripos($statement, 'ALTER TABLE') !== false && stripos($statement, 'ADD COLUMN IF NOT EXISTS') === false) {
                // Try to execute, but don't fail if column already exists
                if ($conn->query($statement) === TRUE) {
                    echo "<p class='success'><i class='fas fa-check-circle'></i> Successfully executed: " . htmlspecialchars(substr($statement, 0, 70)) . "...</p>";
                    $successCount++;
                } else {
                    // Check if error is about duplicate column
                    if (strpos($conn->error, 'Duplicate column') !== false || strpos($conn->error, 'already exists') !== false) {
                        echo "<p class='success'><i class='fas fa-info-circle'></i> Column already exists (skipped): " . htmlspecialchars(substr($statement, 0, 70)) . "...</p>";
                        $successCount++;
                    } else {
                        echo "<p class='error'><i class='fas fa-times-circle'></i> Error: " . htmlspecialchars($conn->error) . "</p>";
                        $errorCount++;
                        $errors[] = $conn->error;
                    }
                }
            } else {
                // Regular CREATE TABLE or other statements
                if ($conn->query($statement) === TRUE) {
                    echo "<p class='success'><i class='fas fa-check-circle'></i> Successfully executed: " . htmlspecialchars(substr($statement, 0, 70)) . "...</p>";
                    $successCount++;
                } else {
                    // Check if table already exists
                    if (strpos($conn->error, 'already exists') !== false) {
                        echo "<p class='success'><i class='fas fa-info-circle'></i> Table already exists (skipped): " . htmlspecialchars(substr($statement, 0, 70)) . "...</p>";
                        $successCount++;
                    } else {
                        echo "<p class='error'><i class='fas fa-times-circle'></i> Error: " . htmlspecialchars($conn->error) . "</p>";
                        $errorCount++;
                        $errors[] = $conn->error;
                    }
                }
            }
        }
        ?>

        <div class="summary">
            <h2>Setup Summary</h2>
            <p><strong>Total Statements:</strong> <?= count($statements) ?></p>
            <p><strong>Successful:</strong> <span style="color: #43a047;"><?= $successCount ?></span></p>
            <p><strong>Errors:</strong> <span style="color: #e53935;"><?= $errorCount ?></span></p>
            
            <?php if ($errorCount > 0): ?>
                <h3>Errors:</h3>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li style="color: #e53935;"><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

            <?php if ($errorCount == 0): ?>
                <p style="color: #43a047; font-weight: 600; margin-top: 20px;">
                    <i class="fas fa-check-circle"></i> Setup completed successfully!
                </p>
                <p>You can now use the Supplier Management and Order Tracking features in the admin panel.</p>
            <?php endif; ?>
        </div>

        <a href="admin_dashboard.php" class="btn">
            <i class="fas fa-arrow-left"></i> Go to Admin Dashboard
        </a>
    </div>
</body>
</html>

<?php
$conn->close();
?>

