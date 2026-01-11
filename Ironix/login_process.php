<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ironix";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data - only email and password for login
$email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
$password_input = isset($_POST['password']) ? $conn->real_escape_string($_POST['password']) : '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate that email and password are provided
    if (empty($email) || empty($password_input)) {
        $_SESSION['login_error'] = 'Please enter both email and password.';
        header("Location: login.php");
        exit();
    }
    
    // First, check if this email and password belong to an admin
    $checkAdmin = "SELECT * FROM admin WHERE email='$email' AND password='$password_input'";
    $adminResult = $conn->query($checkAdmin);
    
    if ($adminResult && $adminResult->num_rows > 0) {
        // Admin credentials detected on user login page - show error
        $_SESSION['login_error'] = 'Error: Invalid user or email';
        header("Location: login.php");
        exit();
    }
    
    // Check if user exists with email and password in the users table
    // This connects to the users database table to verify credentials
    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password_input' AND password IS NOT NULL";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // User found in users table, login successful
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['logged_in'] = true;
        header("Location: index.php"); // Redirect to home page
        exit();
    } else {
        // User not found or incorrect password
        $_SESSION['login_error'] = 'Invalid email or password. Please check your credentials and try again. Make sure you have created an account first.';
        header("Location: login.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>

