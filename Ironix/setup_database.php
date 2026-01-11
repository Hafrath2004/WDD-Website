<?php
/**
 * Database Setup Script for Ironix
 * This script creates all necessary tables for the Ironix application
 * 
 * Usage: Navigate to http://localhost/Ironix/setup_database.php in your browser
 * or run: php setup_database.php from command line
 */

// Database configuration
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

// Add HTML header for browser viewing
if (php_sapi_name() !== 'cli') {
    echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Database Setup - Ironix</title>";
    echo "<style>body{font-family:Arial,sans-serif;max-width:800px;margin:50px auto;padding:20px;background:#f5f5f5;}";
    echo "h2{color:#243b55;}p{margin:8px 0;}a{color:#f5a623;text-decoration:none;font-weight:bold;}";
    echo "a:hover{text-decoration:underline;}ul{padding-left:20px;}</style></head><body>";
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
if ($conn->query($sql) === TRUE) {
    echo "Database '$dbname' created or already exists.<br>";
} else {
    die("Error creating database: " . $conn->error);
}

// Select the database
$conn->select_db($dbname);

// Read and execute SQL file
$sqlFile = __DIR__ . '/create_tables.sql';
if (!file_exists($sqlFile)) {
    die("SQL file not found: $sqlFile");
}

$sql = file_get_contents($sqlFile);

// Remove comments and split into statements
$sql = preg_replace('/--.*$/m', '', $sql); // Remove single-line comments
$sql = preg_replace('/\/\*.*?\*\//s', '', $sql); // Remove multi-line comments

// Split by semicolon and filter empty statements
$statements = array_filter(
    array_map('trim', explode(';', $sql)),
    function($statement) {
        $statement = trim($statement);
        return !empty($statement) && strlen($statement) > 5;
    }
);

$successCount = 0;
$errorCount = 0;
$errors = [];

echo "<h2>Creating Tables...</h2>";

foreach ($statements as $statement) {
    $statement = trim($statement);
    
    // Skip empty statements
    if (empty($statement)) {
        continue;
    }
    
    // Execute statement
    if ($conn->query($statement) === TRUE) {
        $successCount++;
        // Extract table name for feedback
        if (preg_match('/CREATE TABLE\s+(?:IF NOT EXISTS\s+)?`?(\w+)`?/i', $statement, $matches)) {
            echo "<p style='color: green;'>✓ Table '{$matches[1]}' created successfully</p>";
        } elseif (preg_match('/DROP TABLE/i', $statement)) {
            echo "<p style='color: orange;'>✓ Dropped existing table</p>";
        } elseif (preg_match('/CREATE (?:DATABASE|INDEX)/i', $statement)) {
            echo "<p style='color: green;'>✓ Statement executed successfully</p>";
        }
    } else {
        $errorCount++;
        $errorMsg = $conn->error;
        $errors[] = $errorMsg;
        echo "<p style='color: red;'>✗ Error: " . htmlspecialchars($errorMsg) . "</p>";
    }
}

// Close connection
$conn->close();

// Display results
echo "<h2>Setup Complete!</h2>";
echo "<p style='color: green;'>Successfully executed: $successCount statements</p>";
if ($errorCount > 0) {
    echo "<p style='color: red;'>Errors: $errorCount</p>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
} else {
    echo "<p style='color: green; font-weight: bold;'>All tables created successfully!</p>";
    echo "<p>You can now:</p>";
    echo "<ul>";
    echo "<li>Add products through the admin panel</li>";
    echo "<li>Create blog posts</li>";
    echo "<li>Register users</li>";
    echo "</ul>";
    echo "<p><a href='index.php'>Go to Home Page</a> | <a href='admin_login.html'>Go to Admin Panel</a></p>";
}

// Close HTML for browser viewing
if (php_sapi_name() !== 'cli') {
    echo "</body></html>";
}
?>

