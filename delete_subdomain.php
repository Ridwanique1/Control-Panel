<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['subdomain'])) {
    $subdomain = $_GET['subdomain'];

    // Simulate deletion (in real-world, you would remove the subdomain record from DNS provider)
    $deleted = true; // Simulate success

    if ($deleted) {
        echo "Subdomain '$subdomain' has been deleted.";
    } else {
        echo "Failed to delete subdomain. Please try again.";
    }
}
?>
