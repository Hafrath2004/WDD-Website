<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ironix";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Get or create a default user (for demo purposes)
$userId = 1; // Default user ID - in production, get from session
$user = $conn->query("SELECT * FROM users WHERE id = $userId")->fetch_assoc();

if (!$user) {
    // Create a default user if none exists
    $conn->query("INSERT INTO users (name, email, phone, address) VALUES ('Guest User', 'guest@example.com', '0000000000', 'Default Address')");
    $userId = $conn->insert_id;
    $user = $conn->query("SELECT * FROM users WHERE id = $userId")->fetch_assoc();
}

// Get cart items
$sql = "SELECT c.product_id, c.quantity, p.name, p.description, p.price, p.image_url 
        FROM cart c JOIN products p ON c.product_id = p.id";
$result = $conn->query($sql);

if (!$result || $result->num_rows == 0) {
    header("Location: cart.php?error=empty_cart");
    exit();
}

// Calculate total
$grandTotal = 0;
$cartItems = [];
while($row = $result->fetch_assoc()) {
    $total = $row['price'] * $row['quantity'];
    $grandTotal += $total;
    $cartItems[] = $row;
}

// Generate unique order number
$orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));

// Create order
$shippingAddress = $user['address'];
$status = 'pending';

$orderSql = "INSERT INTO orders (user_id, order_number, total_amount, status, shipping_address) 
             VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($orderSql);

if ($stmt === false) {
    // If prepare fails, check if tables exist
    $checkOrders = $conn->query("SHOW TABLES LIKE 'orders'");
    if ($checkOrders->num_rows == 0) {
        die("Error: Orders table does not exist. Please run setup_suppliers_orders.php first.");
    }
    die("Error preparing order statement: " . $conn->error);
}

$stmt->bind_param("isdss", $userId, $orderNumber, $grandTotal, $status, $shippingAddress);

if ($stmt->execute()) {
    $orderId = $conn->insert_id;
    
    // Create order items
    foreach($cartItems as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $itemSql = "INSERT INTO order_items (order_id, product_id, product_name, quantity, unit_price, subtotal) 
                    VALUES (?, ?, ?, ?, ?, ?)";
        $itemStmt = $conn->prepare($itemSql);
        
        if ($itemStmt === false) {
            // If prepare fails, log error and continue
            error_log("Error preparing order item statement: " . $conn->error);
            continue;
        }
        
        $itemStmt->bind_param("iisidd", $orderId, $item['product_id'], $item['name'], $item['quantity'], $item['price'], $subtotal);
        
        if (!$itemStmt->execute()) {
            error_log("Error executing order item: " . $itemStmt->error);
        }
        
        $itemStmt->close();
    }
    
    // Clear cart after successful order
    $conn->query("DELETE FROM cart");
    
    $stmt->close();
    $conn->close();
    
    // Redirect to success page with order ID
    header("Location: order_success.php?order_id=" . $orderId);
    exit();
} else {
    $error = $stmt->error;
    $stmt->close();
    $conn->close();
    header("Location: checkout.php?error=order_failed&msg=" . urlencode($error));
    exit();
}
?>

