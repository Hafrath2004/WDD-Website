<?php
/**
 * Insert Sample Data Script for Ironix
 * This script inserts 20 products and 5 blog posts with product details
 * 
 * Usage: Navigate to http://localhost/Ironix/insert_sample_data.php in your browser
 * or run: php insert_sample_data.php from command line
 */

// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ironix";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add HTML header for browser viewing
if (php_sapi_name() !== 'cli') {
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Insert Sample Data - Ironix</title>";
    echo "<style>body{font-family:Arial,sans-serif;max-width:900px;margin:50px auto;padding:20px;background:#f5f5f5;}";
    echo "h2{color:#243b55;}h3{color:#f5a623;}p{margin:8px 0;}table{width:100%;border-collapse:collapse;margin:20px 0;background:white;}";
    echo "th,td{padding:12px;text-align:left;border-bottom:1px solid #ddd;}th{background:#243b55;color:white;}";
    echo ".success{color:green;font-weight:bold;}.error{color:red;font-weight:bold;}";
    echo "a{color:#f5a623;text-decoration:none;font-weight:bold;}a:hover{text-decoration:underline;}</style></head><body>";
}

echo "<h2>Inserting Sample Data into Ironix Database</h2>";

// Read and execute SQL file
$sqlFile = __DIR__ . '/insert_sample_data.sql';
if (!file_exists($sqlFile)) {
    die("SQL file not found: $sqlFile");
}

$sql = file_get_contents($sqlFile);

// Remove comments and split into statements
$sql = preg_replace('/--.*$/m', '', $sql);
$sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

// Split by semicolon and filter empty statements
$statements = array_filter(
    array_map('trim', explode(';', $sql)),
    function($statement) {
        $statement = trim($statement);
        return !empty($statement) && strlen($statement) > 10;
    }
);

$successCount = 0;
$errorCount = 0;
$errors = [];
$productsInserted = 0;
$blogsInserted = 0;

echo "<h3>Processing INSERT statements...</h3>";

foreach ($statements as $statement) {
    $statement = trim($statement);
    
    if (empty($statement)) {
        continue;
    }
    
    // Execute statement
    if ($conn->query($statement) === TRUE) {
        $successCount++;
        
        // Count inserts by counting VALUES clauses
        if (stripos($statement, 'INSERT INTO products') !== false) {
            // Count how many rows were inserted (VALUES clause)
            preg_match_all('/VALUES\s*\(/i', $statement, $matches);
            $productsInserted += count($matches[0]);
        } elseif (stripos($statement, 'INSERT INTO blog') !== false) {
            preg_match_all('/VALUES\s*\(/i', $statement, $matches);
            $blogsInserted += count($matches[0]);
        }
        
        if (php_sapi_name() !== 'cli') {
            echo "<p class='success'>✓ Statement executed successfully</p>";
        } else {
            echo "✓ Statement executed successfully\n";
        }
    } else {
        $errorCount++;
        $errorMsg = $conn->error;
        $errors[] = $errorMsg;
        if (php_sapi_name() !== 'cli') {
            echo "<p class='error'>✗ Error: " . htmlspecialchars($errorMsg) . "</p>";
        } else {
            echo "✗ Error: " . $errorMsg . "\n";
        }
    }
}

// Display summary
echo "<h3>Summary</h3>";
if (php_sapi_name() !== 'cli') {
    echo "<table>";
    echo "<tr><th>Item</th><th>Count</th></tr>";
    echo "<tr><td>Products Inserted</td><td><strong>$productsInserted</strong></td></tr>";
    echo "<tr><td>Blog Posts Inserted</td><td><strong>$blogsInserted</strong></td></tr>";
    echo "<tr><td>Total Statements Executed</td><td><strong>$successCount</strong></td></tr>";
    if ($errorCount > 0) {
        echo "<tr><td>Errors</td><td class='error'><strong>$errorCount</strong></td></tr>";
    }
    echo "</table>";
} else {
    echo "Products Inserted: $productsInserted\n";
    echo "Blog Posts Inserted: $blogsInserted\n";
    echo "Total Statements Executed: $successCount\n";
}

if ($errorCount > 0) {
    echo "<h3>Errors Encountered:</h3>";
    if (php_sapi_name() !== 'cli') {
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li class='error'>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
    } else {
        foreach ($errors as $error) {
            echo "Error: $error\n";
        }
    }
} else {
    echo "<h3 class='success'>✓ All data inserted successfully!</h3>";
    echo "<p>You can now:</p>";
    echo "<ul>";
    echo "<li><a href='products.php'>View Products Page</a></li>";
    echo "<li><a href='blog.php'>View Blog Page</a></li>";
    echo "<li><a href='admin_products.php'>View Products in Admin Panel</a></li>";
    echo "<li><a href='index.php'>Go to Home Page</a></li>";
    echo "</ul>";
}

// Close connection
$conn->close();

// Close HTML for browser viewing
if (php_sapi_name() !== 'cli') {
    echo "</body></html>";
}
?>

