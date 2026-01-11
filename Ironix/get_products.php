<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: no-cache, must-revalidate');

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);

$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "ironix";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error, 'products' => []]);
    exit;
}

// Set charset to utf8
$conn->set_charset("utf8");

$offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 12;

if (isset($_GET['all']) && $_GET['all'] == '1') {
    $sql = "SELECT id, name, description, price, image_url FROM products ORDER BY id ASC";
} else {
    $sql = "SELECT id, name, description, price, image_url FROM products ORDER BY id ASC LIMIT $limit OFFSET $offset";
}

$result = $conn->query($sql);
$products = [];

if ($result === false) {
    echo json_encode(['error' => 'Query failed: ' . $conn->error, 'products' => []]);
    $conn->close();
    exit;
}

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        // Ensure all required fields exist and have valid values
        if (isset($row['id']) && isset($row['name']) && isset($row['price'])) {
            // Clean and validate data
            $row['id'] = intval($row['id']);
            $row['name'] = trim($row['name']);
            $row['description'] = isset($row['description']) ? trim($row['description']) : '';
            $row['price'] = floatval($row['price']);
            $row['image_url'] = isset($row['image_url']) ? trim($row['image_url']) : '';
            
            // Only add if essential fields are not empty
            if ($row['id'] > 0 && !empty($row['name']) && $row['price'] > 0) {
                $products[] = $row;
            }
        }
    }
}

$conn->close();

// Debug output (remove in production)
if (isset($_GET['debug']) && $_GET['debug'] == '1') {
    header('Content-Type: text/html');
    echo '<pre>';
    echo 'Products found: ' . count($products) . "\n\n";
    print_r($products);
    echo '</pre>';
    exit;
}

// Ensure proper JSON encoding
$output = ['products' => $products, 'count' => count($products)];
$json = json_encode($output, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

if ($json === false) {
    echo json_encode(['error' => 'JSON encoding failed: ' . json_last_error_msg(), 'products' => []]);
} else {
    echo $json;
} 