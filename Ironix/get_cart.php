<?php
// get_cart.php

header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ironix";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database Connection failed: ' . $conn->connect_error]);
    exit();
}

$cart_items = [];

// Fetch all cart items from the database
$sql = "SELECT product_id, name, price, quantity FROM cart";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
    }
}

$response = [
    "cart" => $cart_items
];

echo json_encode($response);

$conn->close();
?> 