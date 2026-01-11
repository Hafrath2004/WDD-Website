<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ironix";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Modified query to join cart and products tables
$sql = "SELECT ";
$sql .= "c.product_id, c.quantity, "; // Get cart specific info
$sql .= "p.name, p.description, p.price, p.image_url "; // Get product specific info
$sql .= "FROM cart c JOIN products p ON c.product_id = p.id";

$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Ironix Hardware Shop - Cart</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', Arial, sans-serif; background: #f8f9fa; color: #222; margin: 0; padding: 0; }
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
    /* User and Cart Icons Section - Right Side */
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
    .nav-icon-wrapper a::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(245,166,35,0.1);
      transform: translate(-50%, -50%);
      transition: width 0.3s, height 0.3s;
    }
    .nav-icon-wrapper a:hover::before {
      width: 100%;
      height: 100%;
    }
    .nav-icon-wrapper a:hover {
      background: linear-gradient(135deg, #f5a623 0%, #f39c12 100%);
      color: #fff;
      transform: translateY(-2px) scale(1.1);
      box-shadow: 0 6px 16px rgba(245,166,35,0.4);
      border-color: #f5a623;
    }
    .nav-icon-wrapper a i {
      position: relative;
      z-index: 1;
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
      background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
      min-width: 240px;
      box-shadow: 0 12px 40px rgba(0,0,0,0.15), 0 4px 12px rgba(245,166,35,0.1);
      border-radius: 16px;
      overflow: hidden;
      z-index: 1000;
      border: 2px solid #f5a623;
      animation: dropdownFadeIn 0.3s ease-out;
      transform-origin: top right;
    }
    @keyframes dropdownFadeIn {
      from {
        opacity: 0;
        transform: translateY(-10px) scale(0.95);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }
    .profile-dropdown.active .dropdown-content {
      display: block;
    }
    .dropdown-header {
      background: linear-gradient(135deg, #f5a623 0%, #f39c12 100%);
      padding: 20px;
      text-align: center;
      color: #fff;
      border-bottom: 2px solid rgba(255,255,255,0.2);
    }
    .dropdown-header i {
      font-size: 2.5em;
      margin-bottom: 8px;
      display: block;
      text-shadow: 0 2px 8px rgba(0,0,0,0.2);
    }
    .dropdown-header h3 {
      margin: 0;
      font-size: 1.1em;
      font-weight: 700;
      letter-spacing: 0.5px;
    }
    .dropdown-header p {
      margin: 6px 0 0 0;
      font-size: 0.85em;
      opacity: 0.95;
      font-weight: 400;
    }
    .profile-dropdown .dropdown-content a {
      color: #243b55;
      padding: 18px 24px;
      display: flex;
      align-items: center;
      gap: 16px;
      font-size: 1.05em;
      font-weight: 600;
      border-bottom: 1px solid #f0f0f0;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }
    .profile-dropdown .dropdown-content a::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      width: 4px;
      height: 100%;
      background: linear-gradient(180deg, #f5a623 0%, #f39c12 100%);
      transform: scaleY(0);
      transition: transform 0.3s ease;
    }
    .profile-dropdown .dropdown-content a:hover::before {
      transform: scaleY(1);
    }
    .profile-dropdown .dropdown-content a:last-child {
      border-bottom: none;
    }
    .profile-dropdown .dropdown-content a:hover {
      background: linear-gradient(135deg, #fff8e1 0%, #ffe0b2 100%);
      color: #f5a623;
      padding-left: 28px;
      transform: translateX(4px);
    }
    .profile-dropdown .dropdown-content a i {
      font-size: 1.3em;
      width: 28px;
      text-align: center;
      color: #f5a623;
      transition: all 0.3s ease;
    }
    .profile-dropdown .dropdown-content a:hover i {
      color: #f39c12;
      transform: scale(1.15) rotate(5deg);
    }
    .dropdown-footer {
      padding: 12px;
      text-align: center;
      background: #f8f9fa;
      border-top: 1px solid #e0e0e0;
      font-size: 0.8em;
      color: #888;
    }
    /* Notification Dropdown Styles */
    .notification-dropdown {
      position: relative;
      display: inline-block;
    }
    .notification-dropdown .dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      top: calc(100% + 10px);
      background: linear-gradient(180deg, #ffffff 0%, #fafafa 100%);
      width: 320px;
      max-height: 420px;
      box-shadow: 0 12px 40px rgba(0,0,0,0.15), 0 4px 12px rgba(245,166,35,0.1);
      border-radius: 16px;
      overflow: hidden;
      z-index: 1000;
      border: 2px solid #f5a623;
      animation: dropdownFadeIn 0.3s ease-out;
      transform-origin: top right;
    }
    .notification-dropdown.active .dropdown-content {
      display: block;
    }
    .notification-header {
      background: linear-gradient(135deg, #f5a623 0%, #f39c12 100%);
      padding: 16px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: #fff;
      border-bottom: 2px solid rgba(255,255,255,0.2);
    }
    .notification-header h3 {
      margin: 0;
      font-size: 1.1em;
      font-weight: 700;
      letter-spacing: 0.5px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .notification-header h3 i {
      font-size: 1.2em;
    }
    .notification-header .mark-all-read {
      background: rgba(255,255,255,0.2);
      border: 1px solid rgba(255,255,255,0.3);
      color: #fff;
      padding: 6px 12px;
      border-radius: 8px;
      font-size: 0.85em;
      cursor: pointer;
      transition: all 0.3s;
      font-weight: 600;
    }
    .notification-header .mark-all-read:hover {
      background: rgba(255,255,255,0.3);
      transform: scale(1.05);
    }
    .notification-list {
      max-height: 320px;
      overflow-y: auto;
      background: #fff;
    }
    .notification-list::-webkit-scrollbar {
      width: 6px;
    }
    .notification-list::-webkit-scrollbar-track {
      background: #f1f1f1;
    }
    .notification-list::-webkit-scrollbar-thumb {
      background: #f5a623;
      border-radius: 3px;
    }
    .notification-item {
      padding: 14px 18px;
      border-bottom: 1px solid #f0f0f0;
      display: flex;
      align-items: flex-start;
      gap: 12px;
      transition: all 0.3s;
      cursor: pointer;
      position: relative;
    }
    .notification-item.unread {
      background: linear-gradient(90deg, #fff8e1 0%, #ffffff 100%);
      border-left: 3px solid #f5a623;
    }
    .notification-item.unread::before {
      content: '';
      position: absolute;
      left: 0;
      top: 50%;
      transform: translateY(-50%);
      width: 8px;
      height: 8px;
      background: #f5a623;
      border-radius: 50%;
    }
    .notification-item:hover {
      background: linear-gradient(135deg, #fff8e1 0%, #ffe0b2 100%);
      transform: translateX(2px);
    }
    .notification-item:last-child {
      border-bottom: none;
    }
    .notification-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2em;
      flex-shrink: 0;
      background: linear-gradient(135deg, #f5a623 0%, #f39c12 100%);
      color: #fff;
      box-shadow: 0 2px 8px rgba(245,166,35,0.3);
    }
    .notification-icon.order { background: linear-gradient(135deg, #3498db 0%, #2980b9 100%); }
    .notification-icon.discount { background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%); }
    .notification-icon.shipping { background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%); }
    .notification-icon.info { background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%); }
    .notification-content {
      flex: 1;
      min-width: 0;
    }
    .notification-content h4 {
      margin: 0 0 4px 0;
      font-size: 0.95em;
      font-weight: 700;
      color: #222;
      line-height: 1.3;
    }
    .notification-content p {
      margin: 0;
      font-size: 0.85em;
      color: #666;
      line-height: 1.4;
    }
    .notification-time {
      font-size: 0.75em;
      color: #999;
      margin-top: 4px;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .notification-footer {
      padding: 12px;
      text-align: center;
      background: #f8f9fa;
      border-top: 1px solid #e0e0e0;
    }
    .notification-footer a {
      color: #f5a623;
      font-weight: 600;
      font-size: 0.9em;
      text-decoration: none;
      transition: color 0.3s;
    }
    .notification-footer a:hover {
      color: #f39c12;
    }
    .notification-badge {
      position: absolute;
      top: -5px;
      right: -5px;
      background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
      color: #fff;
      font-size: 0.65em;
      font-weight: 700;
      padding: 4px 7px;
      border-radius: 12px;
      min-width: 20px;
      text-align: center;
      box-shadow: 0 2px 8px rgba(231,76,60,0.5);
      border: 2px solid #fff;
      animation: pulse 2s infinite;
    }
    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.1); }
    }
    .notification-empty {
      padding: 40px 20px;
      text-align: center;
      color: #999;
    }
    .notification-empty i {
      font-size: 3em;
      margin-bottom: 12px;
      color: #ddd;
    }
    .notification-empty p {
      margin: 0;
      font-size: 0.95em;
    }
    .main-content {
        padding-top: 80px; /* Adjust based on your nav bar height */
    }
    .cart-flex-container {
      display: flex;
      gap: 32px;
      max-width: 1200px;
      margin: 40px auto;
      align-items: flex-start;
    }
    .cart-items-list {
      flex: 2;
      min-width: 0;
    }
    .cart-summary-box {
      flex: 1;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 24px rgba(245,166,35,0.10);
      padding: 32px 24px;
      position: sticky;
      top: 120px;
      align-self: flex-start;
      min-width: 260px;
      max-width: 340px;
      margin-top: 32px;
    }
    .cart-summary-title {
      font-size: 1.3em;
      font-weight: 700;
      color: #f5a623;
      margin-bottom: 18px;
      text-align: center;
    }
    .cart-summary-total-label {
      color: #888;
      font-size: 1.1em;
      margin-bottom: 6px;
      text-align: center;
    }
    .cart-summary-total {
      font-size: 2em;
      font-weight: 700;
      color: #e67e22;
      margin-bottom: 28px;
      text-align: center;
    }
    .checkout-btn {
      width: 100%;
      padding: 18px 0;
      background: linear-gradient(90deg, #f5a623 0%, #f39c12 100%);
      color: #fff;
      font-size: 1.2em;
      font-weight: 700;
      border: none;
      border-radius: 32px;
      cursor: pointer;
      box-shadow: 0 4px 16px rgba(245,166,35,0.18);
      transition: background 0.2s, transform 0.2s, box-shadow 0.2s;
      margin-top: 12px;
    }
    .checkout-btn:hover {
      background: linear-gradient(90deg, #f39c12 0%, #f5a623 100%);
      color: #fff200;
      transform: scale(1.04);
      box-shadow: 0 8px 24px rgba(245,166,35,0.28);
    }
    .cart-items-list h1 {
      font-size: 2em;
      font-weight: 700;
      color: #243b55;
      margin-bottom: 32px;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .cart-items-list h1 i {
      color: #f5a623;
    }
    .empty-cart {
      text-align: center;
      padding: 60px 20px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(36,59,85,0.07);
      color: #888;
      font-size: 1.2em;
    }
    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      margin-top: 24px;
      padding: 12px 24px;
      background: #f5a623;
      color: #fff;
      border-radius: 8px;
      font-weight: 600;
      transition: background 0.2s;
    }
    .back-btn:hover {
      background: #ffb300;
      color: #fff;
    }
    .cart-item-card {
      display: flex;
      align-items: center;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(36,59,85,0.08);
      margin-bottom: 24px;
      padding: 24px;
      position: relative;
      transition: box-shadow 0.2s, transform 0.2s;
      border: 1px solid #f0f0f0;
    }
    .cart-item-card:hover {
      box-shadow: 0 8px 32px rgba(245,166,35,0.15);
      transform: translateY(-2px);
      border-color: #f5a623;
    }
    .cart-item-img-wrap {
      width: 120px;
      height: 120px;
      border-radius: 12px;
      overflow: hidden;
      margin-right: 24px;
      background: #f5f5f5;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      border: 2px solid #f0f0f0;
    }
    .cart-item-img-wrap img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
    .cart-item-details {
      flex: 1;
      min-width: 0;
    }
    .cart-item-title {
      font-size: 1.3em;
      font-weight: 700;
      color: #243b55;
      margin-bottom: 8px;
      line-height: 1.3;
    }
    .cart-item-desc {
      color: #666;
      font-size: 0.95em;
      margin-bottom: 12px;
      line-height: 1.5;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .cart-item-info-row {
      display: flex;
      align-items: center;
      gap: 24px;
      flex-wrap: wrap;
      margin-bottom: 8px;
    }
    .cart-item-price {
      color: #e67e22;
      font-size: 1.15em;
      font-weight: 600;
    }
    .cart-item-qty {
      color: #243b55;
      font-size: 1em;
      font-weight: 500;
    }
    .cart-item-total {
      color: #f5a623;
      font-size: 1.25em;
      font-weight: 700;
      margin-top: 8px;
    }
    .remove-item-btn {
      background: #fff0e1;
      color: #e74c3c;
      border: none;
      border-radius: 50%;
      width: 38px;
      height: 38px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2em;
      position: absolute;
      top: 16px;
      right: 16px;
      cursor: pointer;
      box-shadow: 0 2px 8px rgba(231,76,60,0.08);
      transition: background 0.2s, color 0.2s, transform 0.2s;
    }
    .remove-item-btn:hover {
      background: #e74c3c;
      color: #fff;
      transform: scale(1.12) rotate(-8deg);
    }
    @media (max-width: 900px) {
      .cart-flex-container {
        flex-direction: column;
        gap: 0;
      }
      .cart-summary-box {
        position: static;
        margin-top: 32px;
        max-width: 100%;
        width: 100%;
      }
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
      <!-- User and Cart Icons - Right Side -->
      <div class="nav-icons-section">
        <div class="nav-icon-wrapper profile-dropdown">
          <a href="#" title="Account">
            <i class="fas fa-user-circle"></i>
          </a>
          <div class="dropdown-content">
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
              <div class="dropdown-header">
                <i class="fas fa-user-circle"></i>
                <h3>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h3>
                <p>Signed in as <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
              </div>
              <a href="logout.php"><i class="fas fa-sign-out-alt"></i> <span>Logout</span></a>
            <?php else: ?>
              <div class="dropdown-header">
                <i class="fas fa-user-circle"></i>
                <h3>Welcome to IRONIX</h3>
                <p>Sign in to your account</p>
              </div>
              <a href="login.php"><i class="fas fa-sign-in-alt"></i> <span>Login to Account</span></a>
              <a href="register.php"><i class="fas fa-user-plus"></i> <span>Create New Account</span></a>
            <?php endif; ?>
            <div class="dropdown-footer">
              <i class="fas fa-shield-alt"></i> Secure & Fast
            </div>
          </div>
        </div>
        <div class="nav-icon-wrapper notification-dropdown">
          <a href="#" title="Notifications" class="notification-toggle">
            <i class="fas fa-bell"></i>
            <span class="notification-badge" id="notificationBadge">3</span>
          </a>
          <div class="dropdown-content">
            <div class="notification-header">
              <h3><i class="fas fa-bell"></i> Notifications</h3>
              <button class="mark-all-read" onclick="markAllAsRead()">Mark all read</button>
            </div>
            <div class="notification-list" id="notificationList">
              <div class="notification-item unread" onclick="markAsRead(this)">
                <div class="notification-icon order">
                  <i class="fas fa-box"></i>
                </div>
                <div class="notification-content">
                  <h4>Order Shipped!</h4>
                  <p>Your order #12345 has been shipped and is on its way.</p>
                  <div class="notification-time">
                    <i class="far fa-clock"></i> 2 hours ago
                  </div>
                </div>
              </div>
              <div class="notification-item unread" onclick="markAsRead(this)">
                <div class="notification-icon discount">
                  <i class="fas fa-tag"></i>
                </div>
                <div class="notification-content">
                  <h4>Special Discount!</h4>
                  <p>Get 25% off on all power tools. Limited time offer!</p>
                  <div class="notification-time">
                    <i class="far fa-clock"></i> 5 hours ago
                  </div>
                </div>
              </div>
              <div class="notification-item unread" onclick="markAsRead(this)">
                <div class="notification-icon shipping">
                  <i class="fas fa-truck"></i>
                </div>
                <div class="notification-content">
                  <h4>Free Shipping Available</h4>
                  <p>Your cart qualifies for free shipping. Add more items!</p>
                  <div class="notification-time">
                    <i class="far fa-clock"></i> 1 day ago
                  </div>
                </div>
              </div>
              <div class="notification-item" onclick="markAsRead(this)">
                <div class="notification-icon info">
                  <i class="fas fa-info-circle"></i>
                </div>
                <div class="notification-content">
                  <h4>Welcome to Ironix!</h4>
                  <p>Thank you for joining us. Explore our amazing products.</p>
                  <div class="notification-time">
                    <i class="far fa-clock"></i> 3 days ago
                  </div>
                </div>
              </div>
            </div>
            <div class="notification-footer">
              <a href="#">View All Notifications</a>
            </div>
          </div>
        </div>
        <div class="nav-icon-wrapper">
          <a href="cart.php" title="Shopping Cart" class="active">
            <i class="fas fa-shopping-cart"></i>
          </a>
        </div>
      </div>
    </div>
  </nav>
  <div class="main-content">
    <div class="cart-flex-container">
      <div class="cart-items-list">
        <h1><i class="fas fa-shopping-cart"></i> Your Cart</h1>
        <?php if ($result && $result->num_rows > 0): ?>
          <?php
            $grandTotal = 0;
            while($row = $result->fetch_assoc()):
              $total = $row['price'] * $row['quantity'];
              $grandTotal += $total;
          ?>
          <div class="cart-item-card">
            <div class="cart-item-img-wrap">
              <img src="<?=htmlspecialchars($row['image_url'])?>" alt="<?=htmlspecialchars($row['name'])?>">
            </div>
            <div class="cart-item-details">
              <div class="cart-item-title"><?=htmlspecialchars($row['name'])?></div>
              <div class="cart-item-desc"><?=htmlspecialchars($row['description'])?></div>
              <div class="cart-item-info-row">
                <div class="cart-item-price">Price: LKR <?=number_format($row['price'], 2)?></div>
                <div class="cart-item-qty">Quantity: <strong><?=intval($row['quantity'])?></strong></div>
              </div>
              <div class="cart-item-total">Total: LKR <?=number_format($total, 2)?></div>
            </div>
            <button class="remove-item-btn" data-product-id="<?= intval($row['product_id']) ?>" title="Remove from cart" onclick="removeFromCart(<?= intval($row['product_id']) ?>)">
              <i class="fas fa-trash-alt"></i>
            </button>
          </div>
          <?php endwhile; ?>
          <a href="index.php" class="back-btn" style="margin-top: 24px;"><i class="fa fa-arrow-left"></i> Continue Shopping</a>
        <?php else: ?>
          <div class="empty-cart">
            <i class="fas fa-shopping-cart" style="font-size: 3em; color: #ddd; margin-bottom: 16px;"></i>
            <p>Your cart is empty.</p>
            <a href="index.php" class="back-btn"><i class="fa fa-arrow-left"></i> Continue Shopping</a>
          </div>
        <?php endif; ?>
      </div>
      <div class="cart-summary-box">
        <div class="cart-summary-title">Order Summary</div>
        <div class="cart-summary-total-label">Grand Total:</div>
        <div class="cart-summary-total">LKR <?=isset($grandTotal) ? number_format($grandTotal, 2) : '0.00'?></div>
        <a href="checkout.php" class="checkout-btn" style="text-decoration: none; display: block; text-align: center;">Checkout</a>
      </div>
    </div>
  </div>

  <footer style="background:#181818; color:#fff; padding:48px 0 24px 0; margin-top:48px; font-family:'Poppins',Arial,sans-serif;">
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

<script>
    // Mode toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
      const modeToggleBtn = document.getElementById('modeToggle');
      const body = document.body;

      // Check for saved mode in localStorage
      const currentMode = localStorage.getItem('themeMode');
      if (currentMode) {
        body.classList.add(currentMode);
        if (currentMode === 'dark-mode') {
          modeToggleBtn.textContent = 'Light Mode';
        } else {
          modeToggleBtn.textContent = 'Dark Mode';
        }
      }

      modeToggleBtn.addEventListener('click', function() {
        if (body.classList.contains('dark-mode')) {
          body.classList.remove('dark-mode');
          localStorage.setItem('themeMode', 'light-mode');
          modeToggleBtn.textContent = 'Dark Mode';
        } else {
          body.classList.add('dark-mode');
          localStorage.setItem('themeMode', 'dark-mode');
          modeToggleBtn.textContent = 'Light Mode';
        }
      });
    });

    // Language dropdown functionality
    document.addEventListener('DOMContentLoaded', function() {
      var langBtn = document.querySelector('.language-dropdown > a');
      var langMenu = document.querySelector('.language-dropdown .language-content');

      if (langBtn && langMenu) {
        langBtn.addEventListener('click', function(e) {
          e.preventDefault();
          langMenu.style.display = (langMenu.style.display === 'block') ? 'none' : 'block';
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function(e) {
          if (!langBtn.contains(e.target) && !langMenu.contains(e.target)) {
            langMenu.style.display = 'none';
          }
        });
      }
    });

    // Add event listener to user icon to redirect to login.php
    document.addEventListener('DOMContentLoaded', function() {
      var userProfileIcon = document.getElementById('userProfileIcon');
      if (userProfileIcon) {
        userProfileIcon.addEventListener('click', function(e) {
          e.preventDefault();
          window.location.href = 'login.php';
        });
      }
    });

    // Add event listener to the search input
    const searchInput = document.querySelector('.search-wrapper input[type="text"]');
    if (searchInput) {
      searchInput.addEventListener('keypress', function(event) {
        // Check if the key pressed was Enter (key code 13)
        if (event.key === 'Enter') {
          event.preventDefault(); // Prevent default form submission
          const searchQuery = searchInput.value.trim();
          if (searchQuery) {
            // Redirect to search_results.php with query parameter
            window.location.href = 'search_results.php?query=' + encodeURIComponent(searchQuery);
          }
        }
      });
    }
</script>

<script>
    // Remove item from cart function
    function removeFromCart(productId) {
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            let formData = new FormData();
            formData.append('product_id', productId);

            fetch('remove_from_cart.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload(); // Reload page to update cart
                } else {
                    alert('Error removing item: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while removing the item.');
            });
        }
    }
    
    // Handle profile dropdown click
    const profileDropdown = document.querySelector('.profile-dropdown');
    const dropdownToggle = profileDropdown?.querySelector('a');
    const dropdownContent = profileDropdown?.querySelector('.dropdown-content');
    
    if (dropdownToggle && dropdownContent) {
      dropdownToggle.addEventListener('click', function(e) {
        e.preventDefault();
        profileDropdown.classList.toggle('active');
      });
      
      // Close dropdown when clicking outside
      document.addEventListener('click', function(e) {
        if (!profileDropdown.contains(e.target)) {
          profileDropdown.classList.remove('active');
        }
      });
      
      // Prevent dropdown from closing when clicking inside it
      dropdownContent.addEventListener('click', function(e) {
        e.stopPropagation();
      });
    }
    
    // Handle notification dropdown click
    const notificationDropdown = document.querySelector('.notification-dropdown');
    const notificationToggle = notificationDropdown?.querySelector('.notification-toggle');
    const notificationDropdownContent = notificationDropdown?.querySelector('.dropdown-content');
    
    if (notificationToggle && notificationDropdownContent) {
      notificationToggle.addEventListener('click', function(e) {
        e.preventDefault();
        notificationDropdown.classList.toggle('active');
      });
      
      // Close dropdown when clicking outside
      document.addEventListener('click', function(e) {
        if (!notificationDropdown.contains(e.target)) {
          notificationDropdown.classList.remove('active');
        }
      });
      
      // Prevent dropdown from closing when clicking inside it
      notificationDropdownContent.addEventListener('click', function(e) {
        e.stopPropagation();
      });
    }
    
    // Update notification badge count - make it globally accessible
    window.updateNotificationBadge = function() {
      const unreadCount = document.querySelectorAll('.notification-item.unread').length;
      const badge = document.getElementById('notificationBadge');
      if (badge) {
        if (unreadCount > 0) {
          badge.textContent = unreadCount;
          badge.style.display = 'block';
        } else {
          badge.style.display = 'none';
        }
      }
    };
    
    // Mark notification as read
    window.markAsRead = function(element) {
      element.classList.remove('unread');
      window.updateNotificationBadge();
    };
    
    // Mark all notifications as read
    window.markAllAsRead = function() {
      const unreadItems = document.querySelectorAll('.notification-item.unread');
      unreadItems.forEach(item => {
        item.classList.remove('unread');
      });
      window.updateNotificationBadge();
    };
    
    // Initialize badge count
    window.updateNotificationBadge();
</script>
