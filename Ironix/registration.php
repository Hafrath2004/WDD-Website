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
    $user_id_input = isset($_POST['id']) ? $conn->real_escape_string($_POST['id']) : '';
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password_input = isset($_POST['password']) ? $conn->real_escape_string($_POST['password']) : '';
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);
    
    // Check if email already exists
    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($checkEmail);
    
    // Response data array (for AJAX support)
    $response = [];
    
    if ($result->num_rows > 0) {
        // Email already exists
        $response['success'] = false;
        $response['message'] = "Email already registered. Please use a different email.";
    } else {
        // Check if User ID is provided and if it already exists
        $idExists = false;
        if (!empty($user_id_input)) {
            $checkId = "SELECT * FROM users WHERE id = '$user_id_input'";
            $idResult = $conn->query($checkId);
            if ($idResult && $idResult->num_rows > 0) {
                $response['success'] = false;
                $response['message'] = "User ID already exists. Please use a different User ID.";
                $idExists = true;
            }
        }
        
        if (!$idExists) {
            // Insert new user into database
            if (!empty($user_id_input)) {
                // Insert with specified ID
                $sql = "INSERT INTO users (id, name, email, password, phone, address) VALUES ('$user_id_input', '$name', '$email', '$password_input', '$phone', '$address')";
                $newUserId = $user_id_input;
            } else {
                // Let database auto-generate ID
                $sql = "INSERT INTO users (name, email, password, phone, address) VALUES ('$name', '$email', '$password_input', '$phone', '$address')";
            }
            
            if ($conn->query($sql) === TRUE) {
                // Registration successful, get the new user ID
                if (empty($user_id_input)) {
                    $newUserId = $conn->insert_id;
                }
                
                // Store user data in session (auto-login after registration)
                $_SESSION['user_id'] = $newUserId;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['logged_in'] = true;
                
                $response['success'] = true;
                $response['message'] = "Registration successful! Your user ID is: " . $newUserId;
                $response['redirect'] = "index.php";
            } else {
                // Registration failed
                $response['success'] = false;
                $response['message'] = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }
    
    // If it's an AJAX request, return JSON response
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } else {
        // For traditional form submission, redirect based on registration result
        if ($response['success']) {
            $_SESSION['registration_success'] = $response['message'];
            header("Location: " . $response['redirect']);
            exit;
        } else {
            // Store error message in session and redirect back to registration page
            $_SESSION['registration_error'] = $response['message'];
            header("Location: register.php");
            exit;
        }
    }
}

// If not a POST request, redirect to registration page
header("Location: register.php");
exit;
?>