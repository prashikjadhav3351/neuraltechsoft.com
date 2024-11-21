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
        echo 'error'; // Return 'error' response if connection fails
        exit;
    }

    // Get form data and sanitize inputs
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);

    // File upload validation
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['resume']['tmp_name'];
        $fileName = basename($_FILES['resume']['name']);
        $uploadDir = 'uploads/QA/'; // Relative path for public access

        // Ensure the upload directory exists
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                echo 'error'; // Return 'error' response if directory creation fails
                exit;
            }
        }

        // Generate a unique file name
        $uniqueFileName = time() . '_' . $fileName;
        $fileDest = $uploadDir . $uniqueFileName;

        // Full server path for moving the file
        $serverFilePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $fileDest;

        // Move the uploaded file to the directory
        if (move_uploaded_file($fileTmpPath, $serverFilePath)) {
            // Use prepared statement to insert data
            $stmt = $conn->prepare("INSERT INTO QA (name, email, phone, resume) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $phone, $fileDest);
            
            if ($stmt->execute()) {
                // Send email notification (optional)
                $to = "prashikjadhav90031@gmail.com , info@neuraltechsoft.com , mahendramehta@neuraltechsoft.com"; // Replace with your email
                $subject = "New Form Submission with Resume";
                $fileURL = 'http://' . $_SERVER['HTTP_HOST'] . '/uploads/QA/' . urlencode($uniqueFileName);

                $message = "You have received a new Quantitative Analyst form submission:\n\n";
                $message .= "Name: $name\n";
                $message .= "Email: $email\n";
                $message .= "Phone: $phone\n";
                $message .= "Resume: $fileURL\n\n";

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-Type: text/plain; charset=ISO-8859-1" . "\r\n";

                if (mail($to, $subject, $message, $headers)) {
                    echo 'success'; // Return success response to AJAX
                } else {
                    echo 'error'; // Return error response if email fails
                }
            } else {
                echo 'error'; // Return error response if database insertion fails
            }

            $stmt->close();
        } else {
            echo 'error'; // Return error if file upload fails
        }
    } else {
        echo 'error'; // Return error if file upload is missing
    }

    // Close the connection
    $conn->close();
}
?>
