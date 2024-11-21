<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "server167.web-hosting.com";
$username = "neurgcxt_neural";
$password = "Neural@123$";
$dbname = "neurgcxt_nts2024";

// Create connection using mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$feedback = $_POST['feedback'] ?? '';

// Prepare and execute the SQL statement
$stmt = $conn->prepare("INSERT INTO feedback (name, email, feedback) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $feedback);

// Execute and check for errors
if ($stmt->execute()) {
    echo "Feedback submitted successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close the connection
$stmt->close();
$conn->close();
?>
