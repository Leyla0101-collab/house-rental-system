<?php
// Start session
session_start();

// Destroy the session to log out the user
session_unset();  // Remove all session variables
session_destroy();  // Destroy the session

// Redirect to index.php after logging out
header('Location: index.php');
exit;
?>
