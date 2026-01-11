<?php
$success = false;
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ironix";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $image_url = $conn->real_escape_string($_POST['image_url']);

    $sql = "INSERT INTO products (name, description, price, image_url) VALUES ('$name', '$description', '$price', '$image_url')";
    if ($conn->query($sql) === TRUE) {
        $success = true;
    } else {
        $error = "Error: " . $conn->error;
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Product - Ironix Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
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
    }
    .add-product-container {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 8px 32px rgba(36,59,85,0.18);
      padding: 40px 32px 32px 32px;
      max-width: 420px;
      width: 100%;
      text-align: center;
      position: relative;
    }
    .add-product-container h2 {
      color: #243b55;
      font-weight: 700;
      margin-bottom: 18px;
      letter-spacing: 1px;
    }
    .add-product-container .brand {
      color: #f5a623;
      font-size: 2em;
      font-weight: 700;
      margin-bottom: 10px;
      letter-spacing: 2px;
    }
    .add-product-form {
      margin-top: 18px;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
    .add-product-form .input-group {
      display: flex;
      align-items: center;
      background: #f8f9fa;
      border-radius: 8px;
      padding: 8px 12px;
      border: 1.5px solid #e0e0e0;
      transition: border 0.3s;
    }
    .add-product-form .input-group:focus-within {
      border: 1.5px solid #f5a623;
    }
    .add-product-form .input-group i {
      color: #f5a623;
      margin-right: 10px;
      font-size: 1.1em;
    }
    .add-product-form input, .add-product-form textarea {
      border: none;
      background: transparent;
      outline: none;
      font-size: 1em;
      flex: 1;
      color: #243b55;
      padding: 8px 0;
      resize: none;
    }
    .add-product-form input::placeholder, .add-product-form textarea::placeholder {
      color: #b0b0b0;
    }
    .add-product-form button {
      background: linear-gradient(90deg, #f5a623 0%, #243b55 100%);
      color: #fff;
      border: none;
      border-radius: 25px;
      padding: 12px 0;
      font-size: 1.1em;
      font-weight: 600;
      cursor: pointer;
      margin-top: 10px;
      transition: background 0.3s, box-shadow 0.3s;
      box-shadow: 0 4px 12px rgba(245,166,35,0.15);
      letter-spacing: 1px;
    }
    .add-product-form button:hover {
      background: linear-gradient(90deg, #243b55 0%, #f5a623 100%);
      color: #243b55;
    }
    .success-message {
      color: #27ae60;
      font-weight: 600;
      margin-bottom: 10px;
    }
    .error-message {
      color: #e74c3c;
      font-weight: 600;
      margin-bottom: 10px;
    }
    .back-link {
      display: inline-block;
      margin-top: 18px;
      color: #f5a623;
      text-decoration: none;
      font-weight: 600;
      font-size: 1em;
    }
    .back-link:hover {
      text-decoration: underline;
    }
    @media (max-width: 500px) {
      .add-product-container {
        padding: 24px 8px;
      }
    }
  </style>
</head>
<body>
  <div class="add-product-container">
    <div class="brand"><i class="fa-solid fa-screwdriver-wrench"></i> Ironix Admin</div>
    <h2>Add New Product</h2>
    <?php if ($success): ?>
      <div class="success-message"><i class="fa-solid fa-circle-check"></i> Product added successfully!</div>
      <a href="admin_dashboard.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
    <?php elseif ($error): ?>
      <div class="error-message"><i class="fa-solid fa-circle-xmark"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form class="add-product-form" method="post" action="add_product.php">
      <div class="input-group">
        <i class="fa-solid fa-box"></i>
        <input type="text" name="name" placeholder="Product Name" required>
      </div>
      <div class="input-group">
        <i class="fa-solid fa-align-left"></i>
        <textarea name="description" placeholder="Description" rows="2" required></textarea>
      </div>
      <div class="input-group">
        <i class="fa-solid fa-dollar-sign"></i>
        <input type="number" name="price" placeholder="Price" step="0.01" min="0" required>
      </div>
      <div class="input-group">
        <i class="fa-solid fa-image"></i>
        <input type="url" name="image_url" placeholder="Image URL" required>
      </div>
      <button type="submit"><i class="fa-solid fa-plus"></i> Add Product</button>
    </form>
  </div>
</body>
</html>
