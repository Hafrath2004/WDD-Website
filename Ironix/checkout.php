<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ironix";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Get cart items
$sql = "SELECT c.product_id, c.quantity, p.name, p.description, p.price, p.image_url 
        FROM cart c JOIN products p ON c.product_id = p.id";
$result = $conn->query($sql);

$cartItems = [];
$grandTotal = 0;

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $total = $row['price'] * $row['quantity'];
        $grandTotal += $total;
        $cartItems[] = $row;
    }
}

// Get or create a default user (for demo purposes - in production, use logged-in user)
$userId = 1; // Default user ID - in production, get from session
$user = $conn->query("SELECT * FROM users WHERE id = $userId")->fetch_assoc();

if (!$user) {
    // Create a default user if none exists
    $conn->query("INSERT INTO users (name, email, phone, address) VALUES ('Guest User', 'guest@example.com', '0000000000', 'Default Address')");
    $userId = $conn->insert_id;
    $user = $conn->query("SELECT * FROM users WHERE id = $userId")->fetch_assoc();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout - Ironix Hardware Shop</title>
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
    .profile-dropdown:hover .dropdown-content {
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
    .checkout-container {
      max-width: 1200px;
      margin: 40px auto;
      padding: 0 24px;
    }
    .checkout-header {
      text-align: center;
      margin-bottom: 40px;
    }
    .checkout-header h1 {
      font-size: 2.5em;
      font-weight: 700;
      color: #243b55;
      margin-bottom: 12px;
    }
    .checkout-content {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 32px;
    }
    .checkout-section {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(36,59,85,0.08);
      padding: 32px;
    }
    .checkout-section h2 {
      font-size: 1.5em;
      font-weight: 700;
      color: #243b55;
      margin-bottom: 24px;
      padding-bottom: 16px;
      border-bottom: 2px solid #f0f0f0;
    }
    .product-item {
      display: flex;
      align-items: center;
      gap: 20px;
      padding: 20px;
      border: 1px solid #f0f0f0;
      border-radius: 12px;
      margin-bottom: 16px;
      transition: box-shadow 0.2s;
    }
    .product-item:hover {
      box-shadow: 0 4px 12px rgba(245,166,35,0.1);
    }
    .product-item img {
      width: 100px;
      height: 100px;
      object-fit: cover;
      border-radius: 10px;
      border: 2px solid #f0f0f0;
    }
    .product-item-details {
      flex: 1;
    }
    .product-item-name {
      font-size: 1.2em;
      font-weight: 700;
      color: #243b55;
      margin-bottom: 8px;
    }
    .product-item-desc {
      color: #666;
      font-size: 0.95em;
      margin-bottom: 12px;
      line-height: 1.5;
    }
    .product-item-info {
      display: flex;
      gap: 24px;
      align-items: center;
      flex-wrap: wrap;
    }
    .product-item-price {
      color: #e67e22;
      font-weight: 600;
      font-size: 1.1em;
    }
    .product-item-qty {
      color: #243b55;
      font-weight: 500;
    }
    .product-item-total {
      color: #f5a623;
      font-weight: 700;
      font-size: 1.2em;
    }
    .order-summary {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(36,59,85,0.08);
      padding: 32px;
      position: sticky;
      top: 100px;
      height: fit-content;
    }
    .summary-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 16px;
      padding-bottom: 16px;
      border-bottom: 1px solid #f0f0f0;
    }
    .summary-row:last-of-type {
      border-bottom: none;
      margin-bottom: 24px;
    }
    .summary-total {
      font-size: 1.5em;
      font-weight: 700;
      color: #f5a623;
    }
    .action-buttons {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    .btn {
      padding: 16px 24px;
      border: none;
      border-radius: 12px;
      font-size: 1.1em;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      text-align: center;
      text-decoration: none;
      display: block;
    }
    .btn-buy-now {
      background: linear-gradient(135deg, #f5a623 0%, #f39c12 100%);
      color: #fff;
      box-shadow: 0 4px 16px rgba(245,166,35,0.3);
    }
    .btn-buy-now:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(245,166,35,0.4);
    }
    .btn-add-cart {
      background: #fff;
      color: #f5a623;
      border: 2px solid #f5a623;
    }
    .btn-add-cart:hover {
      background: #f5a623;
      color: #fff;
    }
    .customer-info {
      background: #f8f9fa;
      padding: 20px;
      border-radius: 12px;
      margin-top: 24px;
    }
    .customer-info p {
      margin: 8px 0;
      color: #666;
    }
    .customer-info strong {
      color: #243b55;
    }
    @media (max-width: 900px) {
      .checkout-content {
        grid-template-columns: 1fr;
      }
      .order-summary {
        position: static;
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
      <div class="nav-icons-section">
        <div class="nav-icon-wrapper profile-dropdown">
          <a href="#" title="Account">
            <i class="fas fa-user-circle"></i>
          </a>
          <div class="dropdown-content">
            <div class="dropdown-header">
              <i class="fas fa-user-circle"></i>
              <h3>Welcome to IRONIX</h3>
              <p>Sign in to your account</p>
            </div>
            <a href="login.html"><i class="fas fa-sign-in-alt"></i> <span>Login to Account</span></a>
            <a href="registration.php"><i class="fas fa-user-plus"></i> <span>Create New Account</span></a>
            <div class="dropdown-footer">
              <i class="fas fa-shield-alt"></i> Secure & Fast
            </div>
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

  <div class="checkout-container">
    <div class="checkout-header">
      <h1><i class="fas fa-shopping-bag"></i> Checkout</h1>
      <p style="color: #666; font-size: 1.1em;">Review your order and complete your purchase</p>
    </div>

    <div class="checkout-content">
      <div class="checkout-section">
        <h2><i class="fas fa-box"></i> Product Details</h2>
        <?php if (count($cartItems) > 0): ?>
          <?php foreach($cartItems as $item): 
            $itemTotal = $item['price'] * $item['quantity'];
          ?>
            <div class="product-item">
              <img src="<?= htmlspecialchars($item['image_url']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
              <div class="product-item-details">
                <div class="product-item-name"><?= htmlspecialchars($item['name']) ?></div>
                <div class="product-item-desc"><?= htmlspecialchars($item['description']) ?></div>
                <div class="product-item-info">
                  <span class="product-item-price">Price: LKR <?= number_format($item['price'], 2) ?></span>
                  <span class="product-item-qty">Quantity: <?= $item['quantity'] ?></span>
                  <span class="product-item-total">Total: LKR <?= number_format($itemTotal, 2) ?></span>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p style="text-align: center; padding: 40px; color: #999;">Your cart is empty. <a href="index.php" style="color: #f5a623;">Continue Shopping</a></p>
        <?php endif; ?>
      </div>

      <div class="order-summary">
        <h2><i class="fas fa-receipt"></i> Order Summary</h2>
        
        <div class="customer-info">
          <h3 style="margin-top: 0; color: #243b55; font-size: 1.1em;">Customer Information</h3>
          <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
          <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></p>
          <p><strong>Address:</strong> <?= htmlspecialchars($user['address']) ?></p>
        </div>

        <div class="summary-row">
          <span>Subtotal:</span>
          <span>LKR <?= number_format($grandTotal, 2) ?></span>
        </div>
        <div class="summary-row">
          <span>Shipping:</span>
          <span style="color: #43a047;">FREE</span>
        </div>
        <div class="summary-row">
          <span class="summary-total">Grand Total:</span>
          <span class="summary-total">LKR <?= number_format($grandTotal, 2) ?></span>
        </div>

        <div class="action-buttons">
          <form method="POST" action="process_order.php" style="margin: 0;">
            <input type="hidden" name="action" value="buy_now">
            <button type="submit" class="btn btn-buy-now">
              <i class="fas fa-credit-card"></i> Buy Now
            </button>
          </form>
          <a href="cart.php" class="btn btn-add-cart">
            <i class="fas fa-arrow-left"></i> Back to Cart
          </a>
        </div>
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

