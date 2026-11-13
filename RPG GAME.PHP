<?php
session_start(); // Start the session

error_reporting(E_ALL);
ini_set('display_errors', 1);

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

// Fetch saved game reviews
$sql = "SELECT game_name, game_description, file_paths FROM games";
$result = $conn->query($sql);

// Check if the query was successful
if ($result === false) {
    die("Error executing query: " . $conn->error);
}

// Check if there are any reviews
if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<div class='review'>";
        echo "<h3>" . htmlspecialchars($row['game_name']) . "</h3>";
        echo "<p>" . htmlspecialchars($row['game_description']) . "</p>";
        if (!empty($row['file_paths'])) {
            $files = explode(',', $row['file_paths']);
            foreach ($files as $file) {
                // Check if the file is an image or video
                $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
                if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    echo "<img src='" . htmlspecialchars($file) . "' alt='Game Image'>";
                } elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg'])) {
                    echo "<video controls><source src='" . htmlspecialchars($file) . "' type='video/" . $fileExtension . "'>Your browser does not support the video tag.</video>";
                }
            }
        }
        echo "</div>";
    }
} else {
    echo "<p>No reviews found.</p>";
}

// Close the database connection
$conn->close();
?>