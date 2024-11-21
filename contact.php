<?php
// Database connection settings
$servername = "server167.web-hosting.com";
$username = "neurgcxt_neural";
$password = "Neural@123$";
$dbname = "neurgcxt_nts2024";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $services = mysqli_real_escape_string($conn, $_POST['services']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Insert data into the SQL table
    $sql = "INSERT INTO contact_us (name, email, phone, services, message) 
            VALUES ('$name', '$email', '$phone', '$services', '$message')";

    if ($conn->query($sql) === TRUE) {
        // Send email to your email address
        $to = "prashikjadhav90031@gmail.com , info@neuraltechsoft.com , mahendramehta@neuraltechsoft.com "; // Replace with your email address
        $subject = "New Contact Us Form Submission";
        $email_body = "You have received a new message:\n\n" . 
                      "Name: $name\n" .
                      "Email: $email\n" .
                      "Phone: $phone\n" .
                      "Service: $services\n" .
                      "Message:\n$message\n";

        if (mail($to, $subject, $email_body)) {
            echo "Success";
        } else {
            echo "Mail could not be sent.";
        }
    } else {
        echo "Error inserting data: " . $conn->error;
    }
}

$conn->close();
?>
