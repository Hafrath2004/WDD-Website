<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Ironix Hardware Shop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Google Fonts & Font Awesome -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    body {
      background: linear-gradient(135deg, #243b55 0%, #f5a623 100%);
      min-height: 100vh;
      font-family: 'Poppins', Arial, sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .register-container {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 8px 32px rgba(36,59,85,0.18);
      padding: 40px 32px 32px 32px;
      max-width: 450px;
      width: 100%;
      text-align: center;
      position: relative;
    }
    .register-container h2 {
      color: #243b55;
      font-weight: 700;
      margin-bottom: 18px;
      letter-spacing: 1px;
    }
    .register-container .brand {
      color: #f5a623;
      font-size: 2em;
      font-weight: 700;
      margin-bottom: 10px;
      letter-spacing: 2px;
    }
    .register-form {
      margin-top: 24px;
      display: flex;
      flex-direction: column;
      gap: 18px;
      width: 100%;
      box-sizing: border-box;
    }
    .register-form .input-group {
      display: flex;
      align-items: center;
      background: #f8f9fa;
      border-radius: 8px;
      padding: 12px 16px;
      border: 1.5px solid #e0e0e0;
      transition: all 0.3s;
      width: 100%;
      box-sizing: border-box;
    }
    .register-form .input-group:focus-within {
      border: 1.5px solid #f5a623;
      background: #fff;
      box-shadow: 0 0 0 3px rgba(245,166,35,0.1);
    }
    .register-form .input-group i {
      color: #f5a623;
      margin-right: 10px;
      font-size: 1.1em;
      flex-shrink: 0;
    }
    .register-form input {
      border: none;
      background: transparent;
      outline: none;
      font-size: 1em;
      flex: 1;
      color: #243b55;
      padding: 4px 0;
      width: 100%;
      font-family: 'Poppins', Arial, sans-serif;
    }
    .register-form input::placeholder {
      color: #b0b0b0;
    }
    .register-form button {
      background: linear-gradient(90deg, #f5a623 0%, #243b55 100%);
      color: #fff;
      border: none;
      border-radius: 25px;
      padding: 14px 0;
      font-size: 1.1em;
      font-weight: 600;
      cursor: pointer;
      margin-top: 10px;
      transition: all 0.3s, box-shadow 0.3s;
      box-shadow: 0 4px 12px rgba(245,166,35,0.15);
      letter-spacing: 1px;
    }
    .register-form button:hover {
      background: linear-gradient(90deg, #243b55 0%, #f5a623 100%);
      transform: translateY(-2px);
      box-shadow: 0 6px 16px rgba(245,166,35,0.25);
    }
    .register-footer {
      margin-top: 24px;
      color: #888;
      font-size: 0.95em;
      text-align: center;
    }
    .register-footer a {
      color: #f5a623;
      font-weight: 600;
      text-decoration: none;
      transition: color 0.3s;
    }
    .register-footer a:hover {
      color: #f39c12;
      text-decoration: underline;
    }
    .error-message {
      background: #fee;
      color: #c33;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 18px;
      font-size: 0.9em;
      border: 1px solid #fcc;
    }
    .success-message {
      background: #efe;
      color: #3c3;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 18px;
      font-size: 0.9em;
      border: 1px solid #cfc;
    }
    @media (max-width: 500px) {
      .register-container {
        padding: 24px 16px;
      }
    }
  </style>
</head>
<body>
  <div class="register-container">
    <div class="brand"><i class="fa-solid fa-screwdriver-wrench"></i> Ironix</div>
    <h2>Create New Account</h2>
    
    <?php
    if (isset($_SESSION['registration_error'])) {
      echo '<div class="error-message">' . htmlspecialchars($_SESSION['registration_error']) . '</div>';
      unset($_SESSION['registration_error']);
    }
    if (isset($_SESSION['registration_success'])) {
      echo '<div class="success-message">' . htmlspecialchars($_SESSION['registration_success']) . '</div>';
      unset($_SESSION['registration_success']);
    }
    ?>
    
    <form class="register-form" method="post" action="registration.php">
      <div class="input-group">
        <i class="fa-solid fa-id-badge"></i>
        <input type="text" name="id" placeholder="User ID" required>
      </div>
      <div class="input-group">
        <i class="fa-solid fa-user"></i>
        <input type="text" name="name" placeholder="Full Name" required>
      </div>
      <div class="input-group">
        <i class="fa-solid fa-envelope"></i>
        <input type="email" name="email" placeholder="Email Address" required>
      </div>
      <div class="input-group">
        <i class="fa-solid fa-lock"></i>
        <input type="password" name="password" placeholder="Password" required minlength="6">
      </div>
      <div class="input-group">
        <i class="fa-solid fa-phone"></i>
        <input type="tel" name="phone" placeholder="Phone Number" required>
      </div>
      <div class="input-group">
        <i class="fa-solid fa-location-dot"></i>
        <input type="text" name="address" placeholder="Address" required>
      </div>
      <button type="submit">Create Account</button>
    </form>
    <div class="register-footer">
      Already have an account? <a href="login.php">Login</a>
    </div>
  </div>
</body>
</html>

