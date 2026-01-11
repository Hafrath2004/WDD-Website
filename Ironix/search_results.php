<?php
// search_results.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ironix";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search_query = "";
$search_results = [];

if (isset($_GET['query']) && !empty($_GET['query'])) {
    $search_query = $conn->real_escape_string($_GET['query']);

    // Search for products matching the query in name or description
    $sql = "SELECT id, name, description, price, image_url FROM products WHERE name LIKE '%" . $search_query . "%' OR description LIKE '%" . $search_query . "%'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $search_results[] = $row;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Ironix Hardware Shop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Basic styling for body and container */
        body {
            font-family: 'Poppins', Arial, sans-serif;
            background: #f8f9fa;
            color: #2c3e50;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 40px auto;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            padding: 30px;
            text-align: center;
        }
        h1 {
            margin-bottom: 30px;
            font-size: 2.2em;
            color: #141e30;
            position: relative;
            display: inline-block;
        }
        h1:after {
            content: '';
            display: block;
            width: 80px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #f5a623, transparent);
            margin: 8px auto 0;
        }
        .search-results-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
            margin-top: 30px;
        }
        .product-card {
            background: #ffffff;
            border: none;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            padding: 20px;
            width: 280px;
            transition: all 0.3s ease;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
            text-align: left;
        }
         .product-card:hover { 
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.12);
        }
        .product-card img {
            width: 100%;
            height: 180px; /* Fixed height for images */
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
             transition: all 0.5s ease;
        }
        .product-card:hover img {
          transform: scale(1.05);
        }
        .product-card h3 {
            font-size: 1.3em;
            margin: 0 0 10px;
            color: #141e30;
        }
        .product-card p {
            font-size: 0.95em;
            color: #34495e;
            line-height: 1.5;
            margin-bottom: 15px;
        }
        .product-card .price {
            font-size: 1.2em;
            color: #141e30;
            font-weight: bold;
            display: inline-block;
            padding: 5px 15px;
            background: #f0f4f8;
            border-radius: 20px;
        }
         .no-results {
            font-size: 1.2em;
            color: #888;
            margin-top: 30px;
        }
         .back-link {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 20px;
            background: #f5a623;
            color: #fff;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 600;
             transition: background 0.3s, transform 0.2s, box-shadow 0.3s;
             box-shadow: 0 4px 12px rgba(245,166,35,0.15);
        }
        .back-link:hover {
            background: #f39c12;
             transform: translateY(-2px) scale(1.03);
             box-shadow: 0 8px 20px rgba(245,166,35,0.25);
        }
    </style>
    <style>
        /* Dark Mode Styles for search_results.php */
        body.dark-mode {
          background: #1a202c;
          color: #e2e8f0;
        }
        .dark-mode .container {
          background: #2d3748;
          box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        .dark-mode h1 {
          color: #e2e8f0;
        }
        .dark-mode h1:after {
          background: linear-gradient(90deg, transparent, #f5a623, transparent); /* Keep accent color */
        }
        .dark-mode .product-card {
          background: #4a5568;
          box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }
        .dark-mode .product-card:hover {
          box-shadow: 0 12px 30px rgba(0,0,0,0.4);
        }
        .dark-mode .product-card img {
          border: 1px solid #4a5568; /* Optional: Add border in dark mode */
        }
        .dark-mode .product-card h3 {
          color: #e2e8f0;
        }
        .dark-mode .product-card p {
          color: #a0aec0;
        }
        .dark-mode .product-card .price {
          color: #f5a623;
          background: #2d3748;
        }
        .dark-mode .discount-badge {
          background-color: #f5a623; /* Use accent color for badge */
          color: #1a202c;
        }
        .dark-mode .rating i.fas {
            color: #f5c107; /* Keep gold stars */
         }
         .dark-mode .rating i.far {
            color: #a0aec0; /* Lighter empty stars */
         }
        .dark-mode .no-results {
          color: #a0aec0;
        }
        .dark-mode .back-link {
          background: #f5a623;
          color: #1a202c;
          box-shadow: 0 4px 12px rgba(245,166,35,0.25);
        }
        .dark-mode .back-link:hover {
          background: #f39c12;
          box-shadow: 0 8px 20px rgba(245,166,35,0.35);
        }
        .dark-mode #modeToggle {
            background: #4a5568;
            color: #e2e8f0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
         .dark-mode #modeToggle:hover {
            background: #718096;
            box-shadow: 0 6px 15px rgba(0,0,0,0.3);
         }
    </style>
    <style>
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
        .logo { font-size: 2em; font-weight: 700; color: #222; letter-spacing: -1px; order: 0; flex-shrink: 0; text-decoration: none; }
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
          text-decoration: none;
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
          text-decoration: none;
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
        @media (max-width: 900px) {
          .nav-inner { flex-direction: column; height: auto; padding: 12px; }
          .nav-search { margin: 12px 0; width: 100%; }
          .search-box { width: 100%; }
          .nav-links { gap: 12px; margin-left: 0; flex-wrap: wrap; justify-content: center; }
          .nav-icons-section { margin-left: 0; padding-left: 0; border-left: none; border-top: 2px solid #e0e0e0; padding-top: 12px; margin-top: 12px; width: 100%; justify-content: center; }
        }
        @media (max-width: 600px) {
          .nav-inner { flex-direction: column; padding: 8px; }
          .logo { font-size: 1.3em; }
          .nav-links { gap: 8px; font-size: 0.85em; }
          .nav-icon-wrapper a { width: 36px; height: 36px; font-size: 1.1em; }
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
    <div class="container">
        <h1>Search Results for "<?= htmlspecialchars($search_query) ?>"</h1>

        <?php if (!empty($search_results)): ?>
            <div class="search-results-grid">
                <?php foreach ($search_results as $product): ?>
                    <div class="product-card">
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <h3><?= htmlspecialchars($product['name']) ?></h3>
                        <p><?= htmlspecialchars($product['description']) ?></p>
                        
                        <?php
                        // Generate random discount and rating
                        $discount_percentage = rand(5, 30);
                        $original_price = $product["price"];
                        $discounted_price = $original_price * (1 - $discount_percentage / 100);
                        $star_rating = rand(3, 5);
                        ?>
                        
                        <p class="price"><span style="text-decoration: line-through; color: #888; font-size: 0.9em; margin-right: 5px;">LKR <?= number_format($original_price, 2) ?></span>LKR <?= number_format($discounted_price, 2) ?></p>
                        <div class="rating" style="color: #ffc107; margin-bottom: 10px;">
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <?php if ($i < $star_rating): ?>
                                    <i class="fas fa-star"></i>
                                <?php else: ?>
                                    <i class="far fa-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>

                        <!-- Add to Cart button or link could be added here if needed -->
                         <a href="#" class="back-link" style="margin-top: 15px; display: block; text-align: center; width: 100%;">View Product</a>

                         <div class="discount-badge" style="position: absolute; top: 10px; right: 10px; background-color: #dc3545; color: white; padding: 5px 10px; border-radius: 5px; font-size: 0.8em; font-weight: bold;">-<?= $discount_percentage ?>%</div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php elseif (!empty($search_query)): ?>
            <div class="no-results">
                No products found for "<?= htmlspecialchars($search_query) ?>".
            </div>
        <?php else: ?>
             <div class="no-results">
                Please enter a search query.
            </div>
        <?php endif; ?>

        <a href="index.php" class="back-link">Back to Home</a>
        <!-- Mode Change Button -->
        <button id="modeToggle" style="padding: 10px 20px; border-radius: 25px; background: #f0f4f8; border: none; cursor: pointer; transition: all 0.3s ease; font-size: 1em; font-weight: 600; color: #243b55; margin-left: 20px;">Light Mode</button>
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
  </script>
</html>
<?php $conn->close(); ?> 