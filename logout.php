<?php
session_start();

// Destroy the session and logout
session_destroy();

// Redirect to login page
header('Location: login.php');
exit;
?>
