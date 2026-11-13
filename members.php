<?php
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

// Fetch data from the database
$sql = "SELECT Name, `E-mail` FROM data";
$result = $conn->query($sql);

// Prepare an array to hold member data
$members = [];

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        $members[] = $row; // Add each member to the array
    }
}

// Close the database connection
$conn->close();

// Return the member data as JSON
header('Content-Type: application/json');
echo json_encode($members);
?>