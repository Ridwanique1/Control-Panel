<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Simulate domain info (In real application, get it from a config or database)
$domain = 'ridwanique.local';

// Get existing subdomains (example)
$subdomains = ['blog.ridwanique.com.ng', 'shop.ridwanique.com.ng']; // In real implementation, fetch this data

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domain Management - Ridwanique Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Domain Management</h1>
        <h3>Domain: <?php echo $domain; ?></h3>
        
        <h4>Subdomains:</h4>
        <ul>
            <?php foreach ($subdomains as $subdomain): ?>
                <li><?php echo $subdomain; ?> <a href="delete_subdomain.php?subdomain=<?php echo urlencode($subdomain); ?>" class="btn btn-danger btn-sm">Delete</a></li>
            <?php endforeach; ?>
        </ul>

        <h4>Add New Subdomain:</h4>
        <form action="add_subdomain.php" method="POST">
            <div class="mb-3">
                <label for="subdomain" class="form-label">Subdomain Name</label>
                <input type="text" name="subdomain" id="subdomain" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Subdomain</button>
        </form>

        <hr>
        <a href="control_panel.php" class="btn btn-secondary">Back to Control Panel</a>
    </div>
</body>
</html>
