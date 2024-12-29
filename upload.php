<?php
// Database connection details
$host = "localhost";
$db = "root";
$user = "";
$pass = "imageactivity";

// Create a database connection
$conn = new mysqli($host, $db, $user, $pass);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If the form is submitted, process the file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if a file was uploaded
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $targetDir = "uploads/";
        $fileName = basename($_FILES['file']['name']);
        $targetFilePath = $targetDir . $fileName;

        // Create the uploads directory if it doesn't exist
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Move the uploaded file to the server directory
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFilePath)) {
            // Insert file path into the database
            $stmt = $conn->prepare("INSERT INTO files (file_path) VALUES (?)");
            $stmt->bind_param("s", $targetFilePath);

            if ($stmt->execute()) {
                echo "<p>File uploaded and saved to the database successfully!</p>";
            } else {
                echo "<p>Failed to save file path to the database.</p>";
            }

            $stmt->close();
        } else {
            echo "<p>Failed to move uploaded file.</p>";
        }
    } else {
        echo "<p>Please upload a valid file.</p>";
    }
}

// Retrieve and display images
$result = $conn->query("SELECT file_path FROM files");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Upload a file</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="file" required>
        <button type="submit">Upload</button>
    </form>
    <h2>Uploaded Images</h2>
    <div>
        <?php
        if ($result->num_rows > 0) {
            // Output each image
            while ($row = $result->fetch_assoc()) {
                echo '<img src="' . $row['file_path'] . '" alt="Uploaded Image" style="max-width: 200px; margin: 10px;">';
            }
        } else {
            echo "<p>No images uploaded yet.</p>";
        }
        ?>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
