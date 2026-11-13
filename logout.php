<?php
session_start(); // Start the session

// Unset all of the session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to the login page or home page
header("Location: Project.html"); // Change this to your desired page
exit();
?>