<?php
session_start(); // Start the session

$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password is empty
$dbname = "project"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $gameName = $_POST['gameName'];
    $gameDescription = $_POST['gameDescription'];
    $filePaths = ''; // Initialize file paths

    // Handle file uploads
if (isset($_FILES['gameFiles'])) {
    $filePathsArray = [];
    
    // Ensure the uploads directory exists
    if (!is_dir('uploads')) {
        mkdir('uploads', 0755, true); // Create the directory with appropriate permissions
    }

    foreach ($_FILES['gameFiles']['tmp_name'] as $key => $tmpName) {
        $fileName = $_FILES['gameFiles']['name'][$key]; // Get the original file name
        $filePath = 'uploads/' . basename($fileName); // Define your upload directory

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4'];
        if (in_array($_FILES['gameFiles']['type'][$key], $allowedTypes)) {
            // Move the file to the upload directory
            if (move_uploaded_file($tmpName, $filePath)) {
                $filePathsArray[] = $filePath; // Add the file path to the array
            } else {
                echo "Error uploading file: " . htmlspecialchars($fileName);
            }
        } else {
            echo "Invalid file type: " . htmlspecialchars($fileName);
        }
    }
    $filePaths = implode(',', $filePathsArray); // Convert array to comma-separated string
}
    // Prepare and execute the SQL statement
    $sql = "INSERT INTO games (game_name, game_description, file_paths) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $gameName, $gameDescription, $filePaths);

    if ($stmt->execute()) {
        // Set a session variable for the success message
        $_SESSION['success_message'] = "The review has been shared successfully!";
        // Redirect back to the form page
        header("Location: RPG GAME.html"); // Change to your actual form page
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>