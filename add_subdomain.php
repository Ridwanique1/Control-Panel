<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['subdomain'])) {
    // Get the submitted subdomain name
    $subdomain = $_POST['subdomain'];

    // Add subdomain to the DNS (this step is simulated, in a real-world app, you'd interface with DNS provider)
    // In a real application, you would interact with DNS API here

    // Simulate success
    $success = true;
    if ($success) {
        echo "Subdomain '$subdomain' has been added successfully!";
    } else {
        echo "Failed to add subdomain. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subdomain - Ridwanique Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Add Subdomain</h1>
        <a href="domain_manager.php" class="btn btn-secondary mb-3">Back to Domain Manager</a>
    </div>
</body>
</html>
