<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ironix";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Get POST data - only email and password for login
$email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
$password_input = isset($_POST['password']) ? $conn->real_escape_string($_POST['password']) : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate that email and password are provided
    if (empty($email) || empty($password_input)) {
        $_SESSION['login_error'] = 'Please enter both email and password.';
        header("Location: admin_login.php");
        exit();
    }
    
    // First, check if this email and password belong to a regular user
    $checkUser = "SELECT * FROM users WHERE email='$email' AND password='$password_input'";
    $userResult = $conn->query($checkUser);
    
    if ($userResult && $userResult->num_rows > 0) {
        // User credentials detected on admin login page - show error
        $_SESSION['login_error'] = 'Error: Invalid name or password';
        header("Location: admin_login.php");
        exit();
    }
    
    // Check if admin exists with email and password in the admin table
    // This connects to the admin database table to verify credentials
    $sql = "SELECT * FROM admin WHERE email='$email' AND password='$password_input' AND password IS NOT NULL";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // Admin found in admin table, login successful
        $admin = $result->fetch_assoc();
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin_dashboard.php"); // Redirect to admin dashboard
        exit();
    } else {
        // Admin not found or incorrect password
        $_SESSION['login_error'] = 'Invalid email or password. Please check your credentials and try again. Make sure you have created an admin account first.';
        header("Location: admin_login.php");
        exit();
    }
} else {
    header("Location: admin_login.php");
    exit();
}
?>

