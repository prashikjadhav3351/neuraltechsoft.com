<?php
if (isset($_POST['submit'])) {
    // Database connection details
    $servername = "server167.web-hosting.com";
    $username = "neurgcxt_neural";
    $password = "Neural@123$";
    $dbname = "neurgcxt_nts2024";

    // Create a connection using MySQLi
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
        $fileTmpPath = $_FILES['resume']['tmp_name'];
        $fileName = basename($_FILES['resume']['name']);
        $uploadDir = 'uploads/';
        $uniqueFileName = time() . '_' . $fileName;
        $fileDest = $uploadDir . $uniqueFileName;

        if (move_uploaded_file($fileTmpPath, $fileDest)) {
            echo "Resume uploaded successfully. ";

            // Insert form data into the database using MySQLi
            $sql = "INSERT INTO contacts (name, email, phone, resume) VALUES ('$name', '$email', '$phone', '$fileDest')";
            if ($conn->query($sql) === TRUE) {
                echo "Data inserted successfully!";

                $to = "prashikjadhav90031@gmail.com, info@neuraltechsoft.com, mahendramehta@neuraltech.com";
                $subject = "New Form Submission with Resume";

                $message = "You have received a new form submission:\n\n";
                $message .= "Name: $name\n";
                $message .= "Email: $email\n";
                $message .= "Phone: $phone\n";
                $message .= "Resume: http://neuraltechsoft.com/" . $fileDest . "\n\n";

                if (mail($to, $subject, $message)) {
                    echo "Email sent successfully!";
                } else {
                    echo "Failed to send email.";
                }
            } else {
                echo "Error inserting data.";
            }
        } else {
            echo "Error uploading file.";
        }
    } else {
        echo "Please upload a resume.";
    }

    // Close connection
    $conn->close();
}
?>
