<?php
// Database connection setup
$servername = "server167.web-hosting.com";
$username = "neurgcxt_neural";
$password = "Neural@123$";
$dbname = "neurgcxt_nts2024";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Include PHPMailer
require 'public_html/PHPMailer/PHPMailerAutoload.php';

// Collect form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

// File upload logic
$targetDir = "uploads/";
$targetFile = $targetDir . basename($_FILES["resume"]["name"]);
move_uploaded_file($_FILES["resume"]["tmp_name"], $targetFile);

// Insert form data into the database
$sql = "INSERT INTO users (name, email, phone, resume) VALUES ('$name', '$email', '$phone', '$targetFile')";
if ($conn->query($sql) === TRUE) {
    // Data inserted successfully
    // Send email to user

    $mail = new PHPMailer;
    $mail->isSMTP();
    $mail->Host = 'smtp.neuraltechsoft.com';  // SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = 'prashikjadhav90031@gmail.com';  // Your email address
    $mail->Password = 'pedl exxn puoi tixa';  // Your email password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Recipients
    $mail->setFrom('prashikjadhav@gmail.com', 'Your Company');
    $mail->addAddress($email, $name);  // Send email to the user

    // Attach the resume file
    $mail->addAttachment($targetFile, basename($targetFile));  // Attach the resume

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Form Submission Confirmation';
    $mail->Body    = "
        <p>Dear $name,</p>
        <p>Thank you for submitting the form. Below are your details:</p>
        <p>Name: $name</p>
        <p>Email: $email</p>
        <p>Phone: $phone</p>
        <p>Best regards,</p>
        <p>Your Company Name</p>
    ";

    // Send email
    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message has been sent';
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
