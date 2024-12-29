<?php
// Database configuration
$host = 'localhost';
$dbname = 'user_login';
$username = 'root';
$password = '';

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get the form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validate inputs
    if (!empty($username) && !empty($password)) {
        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Insert the user into the database
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->execute();

            // Success message
            echo "Sign-up successful! <a href='index.php'>Log in here</a>";
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                // Duplicate entry error (e.g., username already exists)
                echo "Error: Username already exists.";
            } else {
                echo "Error: " . $e->getMessage();
            }
        }
    } else {
        echo "Please fill in all fields.";
    }
}
?>