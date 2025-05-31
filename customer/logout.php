<?php
session_start();
session_unset();     // Remove all session variables
session_destroy();   // Destroy the session

include 'paths.php'; // Load the path after session destruction

header("Location: $homePath"); // Redirect to home.php (or whatever $homePath is)
exit();
?>
