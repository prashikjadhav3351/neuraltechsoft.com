<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection details
    $servername = "server167.web-hosting.com";
    $username = "neurgcxt_neural";
    $password = "Neural@123$";
    $dbname = "neurgcxt_nts2024";

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die('Database connection failed.');
    }

    // Get form data and sanitize inputs
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);

    // Handle file upload
    if (isset($_FILES['resume'])) {
        $uploadErrorCode = $_FILES['resume']['error'];
        $uploadDir = 'uploads/DAintern/'; // Relative path for public access

        if ($uploadErrorCode === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['resume']['tmp_name'];
            $fileName = $_FILES['resume']['name'];

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate unique file name
            $fileBaseName = pathinfo($fileName, PATHINFO_FILENAME);
            $timestamp = date('Y-m-d_His');
            $uniqueFileName = $fileBaseName . '_' . $timestamp . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
            $fileDest = $uploadDir . $uniqueFileName;
            $serverFilePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $fileDest;

            // Move the uploaded file to the directory
            if (move_uploaded_file($fileTmpPath, $serverFilePath)) {
                // Full public URL for the uploaded file
                $fileURL = 'http://' . $_SERVER['HTTP_HOST'] . '/' . $fileDest;

                // Insert form data into the database using prepared statements
                $stmt = $conn->prepare("INSERT INTO DAintern (name, email, phone, resume) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $name, $email, $phone, $fileURL);

                if ($stmt->execute()) {
                    // Send email notification
                    $to = "prashikjadhav90031@gmail.com , info@neuraltechsoft.com , mahendramehta@neuraltechsoft.com"; // Replace with your email
                    $subject = "New Form Submission with Resume";

                    $message = "You have received a new Data Analyst Intern form submission:\n\n";
                    $message .= "Name: $name\n";
                    $message .= "Email: $email\n";
                    $message .= "Phone: $phone\n";
                    $message .= "Resume: $fileURL\n";

                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-Type: text/plain; charset=UTF-8" . "\r\n";

                    mail($to, $subject, $message, $headers);

                    // Send a success response back to the AJAX call
                    echo 'success';
                } else {
                    echo 'Error inserting data into the database.';
                }

                $stmt->close();
            } else {
                echo 'Error moving uploaded file.';
            }
        } else {
            echo 'File upload error.';
        }
    } else {
        echo 'Error with file upload.';
    }

    // Close the connection
    $conn->close();
}
?>
