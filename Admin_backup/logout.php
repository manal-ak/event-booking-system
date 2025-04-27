<?php
session_start();
session_unset(); // Clear all session variables
session_destroy(); // Destroy the session

header("Location: admin.php"); // Redirect back to login page
exit();
?>
