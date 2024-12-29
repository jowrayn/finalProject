<?php

                             // Database credentials
$host = 'localhost';         // Usually 'localhost'
$dbname = 'qr_database';     // The name of the database you created in phpMyAdmin
$username = 'root';          // Change if necessary (default for XAMPP/MAMP is 'root')
$password = '';              // Change if necessary (default for XAMPP/MAMP is empty)

     // Create a connection to the database
$conn = new mysqli($host, $username, $password, $dbname);

    // Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

    // Check if QR data is sent via POST
if (isset($_POST['qr_data'])) {
        // Sanitize the input
    $qrData = $conn->real_escape_string($_POST['qr_data']);

     // Insert the QR data into the database
    $sql = "INSERT INTO qr_codes (qr_data) VALUES ('$qrData')";

    if ($conn->query($sql) === TRUE) {
        echo "QR Code saved successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

    // Close the database connection
$conn->close();
?>