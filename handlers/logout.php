<?php
session_start(); // Start the session to access session variables

// Destroy the session to log the user out
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session

// Optionally, clear session-related cookies
setcookie(session_name(), '', time() - 3600, '/');

// Redirect to the login page or logout confirmation page
header("Location: http://localhost/dbms_project/SignUp_in_page.php"); 
exit;
?>
