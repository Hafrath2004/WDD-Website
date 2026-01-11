<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ironix";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($orderId == 0) {
    header("Location: index.php");
    exit();
}

// Get order details
$orderSql = "SELECT o.*, u.name as customer_name, u.email as customer_email, u.phone as customer_phone, u.address as customer_address
             FROM orders o
             LEFT JOIN users u ON o.user_id = u.id
             WHERE o.id = $orderId";
$orderResult = $conn->query($orderSql);

if (!$orderResult || $orderResult->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$order = $orderResult->fetch_assoc();

// Get order items
$itemsSql = "SELECT oi.*, p.image_url
             FROM order_items oi
             LEFT JOIN products p ON oi.product_id = p.id
             WHERE oi.order_id = $orderId";
$itemsResult = $conn->query($itemsSql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Success - Ironix Hardware Shop</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { 
      font-family: 'Poppins', Arial, sans-serif; 
      background: #f8f9fa; 
      color: #222; 
      margin: 0; 
      padding: 0; 
      position: relative;
      overflow-x: hidden;
    }
    /* Animated Background - More Prominent */
    .animated-bg {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #4facfe 75%, #00f2fe 100%);
      background-size: 400% 400%;
      animation: gradientShift 12s ease infinite;
      opacity: 0.25;
    }
    @keyframes gradientShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    .floating-shapes {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 0;
      overflow: hidden;
      pointer-events: none;
    }
    .sparkle {
      position: absolute;
      width: 4px;
      height: 4px;
      background: #fff;
      border-radius: 50%;
      opacity: 0.8;
      animation: sparkle 3s infinite;
      box-shadow: 0 0 10px rgba(255,255,255,0.8);
    }
    @keyframes sparkle {
      0%, 100% { opacity: 0; transform: scale(0) translateY(0); }
      50% { opacity: 1; transform: scale(1) translateY(-20px); }
    }
    .shape {
      position: absolute;
      border-radius: 50%;
      opacity: 0.35;
      animation: float 18s infinite ease-in-out;
      filter: blur(1px);
      box-shadow: 0 0 30px rgba(0,0,0,0.2);
    }
    .shape:nth-child(1) {
      width: 100px;
      height: 100px;
      background: linear-gradient(135deg, #f5a623 0%, #f39c12 100%);
      top: 15%;
      left: 8%;
      animation-delay: 0s;
    }
    .shape:nth-child(2) {
      width: 140px;
      height: 140px;
      background: linear-gradient(135deg, #43a047 0%, #2e7d32 100%);
      top: 55%;
      left: 85%;
      animation-delay: 2s;
    }
    .shape:nth-child(3) {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, #5e72e4 0%, #4facfe 100%);
      top: 75%;
      left: 15%;
      animation-delay: 4s;
    }
    .shape:nth-child(4) {
      width: 120px;
      height: 120px;
      background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
      top: 25%;
      left: 75%;
      animation-delay: 6s;
    }
    .shape:nth-child(5) {
      width: 110px;
      height: 110px;
      background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
      top: 45%;
      left: 3%;
      animation-delay: 8s;
    }
    .shape:nth-child(6) {
      width: 90px;
      height: 90px;
      background: linear-gradient(135deg, #f093fb 0%, #764ba2 100%);
      top: 65%;
      left: 60%;
      animation-delay: 10s;
    }
    @keyframes float {
      0%, 100% { transform: translate(0, 0) rotate(0deg) scale(1); }
      25% { transform: translate(30px, -40px) rotate(90deg) scale(1.1); }
      50% { transform: translate(-30px, 30px) rotate(180deg) scale(0.9); }
      75% { transform: translate(40px, 15px) rotate(270deg) scale(1.05); }
    }
    .content-wrapper {
      position: relative;
      z-index: 1;
    }
    a { text-decoration: none; color: inherit; }
    .promo-bar { background: #fff8e1; color: #d35400; text-align: center; font-size: 0.95em; padding: 7px 0; border-bottom: 1px solid #ffe0b2; letter-spacing: 0.5px; }
    .promo-bar span { margin: 0 18px; }
    .main-nav { background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.04); padding: 0; position: sticky; top: 0; z-index: 100; }
    .nav-inner { 
      max-width: 1200px; 
      margin: 0 auto; 
      display: flex; 
      align-items: center; 
      justify-content: space-between; 
      padding: 0 24px; 
      height: 68px; 
      gap: 16px; 
    }
    .logo { font-size: 2em; font-weight: 700; color: #222; letter-spacing: -1px; order: 0; flex-shrink: 0; }
    .nav-search { 
      flex: 1; 
      display: flex; 
      justify-content: center; 
      margin: 0 20px; 
      min-width: 0; 
      max-width: 300px;
      order: 1;
    }
    .search-box { position: relative; width: 270px; max-width: 100%; }
    .search-box input { width: 100%; padding: 11px 16px 11px 44px; border: 1.5px solid #ffe0b2; border-radius: 24px; background: #f9f9f9; font-size: 1em; transition: border 0.2s; }
    .search-box input:focus { border-color: #f5a623; background: #fff; outline: none; }
    .search-box i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #f5a623; font-size: 1.1em; }
    .nav-links { 
      display: flex; 
      align-items: center; 
      gap: 32px; 
      flex: 1; 
      justify-content: center; 
      order: 2;
      margin: 0 20px;
    }
    .nav-links a {
      text-transform: uppercase;
      font-weight: 600;
      font-size: 0.97em;
      color: #222;
      padding: 6px 8px;
      letter-spacing: 0.04em;
      transition: color 0.2s;
      position: relative;
      white-space: nowrap;
    }
    .nav-links a:hover, .nav-links a.active {
      color: #f5a623;
    }
    .nav-icons-section {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-left: auto;
      padding-left: 24px;
      border-left: 2px solid #e0e0e0;
      flex-shrink: 0;
      order: 3;
    }
    .nav-icon-wrapper {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .nav-icon-wrapper a {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
      color: #243b55;
      font-size: 1.3em;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      text-decoration: none;
      box-shadow: 0 3px 8px rgba(0,0,0,0.08);
      border: 2px solid #e0e0e0;
      position: relative;
    }
    .nav-icon-wrapper a:hover {
      background: linear-gradient(135deg, #f5a623 0%, #f39c12 100%);
      color: #fff;
      transform: translateY(-2px) scale(1.1);
      box-shadow: 0 6px 16px rgba(245,166,35,0.4);
      border-color: #f5a623;
    }
    .profile-dropdown {
      position: relative;
      display: inline-block;
    }
    .profile-dropdown .dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      top: calc(100% + 10px);
      background: #fff;
      min-width: 180px;
      box-shadow: 0 8px 24px rgba(0,0,0,0.12);
      border-radius: 12px;
      overflow: hidden;
      z-index: 1000;
      border: 1px solid #e0e0e0;
    }
    .profile-dropdown:hover .dropdown-content {
      display: block;
    }
    .profile-dropdown .dropdown-content a {
      color: #222;
      padding: 14px 20px;
      display: flex;
      align-items: center;
      gap: 12px;
      font-size: 1em;
      border-bottom: 1px solid #f5f5f5;
      transition: background 0.2s, color 0.2s;
    }
    .profile-dropdown .dropdown-content a:hover {
      background: linear-gradient(135deg, #fff8e1 0%, #ffe0b2 100%);
      color: #f5a623;
    }
    .success-container {
      max-width: 700px;
      margin: 20px auto;
      padding: 0 20px;
      position: relative;
      z-index: 1;
    }
    .success-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 8px 32px rgba(36,59,85,0.2);
      padding: 20px 18px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    .success-card::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(245,166,35,0.2) 0%, transparent 70%);
      animation: rotate 15s linear infinite;
    }
    @keyframes rotate {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }
    .success-icon {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #43a047 0%, #2e7d32 100%);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 12px;
      animation: scaleIn 0.5s ease-out, pulse 2s ease-in-out infinite;
      position: relative;
      z-index: 1;
      box-shadow: 0 4px 20px rgba(67,160,71,0.4);
    }
    .success-icon i {
      font-size: 2em;
      color: #fff;
    }
    @keyframes scaleIn {
      from { transform: scale(0); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }
    @keyframes pulse {
      0%, 100% { transform: scale(1); box-shadow: 0 4px 20px rgba(67,160,71,0.4); }
      50% { transform: scale(1.08); box-shadow: 0 8px 35px rgba(67,160,71,0.7); }
    }
    .success-title {
      font-size: 1.4em;
      font-weight: 700;
      color: #243b55;
      margin-bottom: 8px;
      position: relative;
      z-index: 1;
    }
    .success-message {
      font-size: 0.9em;
      color: #666;
      margin-bottom: 16px;
      position: relative;
      z-index: 1;
    }
    .order-details-card {
      background: #f8f9fa;
      border-radius: 10px;
      padding: 16px;
      margin: 16px 0;
      text-align: left;
      position: relative;
      z-index: 1;
    }
    .order-details-card h3 {
      font-size: 1em;
      font-weight: 700;
      color: #243b55;
      margin-bottom: 12px;
      text-align: center;
      padding-bottom: 8px;
      border-bottom: 2px solid #e0e0e0;
    }
    .detail-row {
      display: flex;
      justify-content: space-between;
      padding: 6px 0;
      border-bottom: 1px solid #e0e0e0;
      font-size: 0.85em;
    }
    .detail-row:last-child {
      border-bottom: none;
    }
    .detail-label {
      font-weight: 600;
      color: #243b55;
    }
    .detail-value {
      color: #666;
    }
    .order-items-section {
      margin-top: 16px;
    }
    .order-items-section h4 {
      font-size: 0.95em;
      margin-bottom: 10px;
      color: #243b55;
      font-weight: 700;
    }
    .order-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 10px;
      background: #fff;
      border-radius: 8px;
      margin-bottom: 8px;
      border: 1px solid #e0e0e0;
    }
    .order-item img {
      width: 45px;
      height: 45px;
      object-fit: cover;
      border-radius: 6px;
      flex-shrink: 0;
    }
    .order-item-details {
      flex: 1;
      min-width: 0;
    }
    .order-item-name {
      font-weight: 700;
      color: #243b55;
      margin-bottom: 4px;
      font-size: 0.85em;
      line-height: 1.3;
    }
    .order-item-info {
      display: flex;
      gap: 12px;
      color: #666;
      font-size: 0.8em;
      flex-wrap: wrap;
    }
    .order-total {
      background: linear-gradient(135deg, #f5a623 0%, #f39c12 100%);
      color: #fff;
      padding: 12px;
      border-radius: 8px;
      margin-top: 14px;
      text-align: center;
    }
    .order-total-label {
      font-size: 0.9em;
      margin-bottom: 4px;
    }
    .order-total-amount {
      font-size: 1.5em;
      font-weight: 700;
    }
    .continue-shopping-btn {
      display: inline-block;
      padding: 10px 28px;
      background: linear-gradient(135deg, #f5a623 0%, #f39c12 100%);
      color: #fff;
      border-radius: 8px;
      font-size: 0.9em;
      font-weight: 600;
      margin-top: 16px;
      transition: transform 0.2s, box-shadow 0.2s;
      box-shadow: 0 4px 16px rgba(245,166,35,0.3);
      position: relative;
      z-index: 1;
    }
    .continue-shopping-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(245,166,35,0.4);
      color: #fff;
    }
  </style>
</head>
<body>
  <div class="promo-bar">
    <span><i class="fas fa-truck"></i> Free shipping on all orders</span>
    <span><i class="fas fa-undo"></i> Free returns <b>Up to 90 days*</b></span>
  </div>
  <nav class="main-nav">
    <div class="nav-inner">
      <a href="index.php" class="logo">IRONIX</a>
      <div class="nav-search">
        <div class="search-box">
          <i class="fas fa-search"></i>
          <input type="text" placeholder="Search for products..." onkeypress="if(event.key==='Enter'){window.location.href='search_results.php?query='+encodeURIComponent(this.value);}">
        </div>
      </div>
      <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="features.php">Features</a>
        <a href="services.php">Services</a>
        <a href="blog.php">Blog</a>
        <a href="contact.php">Contact</a>
        <a href="support.php">Support</a>
      </div>
      <div class="nav-icons-section">
        <div class="nav-icon-wrapper profile-dropdown">
          <a href="#" title="Account">
            <i class="fas fa-user-circle"></i>
          </a>
          <div class="dropdown-content">
            <a href="login.html"><i class="fas fa-sign-in-alt"></i> Login</a>
            <a href="registration.php"><i class="fas fa-user-plus"></i> Register</a>
          </div>
        </div>
        <div class="nav-icon-wrapper">
          <a href="cart.php" title="Shopping Cart">
            <i class="fas fa-shopping-cart"></i>
          </a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Animated Background -->
  <div class="animated-bg"></div>
  <div class="floating-shapes">
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="sparkle" style="top: 20%; left: 30%; animation-delay: 0s;"></div>
    <div class="sparkle" style="top: 50%; left: 70%; animation-delay: 1s;"></div>
    <div class="sparkle" style="top: 80%; left: 20%; animation-delay: 2s;"></div>
    <div class="sparkle" style="top: 30%; left: 80%; animation-delay: 0.5s;"></div>
    <div class="sparkle" style="top: 60%; left: 10%; animation-delay: 1.5s;"></div>
  </div>

  <div class="content-wrapper">
    <div class="success-container">
    <div class="success-card">
      <div class="success-icon">
        <i class="fas fa-check"></i>
      </div>
      <h1 class="success-title">Order Placed Successfully!</h1>
      <p class="success-message">Thank you for your purchase. Your order has been confirmed and will be processed shortly.</p>

      <div class="order-details-card">
        <h3><i class="fas fa-receipt"></i> Order Details</h3>
        
        <div class="detail-row">
          <span class="detail-label">Order Number:</span>
          <span class="detail-value"><strong><?= htmlspecialchars($order['order_number']) ?></strong></span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Order Date:</span>
          <span class="detail-value"><?= date('F d, Y h:i A', strtotime($order['order_date'])) ?></span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Customer Name:</span>
          <span class="detail-value"><?= htmlspecialchars($order['customer_name']) ?></span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Email:</span>
          <span class="detail-value"><?= htmlspecialchars($order['customer_email']) ?></span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Phone:</span>
          <span class="detail-value"><?= htmlspecialchars($order['customer_phone']) ?></span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Shipping Address:</span>
          <span class="detail-value"><?= htmlspecialchars($order['shipping_address'] ?: $order['customer_address']) ?></span>
        </div>
        <div class="detail-row">
          <span class="detail-label">Order Status:</span>
          <span class="detail-value" style="color: #f5a623; font-weight: 600;"><?= ucfirst($order['status']) ?></span>
        </div>

        <div class="order-items-section">
          <h4 style="margin-bottom: 20px; color: #243b55; font-size: 1.2em;">Products Ordered:</h4>
          <?php if ($itemsResult && $itemsResult->num_rows > 0): ?>
            <?php while($item = $itemsResult->fetch_assoc()): ?>
              <div class="order-item">
                <?php if ($item['image_url']): ?>
                  <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>">
                <?php else: ?>
                  <div style="width:80px; height:80px; background:#ddd; border-radius:8px;"></div>
                <?php endif; ?>
                <div class="order-item-details">
                  <div class="order-item-name"><?= htmlspecialchars($item['product_name']) ?></div>
                  <div class="order-item-info">
                    <span>Quantity: <?= $item['quantity'] ?></span>
                    <span>Unit Price: LKR <?= number_format($item['unit_price'], 2) ?></span>
                    <span>Subtotal: LKR <?= number_format($item['subtotal'], 2) ?></span>
                  </div>
                </div>
              </div>
            <?php endwhile; ?>
          <?php endif; ?>
        </div>

        <div class="order-total">
          <div class="order-total-label">Total Amount</div>
          <div class="order-total-amount">LKR <?= number_format($order['total_amount'], 2) ?></div>
        </div>
      </div>

      <a href="index.php" class="continue-shopping-btn">
        <i class="fas fa-arrow-left"></i> Continue Shopping
      </a>
    </div>
  </div>
  </div>

  <footer style="background:#181818; color:#fff; padding:48px 0 24px 0; margin-top:48px; font-family:'Poppins',Arial,sans-serif; position: relative; z-index: 1;">
    <div style="max-width:1200px; margin:0 auto; display:flex; flex-wrap:wrap; justify-content:space-between; gap:32px;">
      <div style="flex:1 1 180px; min-width:180px; margin-bottom:24px;">
        <h4 style="font-size:1.1em; font-weight:700; margin-bottom:18px;">Company info</h4>
        <div style="font-size:0.98em; line-height:2; color:#ccc;">
          <div><a href="#" style="color:#fff;">About Ironix</a></div>
          <div><a href="#" style="color:#fff;">Ironix – Shop Like a Pro</a></div>
          <div><a href="#" style="color:#fff;">Contact us</a></div>
          <div><a href="#" style="color:#fff;">Careers</a></div>
          <div><a href="#" style="color:#fff;">Press</a></div>
          <div><a href="#" style="color:#fff;">Ironix's Tree Planting Program</a></div>
        </div>
      </div>
      <div style="flex:1 1 180px; min-width:180px; margin-bottom:24px;">
        <h4 style="font-size:1.1em; font-weight:700; margin-bottom:18px;">Customer service</h4>
        <div style="font-size:0.98em; line-height:2; color:#ccc;">
          <div><a href="#" style="color:#fff;">Return and refund policy</a></div>
          <div><a href="#" style="color:#fff;">Intellectual property policy</a></div>
          <div><a href="#" style="color:#fff;">Shipping info</a></div>
          <div><a href="#" style="color:#fff;">Report suspicious activity</a></div>
        </div>
      </div>
      <div style="flex:1 1 180px; min-width:180px; margin-bottom:24px;">
        <h4 style="font-size:1.1em; font-weight:700; margin-bottom:18px;">Help</h4>
        <div style="font-size:0.98em; line-height:2; color:#ccc;">
          <div><a href="#" style="color:#fff;">Support center & FAQ</a></div>
          <div><a href="#" style="color:#fff;">Safety center</a></div>
          <div><a href="#" style="color:#fff;">Ironix purchase protection</a></div>
          <div><a href="#" style="color:#fff;">Sitemap</a></div>
          <div><a href="#" style="color:#fff;">Partner with Ironix</a></div>
        </div>
      </div>
      <div style="flex:1 1 260px; min-width:260px; margin-bottom:24px;">
        <h4 style="font-size:1.1em; font-weight:700; margin-bottom:18px;">Download the Ironix App</h4>
        <div style="font-size:0.98em; line-height:2; color:#ccc; margin-bottom:18px;">
          <span style="display:inline-block; margin-right:12px;"><i class="fas fa-bell" style="color:#f5a623;"></i> Price-drop alerts</span>
          <span style="display:inline-block; margin-right:12px;"><i class="fas fa-truck" style="color:#00b894;"></i> Track orders any time</span><br>
          <span style="display:inline-block; margin-right:12px;"><i class="fas fa-shield-alt" style="color:#0984e3;"></i> Secure checkout</span>
          <span style="display:inline-block; margin-right:12px;"><i class="fas fa-tags" style="color:#e17055;"></i> Exclusive offers</span>
        </div>
        <div style="display:flex; gap:16px; margin-bottom:18px;">
          <a href="#" style="display:inline-block; background:#fff; color:#000; border-radius:24px; padding:8px 18px; font-weight:600; font-size:1em; text-decoration:none; box-shadow:0 2px 8px rgba(0,0,0,0.08);"><i class="fab fa-apple" style="color:#000; font-size:1.2em; margin-right:8px;"></i>App Store</a>
          <a href="#" style="display:inline-block; background:#fff; color:#000; border-radius:24px; padding:8px 18px; font-weight:600; font-size:1em; text-decoration:none; box-shadow:0 2px 8px rgba(0,0,0,0.08);"><i class="fab fa-google-play" style="color:#34a853; font-size:1.2em; margin-right:8px;"></i>Google Play</a>
        </div>
        <div style="margin-top:18px;">
          <span style="font-weight:600; color:#fff;">Connect with Ironix</span>
          <div style="margin-top:10px; display:flex; gap:18px;">
            <a href="#" title="Instagram"><i class="fab fa-instagram" style="color:#e1306c; font-size:1.5em;"></i></a>
            <a href="#" title="Facebook"><i class="fab fa-facebook" style="color:#1877f3; font-size:1.5em;"></i></a>
            <a href="#" title="X"><i class="fab fa-x-twitter" style="color:#fff; font-size:1.5em;"></i></a>
            <a href="#" title="TikTok"><i class="fab fa-tiktok" style="color:#fff; font-size:1.5em;"></i></a>
            <a href="#" title="YouTube"><i class="fab fa-youtube" style="color:#ff0000; font-size:1.5em;"></i></a>
            <a href="#" title="Pinterest"><i class="fab fa-pinterest" style="color:#e60023; font-size:1.5em;"></i></a>
          </div>
        </div>
      </div>
    </div>
    <div style="text-align:center; color:#aaa; font-size:0.95em; margin-top:32px;">&copy; 2025 Ironix Hardware Shop. All Rights Reserved.</div>
  </footer>
</body>
</html>
<?php $conn->close(); ?>

