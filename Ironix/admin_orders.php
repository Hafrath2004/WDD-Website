<?php
// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ironix";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Handle order status update
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $order_id = intval($_POST['order_id']);
    $status = $conn->real_escape_string($_POST['status']);
    
    $sql = "UPDATE orders SET status='$status' WHERE id=$order_id";
    
    if ($conn->query($sql) === TRUE) {
        $message = 'Order status updated successfully!';
        $messageType = 'success';
    } else {
        $message = 'Error: ' . $conn->error;
        $messageType = 'error';
    }
}

// Fetch all orders with customer information
$orders = $conn->query("
    SELECT o.*, u.name as customer_name, u.email as customer_email, u.phone as customer_phone
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    ORDER BY o.order_date DESC
");

// Get order details for modal view
$orderDetails = null;
if (isset($_GET['view_id'])) {
    $orderId = intval($_GET['view_id']);
    $orderResult = $conn->query("
        SELECT o.*, u.name as customer_name, u.email as customer_email, u.phone as customer_phone, u.address as customer_address
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        WHERE o.id = $orderId
    ");
    if ($orderResult && $orderResult->num_rows > 0) {
        $orderDetails = $orderResult->fetch_assoc();
        
        // Fetch order items
        $orderItems = $conn->query("
            SELECT oi.*, p.name as product_name, p.image_url
            FROM order_items oi
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = $orderId
        ");
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Orders Tracking - Ironix Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --main-blue: #243b55;
      --main-gold: #f5a623;
      --main-bg: #f8f9fa;
      --sidebar-bg: #232946;
      --sidebar-active: #f5a623;
      --sidebar-hover: #243b55;
    }
    body {
      margin: 0;
      font-family: 'Poppins', Arial, sans-serif;
      background: var(--main-bg);
      color: var(--main-blue);
    }
    .dashboard-container {
      display: flex;
      min-height: 100vh;
    }
    .sidebar {
      width: 230px;
      background: var(--sidebar-bg);
      color: #fff;
      display: flex;
      flex-direction: column;
      padding: 24px 0;
      min-height: 100vh;
      box-shadow: 2px 0 12px rgba(36,59,85,0.08);
    }
    .sidebar .brand {
      font-size: 1.5em;
      font-weight: 700;
      color: var(--main-gold);
      text-align: center;
      margin-bottom: 32px;
      letter-spacing: 2px;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
      margin: 0;
      flex: 1;
    }
    .sidebar ul li {
      margin-bottom: 8px;
    }
    .sidebar ul li a {
      display: flex;
      align-items: center;
      color: #fff;
      text-decoration: none;
      padding: 12px 32px;
      font-size: 1.05em;
      border-left: 4px solid transparent;
      transition: background 0.2s, border 0.2s;
    }
    .sidebar ul li a.active,
    .sidebar ul li a:hover {
      background: var(--sidebar-hover);
      border-left: 4px solid var(--sidebar-active);
      color: var(--main-gold);
    }
    .sidebar ul li a i {
      margin-right: 14px;
      font-size: 1.1em;
    }
    .sidebar .sidebar-footer {
      text-align: center;
      font-size: 0.95em;
      color: #b0b0b0;
      margin-top: 32px;
    }
    .main-content {
      flex: 1;
      padding: 36px 40px;
      background: var(--main-bg);
      min-width: 0;
    }
    .dashboard-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 32px;
    }
    .dashboard-header .welcome {
      font-size: 1.5em;
      font-weight: 600;
      color: var(--main-blue);
    }
    .btn {
      background: var(--main-gold);
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 10px 20px;
      font-size: 1em;
      font-weight: 500;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      transition: background 0.2s;
    }
    .btn:hover {
      background: #ffb300;
    }
    .btn-sm {
      padding: 6px 12px;
      font-size: 0.9em;
    }
    .btn-info {
      background: #17a2b8;
    }
    .btn-info:hover {
      background: #138496;
    }
    .action-buttons {
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
    }
    .dashboard-table-container {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(36,59,85,0.07);
      padding: 24px 20px;
      margin-bottom: 24px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 12px;
    }
    th, td {
      padding: 14px 12px;
      text-align: left;
    }
    th {
      background: #f0f4f8;
      color: var(--main-blue);
      font-weight: 600;
    }
    tr:nth-child(even) {
      background: #f8f9fa;
    }
    tr:hover {
      background: #fffbe6;
    }
    .status-badge {
      padding: 4px 12px;
      border-radius: 12px;
      font-size: 0.85em;
      font-weight: 600;
      display: inline-block;
    }
    .status-pending {
      background: #fff3cd;
      color: #856404;
    }
    .status-processing {
      background: #cfe2ff;
      color: #084298;
    }
    .status-shipped {
      background: #d1e7dd;
      color: #0f5132;
    }
    .status-delivered {
      background: #d4edda;
      color: #155724;
    }
    .status-cancelled {
      background: #f8d7da;
      color: #721c24;
    }
    .message {
      padding: 12px 18px;
      border-radius: 8px;
      margin-bottom: 20px;
      color: #fff;
    }
    .message.success {
      background: #43a047;
    }
    .message.error {
      background: #e53935;
    }
    .modal {
      display: none;
      position: fixed;
      z-index: 1000;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.5);
      overflow-y: auto;
    }
    .modal-content {
      background: #fff;
      margin: 3% auto;
      padding: 24px;
      border-radius: 16px;
      width: 90%;
      max-width: 900px;
      max-height: 90vh;
      overflow-y: auto;
    }
    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }
    .close:hover {
      color: #000;
    }
    .order-details-section {
      margin-bottom: 24px;
      padding: 16px;
      background: #f8f9fa;
      border-radius: 8px;
    }
    .order-details-section h3 {
      margin-top: 0;
      color: var(--main-blue);
    }
    .order-items-table {
      margin-top: 16px;
    }
    .order-items-table img {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 6px;
    }
    .form-group {
      margin-bottom: 12px;
    }
    .form-group label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
    }
    .form-group select {
      width: 100%;
      padding: 8px 12px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 1em;
    }
    @media (max-width: 900px) {
      .main-content {
        padding: 24px 16px;
      }
      table {
        font-size: 0.9em;
      }
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <nav class="sidebar">
      <div class="brand"><i class="fa-solid fa-screwdriver-wrench"></i> Ironix Admin</div>
      <ul>
        <li><a href="admin_dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
        <li><a href="admin_products.php"><i class="fa-solid fa-box"></i> Products</a></li>
        <li><a href="admin_customers.php"><i class="fa-solid fa-users"></i> Customers</a></li>
        <li><a href="admin_suppliers.php"><i class="fa-solid fa-truck"></i> Suppliers</a></li>
        <li><a href="admin_orders.php" class="active"><i class="fa-solid fa-shopping-cart"></i> Orders</a></li>
        <li><a href="admin_inventory.php"><i class="fa-solid fa-warehouse"></i> Inventory</a></li>
        <li><a href="admin_admins.php"><i class="fa-solid fa-user-gear"></i> Admins</a></li>
        <li><a href="admin_settings.php"><i class="fa-solid fa-gear"></i> Settings</a></li>
      </ul>
      <div class="sidebar-footer">
        &copy; 2025 Ironix Hardware Shop
      </div>
    </nav>
    <div class="main-content">
      <div class="dashboard-header">
        <div class="welcome">Orders Tracking <span style="font-size:1.2em;">📦</span></div>
        <a href="index.php" class="btn"><i class="fa-solid fa-home"></i> Go to Website Home</a>
      </div>

      <?php if ($message): ?>
        <div class="message <?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>

      <!-- Orders List -->
      <div class="dashboard-table-container">
        <h2>All Orders</h2>
        <table>
          <thead>
            <tr>
              <th>Order #</th>
              <th>Customer</th>
              <th>Order Date</th>
              <th>Total Amount</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($orders && $orders->num_rows > 0): ?>
              <?php while($order = $orders->fetch_assoc()): ?>
                <tr>
                  <td><strong><?= htmlspecialchars($order['order_number']) ?></strong></td>
                  <td>
                    <div><?= htmlspecialchars($order['customer_name']) ?></div>
                    <small style="color:#666;"><?= htmlspecialchars($order['customer_email']) ?></small>
                  </td>
                  <td><?= date('M d, Y H:i', strtotime($order['order_date'])) ?></td>
                  <td><strong>LKR <?= number_format($order['total_amount'], 2) ?></strong></td>
                  <td>
                    <span class="status-badge status-<?= strtolower($order['status']) ?>">
                      <?= htmlspecialchars(ucfirst($order['status'])) ?>
                    </span>
                  </td>
                  <td>
                    <div class="action-buttons">
                      <a href="?view_id=<?= $order['id'] ?>" class="btn btn-info btn-sm">
                        <i class="fa-solid fa-eye"></i> View Details
                      </a>
                      <button onclick="openStatusModal(<?= $order['id'] ?>, '<?= htmlspecialchars($order['status']) ?>')" 
                              class="btn btn-sm">
                        <i class="fa-solid fa-edit"></i> Update Status
                      </button>
                    </div>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="6" style="text-align:center;">No orders found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Order Details Modal -->
  <?php if ($orderDetails): ?>
    <div id="orderModal" class="modal" style="display:block;">
      <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Order Details - <?= htmlspecialchars($orderDetails['order_number']) ?></h2>
        
        <div class="order-details-section">
          <h3>Customer Information</h3>
          <p><strong>Name:</strong> <?= htmlspecialchars($orderDetails['customer_name']) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($orderDetails['customer_email']) ?></p>
          <p><strong>Phone:</strong> <?= htmlspecialchars($orderDetails['customer_phone']) ?></p>
          <p><strong>Shipping Address:</strong> <?= htmlspecialchars($orderDetails['shipping_address'] ?: $orderDetails['customer_address']) ?></p>
        </div>

        <div class="order-details-section">
          <h3>Order Information</h3>
          <p><strong>Order Date:</strong> <?= date('M d, Y H:i', strtotime($orderDetails['order_date'])) ?></p>
          <p><strong>Status:</strong> 
            <span class="status-badge status-<?= strtolower($orderDetails['status']) ?>">
              <?= htmlspecialchars(ucfirst($orderDetails['status'])) ?>
            </span>
          </p>
          <p><strong>Total Amount:</strong> <strong style="font-size:1.2em; color:var(--main-gold);">LKR <?= number_format($orderDetails['total_amount'], 2) ?></strong></p>
        </div>

        <div class="order-details-section">
          <h3>Order Items</h3>
          <table class="order-items-table">
            <thead>
              <tr>
                <th>Product</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($orderItems && $orderItems->num_rows > 0): ?>
                <?php while($item = $orderItems->fetch_assoc()): ?>
                  <tr>
                    <td>
                      <?php if ($item['image_url']): ?>
                        <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>">
                      <?php else: ?>
                        <div style="width:50px; height:50px; background:#ddd; border-radius:6px;"></div>
                      <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($item['product_name']) ?></td>
                    <td><?= htmlspecialchars($item['quantity']) ?></td>
                    <td>LKR <?= number_format($item['unit_price'], 2) ?></td>
                    <td><strong>LKR <?= number_format($item['subtotal'], 2) ?></strong></td>
                  </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr><td colspan="5" style="text-align:center;">No items found.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>

        <a href="admin_orders.php" class="btn">Close</a>
      </div>
    </div>
  <?php endif; ?>

  <!-- Update Status Modal -->
  <div id="statusModal" class="modal">
    <div class="modal-content" style="max-width: 500px;">
      <span class="close" onclick="closeStatusModal()">&times;</span>
      <h2>Update Order Status</h2>
      <form method="POST" action="">
        <input type="hidden" name="action" value="update_status">
        <input type="hidden" name="order_id" id="status_order_id">
        <div class="form-group">
          <label>Status</label>
          <select name="status" id="status_select" required>
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="shipped">Shipped</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
        <button type="submit" class="btn">Update Status</button>
        <button type="button" class="btn btn-secondary" onclick="closeStatusModal()" style="background:#6c757d;">Cancel</button>
      </form>
    </div>
  </div>

  <script>
    function closeModal() {
      window.location.href = 'admin_orders.php';
    }
    function openStatusModal(orderId, currentStatus) {
      document.getElementById('statusModal').style.display = 'block';
      document.getElementById('status_order_id').value = orderId;
      document.getElementById('status_select').value = currentStatus;
    }
    function closeStatusModal() {
      document.getElementById('statusModal').style.display = 'none';
    }
    window.onclick = function(event) {
      const modal = document.getElementById('statusModal');
      if (event.target == modal) {
        closeStatusModal();
      }
    }
  </script>
</body>
</html>

