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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Settings Dashboard - Ironix Admin</title>
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
      background: var(--main-gold);
      color: #fff;
    }
    .profile-dropdown.show .profile-menu {
      display: block;
    }
    /* Table */
    .dashboard-table-container {
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 16px rgba(36,59,85,0.07);
      padding: 24px 20px;
      margin-top: 12px;
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

    /* New Settings Page Styles */
    .settings-content {
        display: flex;
        gap: 30px;
        margin-top: 20px; /* Adjust based on header spacing */
        flex-wrap: wrap; /* Allow wrapping on smaller screens */
    }

    .settings-sidebar {
        width: 280px; /* Adjust width as needed */
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 8px 30px rgba(36,59,85,0.08);
        padding: 25px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .settings-sidebar .profile-section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
        width: 100%;
    }

    .settings-sidebar .profile-section img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
        border: 3px solid var(--main-gold);
    }

    .settings-sidebar .profile-section .name {
        font-size: 1.4em;
        font-weight: 600;
        color: var(--main-blue);
        margin-bottom: 5px;
    }

    .settings-sidebar .profile-section .handle {
        font-size: 0.9em;
        color: #888;
    }

    .settings-sidebar .settings-nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
        width: 100%;
        text-align: left;
    }

    .settings-sidebar .settings-nav ul li {
        margin-bottom: 8px;
    }

    .settings-sidebar .settings-nav ul li a {
        display: block;
        padding: 12px 15px;
        text-decoration: none;
        color: var(--main-blue);
        font-size: 1em;
        border-radius: 8px;
        transition: background 0.2s;
    }

    .settings-sidebar .settings-nav ul li a.active,
    .settings-sidebar .settings-nav ul li a:hover {
        background: var(--main-bg);
        color: var(--main-gold);
    }

    .settings-main {
        flex: 1; /* Take up remaining space */
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 8px 30px rgba(36,59,85,0.08);
        padding: 30px;
        min-width: 300px; /* Ensure it doesn't get too small */
    }

    .settings-main h3 {
        font-size: 1.6em;
        font-weight: 700;
        color: var(--main-blue);
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .settings-form .form-group {
        margin-bottom: 20px;
    }

    .settings-form label {
        display: block;
        font-size: 0.9em;
        color: #555;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .settings-form .input-row {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .settings-form .input-row .input-group {
        flex: 1; /* Distribute space */
        min-width: 200px; /* Prevent shrinking too much */
        display: flex;
        align-items: center;
        background: #f8f9fa;
        border-radius: 8px;
        padding: 10px 15px;
        border: 1px solid #e0e0e0;
        transition: border-color 0.3s ease-in-out;
    }

    .settings-form .input-row .input-group:focus-within {
        border-color: var(--main-gold);
        box-shadow: 0 0 5px rgba(245, 166, 35, 0.3);
    }

    .settings-form .input-row .input-group i {
        color: var(--main-blue);
        margin-right: 10px;
        font-size: 1.1em;
    }

    .settings-form input:not([type="checkbox"]), .settings-form textarea, .settings-form select {
        border: none;
        background: transparent;
        outline: none;
        font-size: 1em;
        flex: 1;
        color: #333;
        padding: 0;
        width: 100%; /* Ensure inputs fill their containers */
    }

     .settings-form textarea {
        width: 100%;
        min-height: 100px;
        padding: 10px 15px;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        transition: border-color 0.3s ease-in-out;
        box-sizing: border-box;
     }

    .settings-form textarea:focus {
        border-color: var(--main-gold);
        box-shadow: 0 0 5px rgba(245, 166, 35, 0.3);
    }

    .settings-actions {
        margin-top: 30px;
        display: flex;
        gap: 15px;
    }

    .settings-actions button {
        padding: 12px 25px;
        border: none;
        border-radius: 25px;
        font-size: 1em;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease-in-out;
    }

    .settings-actions .update-btn {
        background: linear-gradient(90deg, var(--main-gold) 0%, var(--main-blue) 100%);
        color: #fff;
        box-shadow: 0 4px 12px rgba(245,166,35,0.2);
    }

    .settings-actions .update-btn:hover {
        background: linear-gradient(90deg, var(--main-blue) 0%, var(--main-gold) 100%);
        box-shadow: 0 4px 15px rgba(36,59,85,0.3);
        transform: translateY(-1px);
    }

     .settings-actions .cancel-btn {
        background: #e0e0e0;
        color: #555;
     }

     .settings-actions .cancel-btn:hover {
        background: #d5d5d5;
        transform: translateY(-1px);
     }

     /* Responsive adjustments */
    @media (max-width: 768px) {
        .settings-content {
            flex-direction: column;
            gap: 20px;
        }
        .settings-sidebar {
            width: 100%;
            flex-direction: row;
            justify-content: space-around;
            align-items: center;
            padding: 20px;
        }
        .settings-sidebar .profile-section {
             margin-bottom: 0;
             padding-bottom: 0;
             border-bottom: none;
             border-right: 1px solid #eee;
             padding-right: 20px;
             margin-right: 20px;
             width: auto;
        }
        .settings-sidebar .profile-section img {
            width: 80px;
            height: 80px;
            margin-bottom: 10px;
        }
         .settings-sidebar .settings-nav ul {
             text-align: center;
         }
         .settings-sidebar .settings-nav ul li {
             display: inline-block;
             margin-right: 15px;
         }
         .settings-sidebar .settings-nav ul li:last-child {
             margin-right: 0;
         }
          .settings-sidebar .settings-nav ul li a {
             padding: 8px 12px;
          }

        .settings-main {
            padding: 20px;
        }

        .settings-form .input-row {
            flex-direction: column;
            gap: 15px;
        }
         .settings-form .input-row .input-group {
            min-width: unset;
         }
    }

    @media (max-width: 500px) {
        .settings-sidebar {
            flex-direction: column;
        }
        .settings-sidebar .profile-section {
            border-right: none;
            padding-right: 0;
            margin-right: 0;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            width: 100%;
        }
         .settings-sidebar .settings-nav ul li {
             display: block;
             margin-right: 0;
         }
         .settings-sidebar .settings-nav ul li a {
             padding: 10px 15px;
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
        <li><a href="admin_dashboard.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
        <li><a href="admin_products.php"><i class="fa-solid fa-box"></i> Products</a></li>
        <li><a href="admin_customers.php"><i class="fa-solid fa-users"></i> Customers</a></li>
        <li><a href="admin_suppliers.php"><i class="fa-solid fa-truck"></i> Suppliers</a></li>
        <li><a href="admin_orders.php"><i class="fa-solid fa-shopping-cart"></i> Orders</a></li>
        <li><a href="admin_inventory.php"><i class="fa-solid fa-warehouse"></i> Inventory</a></li>
        <li><a href="admin_admins.php"><i class="fa-solid fa-user-gear"></i> Admins</a></li>
        <li><a href="admin_settings.php" class="active"><i class="fa-solid fa-gear"></i> Settings</a></li>
        <li><a href="#" id="logout-link"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
      </ul>
      <div class="sidebar-footer">
        &copy; 2025 Ironix Hardware Shop
      </div>
    </nav>
    <!-- Main Content -->
    <div class="main-content">
      <div class="dashboard-header">
        <div class="welcome">Settings <span style="font-size:1.2em;">⚙️</span></div>
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

      <!-- Settings Content -->
      <div class="settings-content">
          <div class="settings-sidebar">
              <div class="profile-section">
                  <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Admin Profile">
                  <div class="name"><?php echo isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Admin User'; ?></div>
                  <div class="handle"><?php echo isset($_SESSION['admin_user_handle']) ? htmlspecialchars($_SESSION['admin_user_handle']) : '@adminuser'; ?></div> <!-- Placeholder handle -->
              </div>
              <nav class="settings-nav">
                  <ul>
                      <li><a href="#account-settings" class="active">Account Settings</a></li>
                      <li><a href="#password-settings">Password</a></li>
                      <li><a href="#security-privacy-settings">Security & Privacy</a></li>
                      <li><a href="#application-settings">Application</a></li>
                      <li><a href="#notification-settings">Notification</a></li>
                  </ul>
              </nav>
          </div>
          <div class="settings-main">
              <div id="account-settings-content" class="settings-section">
                  <h3>Account Settings</h3>
                  <form class="settings-form">
                      <div class="form-group">
                          <div class="input-row">
                              <div class="input-group">
                                  <i class="fa-solid fa-user"></i>
                                  <input type="text" placeholder="Steph" value="<?php echo isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : ''; ?>">
                              </div>
                               <div class="input-group">
                                  <input type="text" placeholder="Crown" value="">
                              </div>
                          </div>
                      </div>
                       <div class="form-group">
                          <div class="input-row">
                              <div class="input-group">
                                  <i class="fa-solid fa-envelope"></i>
                                  <input type="email" placeholder="ptenphotoshop@gmail.com" value="<?php echo isset($_SESSION['admin_email']) ? htmlspecialchars($_SESSION['admin_email']) : ''; ?>">
                              </div>
                               <div class="input-group">
                                  <i class="fa-solid fa-phone"></i>
                                  <input type="text" placeholder="9988 999 999" value="">
                              </div>
                          </div>
                      </div>
                       <div class="form-group">
                          <div class="input-row">
                              <div class="input-group">
                                   <i class="fa-solid fa-building"></i>
                                  <input type="text" placeholder="Pten Studio, Pune" value="">
                              </div>
                               <div class="input-group">
                                   <i class="fa-solid fa-location-dot"></i>
                                  <input type="text" placeholder="Pune" value="">
                                   <button type="button" style="background:none; border:none; color:var(--main-blue); cursor:pointer; font-size:0.9em;">Change</button>
                              </div>
                          </div>
                      </div>
                       <div class="form-group">
                            <label for="bio">Bio</label>
                            <textarea id="bio" placeholder="Purus est tempor, scelerisque arcu, ornare..."></textarea>
                       </div>
                      <div class="settings-actions">
                          <button type="submit" class="update-btn">Update</button>
                          <button type="button" class="cancel-btn">Cancel</button>
                      </div>
                  </form>
              </div>

              <div id="password-settings-content" class="settings-section" style="display: none;">
                  <h3>Password Settings</h3>
                  <form class="settings-form">
                      <div class="form-group">
                          <label for="current-password">Current Password</label>
                          <div class="input-group">
                              <i class="fa-solid fa-lock"></i>
                              <input type="password" id="current-password" placeholder="Enter current password">
                          </div>
                      </div>
                      <div class="form-group">
                          <label for="new-password">New Password</label>
                          <div class="input-group">
                              <i class="fa-solid fa-lock"></i>
                              <input type="password" id="new-password" placeholder="Enter new password">
                          </div>
                      </div>
                       <div class="form-group">
                          <label for="confirm-password">Confirm New Password</label>
                          <div class="input-group">
                              <i class="fa-solid fa-lock"></i>
                              <input type="password" id="confirm-password" placeholder="Confirm new password">
                          </div>
                      </div>
                      <div class="settings-actions">
                          <button type="submit" class="update-btn">Change Password</button>
                          <button type="button" class="cancel-btn">Cancel</button>
                      </div>
                  </form>
              </div>

              <div id="security-privacy-settings-content" class="settings-section" style="display: none;">
                  <h3>Security & Privacy</h3>
                   <form class="settings-form">
                       <div class="form-group">
                           <p>Manage your account's security and privacy settings here.</p>
                           <h4>Two-Factor Authentication</h4>
                           <p>Enhance your account security by enabling 2FA.</p>
                           <button type="button" class="update-btn" style="width:auto;">Enable 2FA</button>
                       </div>
                       <div class="form-group">
                           <h4>Login Activity</h4>
                           <p>Review recent login sessions.</p>
                           <button type="button" class="cancel-btn" style="width:auto;">View Activity Log</button>
                       </div>
                       <div class="form-group">
                           <h4>Privacy Settings</h4>
                           <p>Control how your information is used.</p>
                           <div class="input-group" style="background:none; border:none; padding:0;">
                               <input type="checkbox" id="privacy-opt-in" style="width:auto; margin-right:10px;">
                               <label for="privacy-opt-in" style="margin-bottom:0;">Opt-in to personalized features</label>
                           </div>
                       </div>
                       <div class="settings-actions">
                           <button type="submit" class="update-btn">Save Security Settings</button>
                           <button type="button" class="cancel-btn">Cancel</button>
                       </div>
                   </form>
              </div>

              <div id="application-settings-content" class="settings-section" style="display: none;">
                  <h3>Application Settings</h3>
                  <form class="settings-form">
                      <div class="form-group">
                          <p>Configure application-specific settings.</p>
                          <h4>Theme</h4>
                          <div class="input-group">
                              <i class="fa-solid fa-paint-brush"></i>
                              <select>
                                  <option value="light">Light Theme</option>
                                  <option value="dark">Dark Theme</option>
                              </select>
                          </div>
                      </div>
                       <div class="form-group">
                          <h4>Language</h4>
                          <div class="input-group">
                              <i class="fa-solid fa-language"></i>
                              <select>
                                  <option value="en">English</option>
                                  <option value="es">Spanish</option>
                              </select>
                          </div>
                      </div>
                      <div class="settings-actions">
                           <button type="submit" class="update-btn">Save Application Settings</button>
                           <button type="button" class="cancel-btn">Cancel</button>
                       </div>
                  </form>
              </div>

              <div id="notification-settings-content" class="settings-section" style="display: none;">
                  <h3>Notification Settings</h3>
                   <form class="settings-form">
                       <div class="form-group">
                           <p>Manage your notification preferences.</p>
                           <h4>Email Notifications</h4>
                           <div class="input-group" style="background:none; border:none; padding:0;">
                               <input type="checkbox" id="email-notifications" style="width:auto; margin-right:10px;" checked>
                               <label for="email-notifications" style="margin-bottom:0;">Receive email notifications</label>
                           </div>
                       </div>
                        <div class="form-group">
                           <h4>Push Notifications</h4>
                           <div class="input-group" style="background:none; border:none; padding:0;">
                               <input type="checkbox" id="push-notifications" style="width:auto; margin-right:10px;">
                               <label for="push-notifications" style="margin-bottom:0;">Receive push notifications (if applicable)</label>
                           </div>
                       </div>
                        <div class="form-group">
                           <h4>Notification Frequency</h4>
                           <div class="input-group">
                               <i class="fa-solid fa-clock"></i>
                               <select>
                                   <option value="instant">Instant</option>
                                   <option value="daily">Daily Digest</option>
                                   <option value="weekly">Weekly Summary</option>
                               </select>
                           </div>
                       </div>
                       <div class="settings-actions">
                           <button type="submit" class="update-btn">Save Notification Settings</button>
                           <button type="button" class="cancel-btn">Cancel</button>
                       </div>
                   </form>
              </div>

          </div>
      </div>

    </div>
  </div>
  <script>
    // Profile dropdown logic (copied for consistency)
    document.querySelector('.profile-btn').onclick = function(e) {
      e.stopPropagation();
      document.querySelector('.profile-dropdown').classList.toggle('show');
    };
    document.body.onclick = function() {
      document.querySelector('.profile-dropdown').classList.remove('show');
    };

    // Settings navigation logic
    const settingsLinks = document.querySelectorAll('.settings-nav a');
    const settingsSections = document.querySelectorAll('.settings-section');

    settingsLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);

            settingsLinks.forEach(navLink => navLink.classList.remove('active'));
            this.classList.add('active');

            settingsSections.forEach(section => section.style.display = 'none');
            document.getElementById(targetId + '-content').style.display = 'block';
        });
    });

    // Display default section on load
    document.getElementById('account-settings-content').style.display = 'block';

    // Logout confirmation logic
    const logoutLink = document.getElementById('logout-link');
    logoutLink.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent the default link behavior
        if (confirm('Are you sure you want to logout?')) {
            window.location.href = 'admin_logout.php'; // Redirect to logout script if confirmed
        }
    });

  </script>
</body>
</html> 