<?php

error_reporting(E_ALL);
session_start();

// Database configuration
$host   = "localhost";
$dbname = "sign_db";
$dbuser = "root";    // Change as needed
$dbpass = "";        // Change as needed

// Create a connection
$conn = new mysqli($host, $dbuser, $dbpass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the form when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize inputs
    $username         = trim($_POST['username']);
    $email            = trim($_POST['email']);
    $password         = $_POST['password'];
   

    // Basic validation: Check required fields
 
   

    // Check if username or email already exists
    $stmt = $conn->prepare("SELECT email FROM sign WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        echo "Username or email already exists. Please choose another.";
        $stmt->close();
        exit;
    }
    $stmt->close();

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user into the database
    $stmt = $conn->prepare("INSERT INTO sign (username,email , password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss",  $username,$email,$hashed_password);

    if ($stmt->execute()) {
        $_SESSION['username'] = $username;
        header("Location: Sign up.html");
         ($conn->query($sql) === TRUE) ;
            echo "New record created successfully";
        
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}

$conn->close();
?>
