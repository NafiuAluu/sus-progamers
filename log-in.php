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

// Start the session
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user input from the form
    $email = trim($_POST['email']); // Assuming you are using email for login
    $entered_password = trim($_POST['password']); // Trim whitespace

    // Prepare and bind
    $stmt = $conn->prepare("SELECT Password FROM data WHERE `E-mail` = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_password_from_db);
        $stmt->fetch();

        // Verify the password
        if (password_verify($entered_password, $hashed_password_from_db)) {
            // Password is correct, set session variables
            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $email;

            // Redirect to welcome page or dashboard
            header("location: Project-2.html");
            exit();
        } else {
            // Invalid password
            echo "Invalid password. Please try again.";
        }
    } else {
        // No account found with that email address
        echo "No account found with that email address.";
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
