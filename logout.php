<?php
session_start();
session_unset();  // Clear all session variables
session_destroy(); // Destroy the session

// Redirect to login/signup page
header("Location: landingpage.html");
exit();
?>
