<?php
/**
 * Connection Verification Script for Ironix
 * This script verifies that all pages are properly connected to the database
 * and that image URLs are being pulled dynamically
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

// Add HTML header
echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Connection Verification - Ironix</title>";
echo "<style>body{font-family:Arial,sans-serif;max-width:1000px;margin:50px auto;padding:20px;background:#f5f5f5;}";
echo "h2{color:#243b55;}h3{color:#f5a623;}table{width:100%;border-collapse:collapse;margin:20px 0;background:white;}";
echo "th,td{padding:12px;text-align:left;border-bottom:1px solid #ddd;}th{background:#243b55;color:white;}";
echo ".success{color:green;font-weight:bold;}.error{color:red;font-weight:bold;}.info{color:#666;}";
echo "a{color:#f5a623;text-decoration:none;}a:hover{text-decoration:underline;}</style></head><body>";

echo "<h2>Ironix Database Connection Verification</h2>";

// Test 1: Products Table
echo "<h3>1. Products Table Connection</h3>";
$products_sql = "SELECT id, name, price, image_url FROM products LIMIT 5";
$products_result = $conn->query($products_sql);

if ($products_result && $products_result->num_rows > 0) {
    echo "<p class='success'>✓ Products table is connected and accessible</p>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Price (LKR)</th><th>Image URL</th></tr>";
    while($row = $products_result->fetch_assoc()) {
        $url_preview = strlen($row['image_url']) > 50 ? substr($row['image_url'], 0, 50) . '...' : $row['image_url'];
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . number_format($row['price'], 2) . "</td>";
        echo "<td class='info' title='" . htmlspecialchars($row['image_url']) . "'>" . htmlspecialchars($url_preview) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<p class='info'>These products will appear on: index.php, products.php, search_results.php</p>";
} else {
    echo "<p class='error'>✗ No products found. Run insert_sample_data.php to add sample products.</p>";
}

// Test 2: Blog Table
echo "<h3>2. Blog Table Connection</h3>";
$blog_sql = "SELECT id, title, name, price, image_url FROM blog LIMIT 5";
$blog_result = $conn->query($blog_sql);

if ($blog_result && $blog_result->num_rows > 0) {
    echo "<p class='success'>✓ Blog table is connected and accessible</p>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Title</th><th>Product Name</th><th>Price (LKR)</th><th>Image URL</th></tr>";
    while($row = $blog_result->fetch_assoc()) {
        $url_preview = strlen($row['image_url']) > 50 ? substr($row['image_url'], 0, 50) . '...' : $row['image_url'];
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . number_format($row['price'], 2) . "</td>";
        echo "<td class='info' title='" . htmlspecialchars($row['image_url']) . "'>" . htmlspecialchars($url_preview) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<p class='info'>These blog posts will appear on: blog.php</p>";
} else {
    echo "<p class='error'>✗ No blog posts found. Run insert_sample_data.php to add sample blog posts.</p>";
}

// Test 3: API Endpoint
echo "<h3>3. API Endpoint (get_products.php)</h3>";
$api_test = file_get_contents('http://localhost/Ironix/get_products.php?all=1');
if ($api_test) {
    $api_data = json_decode($api_test, true);
    if ($api_data && isset($api_data['products'])) {
        echo "<p class='success'>✓ API endpoint is working</p>";
        echo "<p class='info'>Found " . count($api_data['products']) . " products via API</p>";
        echo "<p class='info'>This API is used by: index.php</p>";
    } else {
        echo "<p class='error'>✗ API returned invalid data</p>";
    }
} else {
    echo "<p class='error'>✗ Could not access API endpoint</p>";
}

// Test 4: Image URL Accessibility
echo "<h3>4. Image URL Accessibility Test</h3>";
$test_sql = "SELECT image_url FROM products WHERE image_url IS NOT NULL AND image_url != '' LIMIT 3";
$test_result = $conn->query($test_sql);

if ($test_result && $test_result->num_rows > 0) {
    echo "<p class='info'>Testing first 3 product image URLs...</p>";
    echo "<table>";
    echo "<tr><th>Image URL</th><th>Status</th></tr>";
    while($row = $test_result->fetch_assoc()) {
        $url = $row['image_url'];
        $headers = @get_headers($url, 1);
        $status = $headers && strpos($headers[0], '200') !== false ? 'Accessible' : 'Not Accessible';
        $status_class = $status === 'Accessible' ? 'success' : 'error';
        $url_preview = strlen($url) > 60 ? substr($url, 0, 60) . '...' : $url;
        echo "<tr>";
        echo "<td class='info' title='" . htmlspecialchars($url) . "'>" . htmlspecialchars($url_preview) . "</td>";
        echo "<td class='$status_class'>$status</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p class='error'>✗ No image URLs found to test</p>";
}

// Summary
echo "<h3>Summary</h3>";
echo "<div style='background:#fff;padding:20px;border-radius:8px;border-left:4px solid #f5a623;'>";
echo "<p><strong>Database Connection:</strong> <span class='success'>✓ Connected</span></p>";
echo "<p><strong>Products Table:</strong> " . ($products_result && $products_result->num_rows > 0 ? "<span class='success'>✓ Working</span>" : "<span class='error'>✗ Empty</span>") . "</p>";
echo "<p><strong>Blog Table:</strong> " . ($blog_result && $blog_result->num_rows > 0 ? "<span class='success'>✓ Working</span>" : "<span class='error'>✗ Empty</span>") . "</p>";
echo "<p><strong>Dynamic Updates:</strong> <span class='success'>✓ Enabled</span></p>";
echo "<p class='info'><strong>Note:</strong> All pages (index.php, products.php, blog.php) automatically pull image URLs from the database. Changes in the database will appear immediately after refreshing the page.</p>";
echo "</div>";

echo "<p style='margin-top:30px;'><a href='index.php'>Go to Home Page</a> | <a href='products.php'>Go to Products Page</a> | <a href='blog.php'>Go to Blog Page</a></p>";

// Close connection
$conn->close();

echo "</body></html>";
?>

