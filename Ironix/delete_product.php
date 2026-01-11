<?php
// delete_product.php (Colorful Page)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ironix";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("<div style='color:#e53935; font-weight:bold;'>Database connection failed.</div>");
}
function style() {
    echo '<style>
    body { font-family: Poppins, Arial, sans-serif; background: #f8f9fa; color: #243b55; margin:0; }
    .container { max-width: 520px; margin: 48px auto; background: #fff; border-radius: 18px; box-shadow: 0 4px 16px rgba(36,59,85,0.10); padding: 36px 32px; }
    h2 { color: #f5a623; margin-bottom: 18px; }
    .input-group { margin-bottom: 18px; }
    label { font-weight: 600; color: #243b55; }
    input[type=number] { padding: 10px 14px; border-radius: 8px; border: 1px solid #ccc; width: 100%; font-size: 1.1em; }
    button { background: #f5a623; color: #fff; border: none; border-radius: 8px; padding: 12px 28px; font-size: 1.1em; font-weight: 600; cursor: pointer; margin-top: 10px; }
    button:hover { background: #ffb300; }
    .product-card { background: #f0f4f8; border-radius: 14px; padding: 24px; margin-bottom: 18px; box-shadow: 0 2px 8px rgba(36,59,85,0.07); }
    .product-card img { max-width: 100%; max-height: 120px; border-radius: 8px; margin-bottom: 10px; }
    .product-card .title { font-size: 1.3em; font-weight: 700; color: #243b55; margin-bottom: 6px; }
    .product-card .desc { color: #555; margin-bottom: 8px; }
    .product-card .price { color: #f5a623; font-size: 1.1em; font-weight: 600; }
    .msg { padding: 12px 18px; border-radius: 8px; margin-bottom: 18px; color: #fff; font-weight: 600; }
    .msg.success { background: #43a047; }
    .msg.error { background: #e53935; }
    </style>';
}
style();
echo '<div class="container">';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'], $_POST['id'])) {
    // Confirmed deletion
    $id = $_POST['id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo "<div class='msg success'>Product deleted successfully.</div>";
    } else {
        echo "<div class='msg error'>Product not found or could not be deleted.</div>";
    }
    $stmt->close();
    echo '<a href="admin_dashboard.php" style="color:#f5a623; text-decoration:underline; font-weight:600;">Back to Dashboard</a>';
    echo '</div>';
    $conn->close();
    exit();
}
if (isset($_POST['id'])) {
    // Show product details for confirmation
    $id = $_POST['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo '<div class="product-card">';
        if (!empty($row['image_url'])) {
            echo '<img src="'.htmlspecialchars($row['image_url']).'" alt="Product Image">';
        }
        echo '<div class="title">'.htmlspecialchars($row['name']).'</div>';
        echo '<div class="desc">'.htmlspecialchars($row['description']).'</div>';
        echo '<div class="price">$'.number_format($row['price'],2).'</div>';
        echo '<div style="color:#888; font-size:0.98em; margin-top:6px;">Product ID: '.htmlspecialchars($row['id']).'</div>';
        echo '</div>';
        echo '<form method="POST" onsubmit="return confirm(\'Are you sure you want to delete this product?\');">';
        echo '<input type="hidden" name="id" value="'.htmlspecialchars($row['id']).'">';
        echo '<button type="submit" name="confirm_delete"><i class="fa-solid fa-trash"></i> Confirm Delete</button>';
        echo '</form>';
    } else {
        echo "<div class='msg error'>Product not found.</div>";
    }
    $stmt->close();
    echo '<a href="delete_product.php" style="color:#f5a623; text-decoration:underline; font-weight:600;">Delete another product</a>';
    echo ' | <a href="admin_dashboard.php" style="color:#243b55; text-decoration:underline; font-weight:600;">Back to Dashboard</a>';
    echo '</div>';
    $conn->close();
    exit();
}
// Show ID entry form
?>
<h2>Delete Product</h2>
<form method="POST">
  <div class="input-group">
    <label for="id">Enter Product ID to Delete</label><br>
    <input type="number" name="id" id="id" required>
  </div>
  <button type="submit"><i class="fa-solid fa-search"></i> Find Product</button>
</form>
<a href="admin_dashboard.php" style="color:#243b55; text-decoration:underline; font-weight:600;">Back to Dashboard</a>
</div>
<?php $conn->close(); ?> 