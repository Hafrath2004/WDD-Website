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
$admins = $conn->query("SELECT * FROM admin");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Ironix Admin Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Google Fonts & Font Awesome -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --main-blue: #243b55;
      --main-gold: #f5a623;
      --main-bg: #f8f9fa;
      --card-red: #ff5e62;
      --card-blue: #5e72e4;
      --card-yellow: #ffd600;
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
    /* Sidebar */
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
    /* Main Content */
    .main-content {
      flex: 1;
      padding: 36px 40px 36px 40px;
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
      margin-bottom: 8px;
    }
    /* Beautiful Welcome Banner for Admin */
    .admin-welcome-banner {
      background: linear-gradient(135deg, #f5a623 0%, #f39c12 25%, #e67e22 50%, #d35400 75%, #c0392b 100%);
      color: #fff;
      padding: 10px 18px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(245, 166, 35, 0.3);
      display: inline-flex;
      align-items: center;
      gap: 8px;
      font-size: 0.9em;
      font-weight: 600;
      animation: welcomeSlideIn 0.5s ease-out;
      position: relative;
      overflow: hidden;
    }
    .admin-welcome-banner::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
      animation: shimmer 4s infinite;
    }
    @keyframes welcomeSlideIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    @keyframes shimmer {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    .admin-welcome-banner .welcome-icon {
      font-size: 1.1em;
      animation: bounce 2s infinite;
      position: relative;
      z-index: 1;
    }
    @keyframes bounce {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-5px); }
    }
    .admin-welcome-banner .welcome-text {
      position: relative;
      z-index: 1;
      text-shadow: 1px 1px 3px rgba(0,0,0,0.2);
    }
    .admin-welcome-banner .admin-name {
      font-weight: 800;
      color: #fff;
      text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
      background: linear-gradient(45deg, #fff 0%, #ffe0b2 50%, #fff 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      animation: nameGlow 2s ease-in-out infinite;
      display: inline-block;
      margin: 0 4px;
      position: relative;
      z-index: 1;
    }
    @keyframes nameGlow {
      0%, 100% { 
        filter: drop-shadow(0 0 3px rgba(255,255,255,0.5));
      }
      50% { 
        filter: drop-shadow(0 0 8px rgba(255,255,255,0.9));
      }
    }
    .admin-welcome-banner .welcome-emoji {
      font-size: 1em;
      margin: 0 2px;
      animation: wave 1.5s ease-in-out infinite;
      position: relative;
      z-index: 1;
    }
    @keyframes wave {
      0%, 100% { transform: rotate(0deg); }
      25% { transform: rotate(15deg); }
      75% { transform: rotate(-15deg); }
    }
    .admin-welcome-banner.fade-out {
      animation: welcomeFadeOut 0.5s ease-out forwards !important;
      pointer-events: none !important;
      opacity: 0 !important;
    }
    @keyframes welcomeFadeOut {
      0% {
        opacity: 1;
        transform: translateY(0) scale(1);
        max-height: 100px;
        margin-bottom: 8px;
        padding: 10px 18px;
      }
      100% {
        opacity: 0;
        transform: translateY(-20px) scale(0.9);
        max-height: 0;
        margin-bottom: 0;
        padding: 0;
        overflow: hidden;
      }
    }
    .dashboard-header .profile {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .dashboard-header .profile-img {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid var(--main-gold);
    }
    .dashboard-header .profile-name {
      font-weight: 500;
      color: var(--main-blue);
    }
    .profile-dropdown {
      position: relative;
      display: inline-block;
    }
    .profile-btn {
      background: none;
      border: none;
      color: var(--main-blue);
      font-size: 1.2em;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .profile-menu {
      display: none;
      position: absolute;
      right: 0;
      top: 120%;
      background: #fff;
      min-width: 140px;
      box-shadow: 0 4px 16px rgba(36,59,85,0.12);
      border-radius: 10px;
      z-index: 10;
      padding: 8px 0;
    }
    .profile-menu a {
      display: flex;
      align-items: center;
      gap: 8px;
      color: var(--main-blue);
      padding: 10px 18px;
      text-decoration: none;
      font-size: 1em;
      transition: background 0.2s;
    }
    .profile-menu a:hover {
      background: #f5a623;
      color: #fff;
    }
    .profile-dropdown.show .profile-menu {
      display: block;
    }
    /* Cards */
    .dashboard-cards {
      display: flex;
      gap: 24px;
      margin-bottom: 32px;
      flex-wrap: wrap;
    }
    .dashboard-card {
      flex: 1 1 220px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(36,59,85,0.07);
      padding: 24px 20px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
      min-width: 200px;
      position: relative;
      overflow: hidden;
    }
    .dashboard-card .card-icon {
      font-size: 2em;
      margin-bottom: 10px;
      opacity: 0.8;
    }
    .dashboard-card.red { border-top: 5px solid var(--card-red);}
    .dashboard-card.red .card-icon { color: var(--card-red);}
    .dashboard-card.blue { border-top: 5px solid var(--card-blue);}
    .dashboard-card.blue .card-icon { color: var(--card-blue);}
    .dashboard-card.yellow { border-top: 5px solid var(--card-yellow);}
    .dashboard-card.yellow .card-icon { color: var(--card-yellow);}
    .dashboard-card .card-title {
      font-size: 1.1em;
      font-weight: 600;
      margin-bottom: 6px;
      color: var(--main-blue);
    }
    .dashboard-card .card-value {
      font-size: 2em;
      font-weight: 700;
      margin-bottom: 8px;
    }
    .dashboard-card .card-desc {
      font-size: 0.98em;
      color: #888;
      margin-bottom: 6px;
    }
    .dashboard-card .card-date {
      font-size: 0.9em;
      color: #b0b0b0;
    }
    /* Table */
    .dashboard-table-container {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(36,59,85,0.07);
      padding: 24px 20px;
      margin-top: 12px;
    }
    .dashboard-table-tabs {
      display: flex;
      gap: 12px;
      margin-bottom: 18px;
    }
    .dashboard-table-tabs button {
      background: #f0f4f8;
      border: none;
      border-radius: 8px;
      padding: 8px 18px;
      font-size: 1em;
      color: var(--main-blue);
      font-weight: 500;
      cursor: pointer;
      transition: background 0.2s, color 0.2s;
    }
    .dashboard-table-tabs button.active,
    .dashboard-table-tabs button:hover {
      background: var(--main-gold);
      color: #fff;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 8px;
    }
    th, td {
      padding: 12px 10px;
      text-align: left;
    }
    th {
      background: #f0f4f8;
      color: var(--main-blue);
      font-weight: 600;
      font-size: 1em;
    }
    tr:nth-child(even) {
      background: #f8f9fa;
    }
    tr:hover {
      background: #fffbe6;
    }
    .payment-reminder {
      background: var(--main-gold);
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 8px 18px;
      font-size: 1em;
      font-weight: 500;
      float: right;
      margin-bottom: 10px;
      cursor: pointer;
      transition: background 0.2s;
    }
    .payment-reminder:hover {
      background: #ffb300;
    }
    @media (max-width: 900px) {
      .dashboard-cards {
        flex-direction: column;
      }
      .main-content {
        padding: 24px 6px;
      }
    }
    @media (max-width: 600px) {
      .sidebar {
        display: none;
      }
      .main-content {
        padding: 12px 2px;
      }
    }
  </style>
</head>
<body>
  <div class="dashboard-container">
    <!-- Sidebar -->
    <nav class="sidebar">
      <div class="brand"><i class="fa-solid fa-screwdriver-wrench"></i> Ironix Admin</div>
      <ul>
        <li><a href="admin_dashboard.php" class="active"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
        <li><a href="admin_products.php"><i class="fa-solid fa-box"></i> Products</a></li>
        <li><a href="admin_customers.php"><i class="fa-solid fa-users"></i> Customers</a></li>
        <li><a href="admin_suppliers.php"><i class="fa-solid fa-truck"></i> Suppliers</a></li>
        <li><a href="admin_orders.php"><i class="fa-solid fa-shopping-cart"></i> Orders</a></li>
        <li><a href="admin_inventory.php"><i class="fa-solid fa-warehouse"></i> Inventory</a></li>
        <li><a href="admin_admins.php"><i class="fa-solid fa-user-gear"></i> Admins</a></li>
        <li><a href="admin_settings.php"><i class="fa-solid fa-gear"></i> Settings</a></li>
      </ul>
      <div class="sidebar-footer">
        &copy; 2025 Ironix Hardware Shop
      </div>
    </nav>
    <!-- Main Content -->
    <div class="main-content">
      <div class="dashboard-header">
        <div>
          <div class="welcome">Welcome Back 👋</div>
          <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true && isset($_SESSION['admin_name'])): ?>
            <div class="admin-welcome-banner">
              <span class="welcome-icon">👋</span>
              <span class="welcome-text">Welcome,</span>
              <span class="admin-name"><?php echo htmlspecialchars($_SESSION['admin_name']); ?></span>
              <span class="welcome-emoji">🎉</span>
            </div>
          <?php endif; ?>
        </div>
        <a href="index.php" class="btn btn-primary" style="background-color: var(--main-gold); color: var(--main-blue); border: none; border-radius: 8px; padding: 10px 15px; text-decoration: none; font-weight: 600;">Go to Website Home</a>
        <div class="profile">
          <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Admin" class="profile-img">
          <span class="profile-name"><?php echo isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Admin User'; ?></span>
          <div class="profile-dropdown">
            <button class="profile-btn">
              <i class="fa-regular fa-user"></i>
              <i class="fa-solid fa-caret-down"></i>
            </button>
            <div class="profile-menu">
              <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                <a href="admin_logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
              <?php else: ?>
                <a href="admin_login.php"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
                <a href="admin_register.php"><i class="fa-solid fa-user-plus"></i> Create Account</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <!-- Cards -->
      <div class="dashboard-cards">
        <div class="dashboard-card red">
          <i class="fa-solid fa-box card-icon"></i>
          <div class="card-title">Total Products</div>
          <div class="card-value">450</div>
          <div class="card-desc">Products in Inventory</div>
          <div class="card-date"><i class="fa-regular fa-calendar"></i> 30 April 2025</div>
        </div>
        <div class="dashboard-card blue">
          <i class="fa-solid fa-users card-icon"></i>
          <div class="card-title">Customers</div>
          <div class="card-value">310</div>
          <div class="card-desc">Active Customers</div>
          <div class="card-date"><i class="fa-regular fa-calendar"></i> 30 April 2025</div>
        </div>
        <div class="dashboard-card yellow">
          <i class="fa-solid fa-warehouse card-icon"></i>
          <div class="card-title">Current Stock</div>
          <div class="card-value">560</div>
          <div class="card-desc">Items in Stock</div>
          <div class="card-date"><i class="fa-regular fa-calendar"></i> 30 April 2025</div>
        </div>
      </div>

      <!-- New Sections for Facilities and Summaries -->
      <div class="dashboard-table-container" style="margin-top: 24px;">
        <h2>Recent Customers</h2>
        <p>Placeholder for a short list or summary of recent customer activity.</p>
        <!-- Add dynamic content here -->
      </div>

      <div class="dashboard-table-container" style="margin-top: 24px;">
        <h2>Admin Overview</h2>
        <p>Placeholder for a short summary or key details about admins.</p>
        <!-- Add dynamic content here -->
      </div>

      <div class="dashboard-table-container" style="margin-top: 24px;">
        <h2>Quick Actions</h2>
        <!-- Add quick action buttons or links here -->
        
        <p>Placeholder for frequently used actions or links.</p>
        <!-- Add quick action buttons or links here -->
      </div>

      <!-- Old Product and Admin Tables (hidden) -->
      <div class="dashboard-table-container" style="display: none;">
        <h2>Products</h2>
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Description</th>
              <th>Price</th>
              <th>Image URL</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $latestProduct = $conn->query("SELECT * FROM products ORDER BY id DESC LIMIT 1");
            if ($latestProduct && $latestProduct->num_rows > 0):
              $row = $latestProduct->fetch_assoc(); ?>
                <tr>
                  <td><?= htmlspecialchars($row['id']) ?></td>
                  <td><?= htmlspecialchars($row['name']) ?></td>
                  <td><?= htmlspecialchars($row['description']) ?></td>
                  <td>LKR <?= number_format($row['price'], 2) ?></td>
                  <td style="max-width:220px; overflow-x:auto; white-space:nowrap;">
                    <a href="<?= htmlspecialchars($row['image_url']) ?>" target="_blank">View Image</a>
                  </td>
                </tr>
            <?php else: ?>
              <tr><td colspan="5" style="text-align:center;">No products found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <!-- Admins Table (Dynamic from DB) -->
      <div id="admins-table" style="display:none;">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Password</th>
              <th>Gender</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($admins && $admins->num_rows > 0): ?>
              <?php while($row = $admins->fetch_assoc()): ?>
                <tr>
                  <td><?= htmlspecialchars($row['id']) ?></td>
                  <td><?= htmlspecialchars($row['name']) ?></td>
                  <td><?= htmlspecialchars($row['email']) ?></td>
                  <td><?= htmlspecialchars($row['password']) ?></td>
                  <td><?= htmlspecialchars($row['gender']) ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="5" style="text-align:center;">No admins found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <script>
    // Tab switching logic
    const tabs = document.querySelectorAll('.dashboard-table-tabs button');
    const tables = [
      document.querySelector('table'), // Products table (first)
      null, // Customers table (not implemented)
      document.getElementById('admins-table')
    ];
    tabs.forEach((tab, idx) => {
      tab.addEventListener('click', () => {
        tabs.forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        // Hide all tables
        document.querySelector('table').style.display = idx === 0 ? '' : 'none';
        if (tables[2]) tables[2].style.display = idx === 2 ? '' : 'none';
        // (You can add logic for Customers table if needed)
      });
    });
    // Profile dropdown logic
    document.querySelector('.profile-btn').onclick = function(e) {
      e.stopPropagation();
      document.querySelector('.profile-dropdown').classList.toggle('show');
    };
    document.body.onclick = function() {
      document.querySelector('.profile-dropdown').classList.remove('show');
    };
    // Product Actions Dropdown logic
    document.getElementById('productActionsBtn').onclick = function(e) {
      e.stopPropagation();
      var menu = document.getElementById('productActionsMenu');
      menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    };
    document.body.addEventListener('click', function() {
      document.getElementById('productActionsMenu').style.display = 'none';
    });
    
    // Auto-dismiss admin welcome banner after 5 seconds
    (function() {
      let bannerDismissed = false;
      
      function dismissWelcomeBanner() {
        if (bannerDismissed) return;
        
        const adminWelcomeBanner = document.querySelector('.admin-welcome-banner');
        if (adminWelcomeBanner && adminWelcomeBanner.offsetParent !== null) {
          bannerDismissed = true;
          
          // Start countdown - banner will disappear after 5 seconds
          setTimeout(function() {
            // Add fade-out class to trigger animation
            adminWelcomeBanner.classList.add('fade-out');
            
            // Force removal after animation
            setTimeout(function() {
              adminWelcomeBanner.style.cssText = 'display: none !important; visibility: hidden !important; opacity: 0 !important; height: 0 !important; margin: 0 !important; padding: 0 !important; overflow: hidden !important;';
              try {
                adminWelcomeBanner.remove();
              } catch(e) {
                // If remove fails, parent will handle it
                if (adminWelcomeBanner.parentNode) {
                  adminWelcomeBanner.parentNode.removeChild(adminWelcomeBanner);
                }
              }
            }, 500); // Wait for fade-out animation to complete
          }, 5000); // Show for 5 seconds
        }
      }
      
      // Try immediately
      dismissWelcomeBanner();
      
      // Try on DOMContentLoaded
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', dismissWelcomeBanner);
      }
      
      // Try on window load
      window.addEventListener('load', function() {
        setTimeout(dismissWelcomeBanner, 100);
      });
      
      // Final fallback after 1 second
      setTimeout(dismissWelcomeBanner, 1000);
    })();
  </script>
</body>
</html>
