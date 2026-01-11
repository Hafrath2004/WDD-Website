<?php
// Start session
session_start();

// Database connection
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

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $admin_id_input = isset($_POST['id']) ? $conn->real_escape_string($_POST['id']) : '';
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password_input = isset($_POST['password']) ? $conn->real_escape_string($_POST['password']) : '';
    $gender = $conn->real_escape_string($_POST['gender']);
    
    // Check if email already exists
    $checkEmail = "SELECT * FROM admin WHERE email = '$email'";
    $result = $conn->query($checkEmail);
    
    // Response data array
    $response = [];
    
    if ($result->num_rows > 0) {
        // Email already exists
        $response['success'] = false;
        $response['message'] = "Email already registered. Please use a different email.";
    } else {
        // Check if Admin ID is provided and if it already exists
        $idExists = false;
        if (!empty($admin_id_input)) {
            $checkId = "SELECT * FROM admin WHERE id = '$admin_id_input'";
            $idResult = $conn->query($checkId);
            if ($idResult && $idResult->num_rows > 0) {
                $response['success'] = false;
                $response['message'] = "Admin ID already exists. Please use a different Admin ID.";
                $idExists = true;
            }
        }
        
        if (!$idExists) {
            // Insert new admin into database
            if (!empty($admin_id_input)) {
                // Insert with specified ID
                $sql = "INSERT INTO admin (id, name, email, password, gender) VALUES ('$admin_id_input', '$name', '$email', '$password_input', '$gender')";
                $newAdminId = $admin_id_input;
            } else {
                // Let database auto-generate ID
                $sql = "INSERT INTO admin (name, email, password, gender) VALUES ('$name', '$email', '$password_input', '$gender')";
            }
            
            if ($conn->query($sql) === TRUE) {
                // Registration successful, get the new admin ID
                if (empty($admin_id_input)) {
                    $newAdminId = $conn->insert_id;
                }
                
                // Store admin data in session (auto-login after registration)
                $_SESSION['admin_id'] = $newAdminId;
                $_SESSION['admin_name'] = $name;
                $_SESSION['admin_email'] = $email;
                $_SESSION['admin_logged_in'] = true;
                
                $response['success'] = true;
                $response['message'] = "Admin registration successful! Your admin ID is: " . $newAdminId;
                $response['redirect'] = "admin_dashboard.php";
            } else {
                // Registration failed
                $response['success'] = false;
                $response['message'] = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    
    // For traditional form submission, redirect based on registration result
    if ($response['success']) {
        $_SESSION['admin_registration_success'] = $response['message'];
        header("Location: " . $response['redirect']);
        exit;
    } else {
        // Store error message in session and redirect back to registration page
        $_SESSION['admin_registration_error'] = $response['message'];
        header("Location: admin_register.php");
        exit;
    }
}

// If not a POST request, redirect to registration page
header("Location: admin_register.php");
exit;
?>

