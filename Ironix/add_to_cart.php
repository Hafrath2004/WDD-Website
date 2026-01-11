<?php
// add_to_cart.php

header('Content-Type: application/json'); // Ensure JSON header is sent first

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ironix";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection; return JSON error if connection fails.
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database Connection failed: ' . $conn->connect_error]);
    exit(); // Stop script execution
}

// Use a try-catch block for better error handling
try {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;
    $image_url = isset($_POST['image_url']) ? $_POST['image_url'] : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    // Validate required fields (simple check)
    if (!$product_id || $name === '' || !$price) {
        echo json_encode(['error' => 'Missing required product data.']);
        $conn->close();
        exit();
    }

    // Check if product already in cart
    $check = $conn->prepare("SELECT quantity FROM cart WHERE product_id = ?");
    if ($check === false) {
        throw new Exception('Prepare failed for check: ' . $conn->error);
    }
    $check->bind_param("i", $product_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        $check->bind_result($existing_qty);
        $check->fetch();
        $new_qty = $existing_qty + $quantity;
        $update = $conn->prepare("UPDATE cart SET quantity = ? WHERE product_id = ?");
         if ($update === false) {
            throw new Exception('Prepare failed for update: ' . $conn->error);
        }
        $update->bind_param("ii", $new_qty, $product_id);
        $update->execute();
        $update->close();
    } else {
        $sql = "INSERT INTO cart (product_id, name, price, quantity) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
         if ($stmt === false) {
            throw new Exception('Prepare failed for insert: ' . $conn->error);
        }
        // Use 's' for description and image_url if they are strings
        $stmt->bind_param("isdi", $product_id, $name, $price, $quantity);
        $stmt->execute();
        $stmt->close();
    }
    $check->close();

    // Fetch all cart items (optional, could be done on cart page)
    $cart_items = [];
    $result = $conn->query("SELECT product_id, name, price, quantity FROM cart");
     if ($result) {
        while ($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }
    }

    // Success response
    $response = [
        "success" => true,
        "message" => "Product added to cart.",
        "cart" => $cart_items // Include cart items if needed on the frontend
    ];
    echo json_encode($response);

} catch (Exception $e) {
    // Catch any exceptions and return a JSON error response
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    // Close the database connection in all cases
    if ($conn) {
        $conn->close();
    }
}

?>
