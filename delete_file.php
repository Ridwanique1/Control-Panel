<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Define the target directory
$targetDir = "C:/xampp/htdocs/";

// Check if the file to delete is specified
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    $filePath = $targetDir . $file;

    // Check if file exists
    if (file_exists($filePath)) {
        unlink($filePath); // Delete the file
        echo "File deleted successfully.";
    } else {
        echo "File not found.";
    }
} else {
    echo "No file specified.";
}

// Redirect to the file manager page
header('Location: file_manager.php');
exit;
