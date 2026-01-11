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

// Handle form submissions
$message = '';
$messageType = '';

// Add Supplier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $company_name = $conn->real_escape_string($_POST['company_name']);
    $contact_person = $conn->real_escape_string($_POST['contact_person']);
    
    $sql = "INSERT INTO suppliers (name, email, phone, address, company_name, contact_person) 
            VALUES ('$name', '$email', '$phone', '$address', '$company_name', '$contact_person')";
    
    if ($conn->query($sql) === TRUE) {
        $message = 'Supplier added successfully!';
        $messageType = 'success';
    } else {
        $message = 'Error: ' . $conn->error;
        $messageType = 'error';
    }
}

// Update Supplier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $id = intval($_POST['id']);
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    $company_name = $conn->real_escape_string($_POST['company_name']);
    $contact_person = $conn->real_escape_string($_POST['contact_person']);
    
    $sql = "UPDATE suppliers SET name='$name', email='$email', phone='$phone', address='$address', 
            company_name='$company_name', contact_person='$contact_person' WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        $message = 'Supplier updated successfully!';
        $messageType = 'success';
    } else {
        $message = 'Error: ' . $conn->error;
        $messageType = 'error';
    }
}

// Delete Supplier
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $sql = "DELETE FROM suppliers WHERE id=$id";
    
    if ($conn->query($sql) === TRUE) {
        $message = 'Supplier deleted successfully!';
        $messageType = 'success';
    } else {
        $message = 'Error: ' . $conn->error;
        $messageType = 'error';
    }
}

// Assign Supplier to Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'assign_supplier') {
    $product_id = intval($_POST['product_id']);
    $supplier_id = intval($_POST['supplier_id']);
    
    // Check if supplier_id column exists, if not add it
    $checkColumn = $conn->query("SHOW COLUMNS FROM products LIKE 'supplier_id'");
    if ($checkColumn->num_rows == 0) {
        $conn->query("ALTER TABLE products ADD COLUMN supplier_id INT NULL");
    }
    
    $sql = "UPDATE products SET supplier_id=$supplier_id WHERE id=$product_id";
    
    if ($conn->query($sql) === TRUE) {
        $message = 'Supplier assigned to product successfully!';
        $messageType = 'success';
    } else {
        $message = 'Error: ' . $conn->error;
        $messageType = 'error';
    }
}

// Fetch suppliers
$suppliers = $conn->query("SELECT * FROM suppliers ORDER BY id DESC");

// Fetch supplier for editing
$editSupplier = null;
if (isset($_GET['edit_id'])) {
    $editId = intval($_GET['edit_id']);
    $result = $conn->query("SELECT * FROM suppliers WHERE id=$editId");
    if ($result && $result->num_rows > 0) {
        $editSupplier = $result->fetch_assoc();
    }
}

// Fetch products with their suppliers
$products = $conn->query("SELECT p.*, s.name as supplier_name FROM products p LEFT JOIN suppliers s ON p.supplier_id = s.id ORDER BY p.id DESC");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Suppliers Management - Ironix Admin</title>
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
    .btn-danger {
      background: #e53935;
    }
    .btn-danger:hover {
      background: #c62828;
    }
    .btn-secondary {
      background: #6c757d;
    }
    .btn-secondary:hover {
      background: #5a6268;
    }
    .dashboard-table-container {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(36,59,85,0.07);
      padding: 24px 20px;
      margin-bottom: 24px;
    }
    .form-container {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(36,59,85,0.07);
      padding: 24px 20px;
      margin-bottom: 24px;
    }
    .form-group {
      margin-bottom: 18px;
    }
    .form-group label {
      display: block;
      margin-bottom: 6px;
      font-weight: 600;
      color: var(--main-blue);
    }
    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1em;
      font-family: 'Poppins', Arial, sans-serif;
      box-sizing: border-box;
    }
    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
      outline: none;
      border-color: var(--main-gold);
    }
    .form-row {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
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
    .action-buttons {
      display: flex;
      gap: 8px;
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
    }
    .modal-content {
      background: #fff;
      margin: 5% auto;
      padding: 24px;
      border-radius: 16px;
      width: 90%;
      max-width: 600px;
      max-height: 80vh;
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
    @media (max-width: 900px) {
      .form-row {
        grid-template-columns: 1fr;
      }
      .main-content {
        padding: 24px 16px;
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
        <li><a href="admin_suppliers.php" class="active"><i class="fa-solid fa-truck"></i> Suppliers</a></li>
        <li><a href="admin_orders.php"><i class="fa-solid fa-shopping-cart"></i> Orders</a></li>
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
        <div class="welcome">Suppliers Management <span style="font-size:1.2em;">🚚</span></div>
        <a href="index.php" class="btn"><i class="fa-solid fa-home"></i> Go to Website Home</a>
      </div>

      <?php if ($message): ?>
        <div class="message <?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
      <?php endif; ?>

      <!-- Add/Edit Supplier Form -->
      <div class="form-container">
        <h2><?= $editSupplier ? 'Edit Supplier' : 'Add New Supplier' ?></h2>
        <form method="POST" action="">
          <input type="hidden" name="action" value="<?= $editSupplier ? 'update' : 'add' ?>">
          <?php if ($editSupplier): ?>
            <input type="hidden" name="id" value="<?= $editSupplier['id'] ?>">
          <?php endif; ?>
          <div class="form-row">
            <div class="form-group">
              <label>Supplier Name *</label>
              <input type="text" name="name" value="<?= $editSupplier ? htmlspecialchars($editSupplier['name']) : '' ?>" required>
            </div>
            <div class="form-group">
              <label>Email *</label>
              <input type="email" name="email" value="<?= $editSupplier ? htmlspecialchars($editSupplier['email']) : '' ?>" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label>Phone</label>
              <input type="text" name="phone" value="<?= $editSupplier ? htmlspecialchars($editSupplier['phone']) : '' ?>">
            </div>
            <div class="form-group">
              <label>Company Name</label>
              <input type="text" name="company_name" value="<?= $editSupplier ? htmlspecialchars($editSupplier['company_name']) : '' ?>">
            </div>
          </div>
          <div class="form-group">
            <label>Contact Person</label>
            <input type="text" name="contact_person" value="<?= $editSupplier ? htmlspecialchars($editSupplier['contact_person']) : '' ?>">
          </div>
          <div class="form-group">
            <label>Address</label>
            <textarea name="address" rows="3"><?= $editSupplier ? htmlspecialchars($editSupplier['address']) : '' ?></textarea>
          </div>
          <button type="submit" class="btn"><?= $editSupplier ? 'Update Supplier' : 'Add Supplier' ?></button>
          <?php if ($editSupplier): ?>
            <a href="admin_suppliers.php" class="btn btn-secondary">Cancel</a>
          <?php endif; ?>
        </form>
      </div>

      <!-- Suppliers List -->
      <div class="dashboard-table-container">
        <h2>All Suppliers</h2>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Company</th>
              <th>Contact Person</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($suppliers && $suppliers->num_rows > 0): ?>
              <?php while($row = $suppliers->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($row['id']) ?></td>
                  <td><?= htmlspecialchars($row['name']) ?></td>
                  <td><?= htmlspecialchars($row['email']) ?></td>
                  <td><?= htmlspecialchars($row['phone']) ?></td>
                  <td><?= htmlspecialchars($row['company_name']) ?></td>
                  <td><?= htmlspecialchars($row['contact_person']) ?></td>
                  <td>
                    <div class="action-buttons">
                      <a href="?edit_id=<?= $row['id'] ?>" class="btn btn-secondary" style="padding: 6px 12px; font-size: 0.9em;">
                        <i class="fa-solid fa-edit"></i> Edit
                      </a>
                      <a href="?delete_id=<?= $row['id'] ?>" class="btn btn-danger" style="padding: 6px 12px; font-size: 0.9em;"
                         onclick="return confirm('Are you sure you want to delete this supplier?')">
                        <i class="fa-solid fa-trash"></i> Delete
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="7" style="text-align:center;">No suppliers found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Assign Suppliers to Products -->
      <div class="dashboard-table-container">
        <h2>Assign Suppliers to Products</h2>
        <table>
          <thead>
            <tr>
              <th>Product ID</th>
              <th>Product Name</th>
              <th>Current Supplier</th>
              <th>Assign Supplier</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($products && $products->num_rows > 0): ?>
              <?php while($product = $products->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($product['id']) ?></td>
                  <td><?= htmlspecialchars($product['name']) ?></td>
                  <td><?= $product['supplier_name'] ? htmlspecialchars($product['supplier_name']) : '<span style="color:#999;">Not assigned</span>' ?></td>
                  <td>
                    <form method="POST" action="" style="display:inline;">
                      <input type="hidden" name="action" value="assign_supplier">
                      <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                      <select name="supplier_id" style="padding: 6px 10px; border-radius: 6px; margin-right: 8px;">
                        <option value="">-- Select Supplier --</option>
                        <?php
                        $suppliersList = $conn->query("SELECT * FROM suppliers ORDER BY name");
                        if ($suppliersList && $suppliersList->num_rows > 0) {
                            $suppliersList->data_seek(0);
                            while($supplier = $suppliersList->fetch_assoc()):
                        ?>
                          <option value="<?= $supplier['id'] ?>" <?= $product['supplier_id'] == $supplier['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($supplier['name']) ?>
                          </option>
                        <?php
                            endwhile;
                        }
                        ?>
                      </select>
                      <button type="submit" class="btn" style="padding: 6px 12px; font-size: 0.9em;">
                        <i class="fa-solid fa-link"></i> Assign
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="4" style="text-align:center;">No products found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>

