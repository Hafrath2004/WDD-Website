<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ironix";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Connection failed: ' . $conn->connect_error]));
}

header('Content-Type: application/json');

// Get product ID from POST request
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

if ($product_id > 0) {
    // Prepare a delete statement
    $stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        // Check if any rows were affected
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => true, 'message' => 'Item removed from cart.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Item not found in cart.']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Error removing item: ' . $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid product ID.']);
}

$conn->close();
?> 