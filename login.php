<?php
$servername = "localhost";
$username = "root";
$password = "Cosmicenergy24@";
$dbname = "banksystem";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Unable to connect to database"); // Hide specific error details from users
}

// Get form data
$user = $_POST['username'];
$pass = $_POST['password'];

// Prepare and bind
$stmt = $conn->prepare("SELECT * FROM login_details WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $user, $pass); // 'ss' specifies the variable types are strings

// Execute the statement
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Redirect to main.html if login is successful
    header('Location: main.html');
    exit;
} else {
    echo "Invalid username or password";
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
