<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch list of files in the htdocs directory
$uploadDir = "C:/xampp/htdocs/";
$files = array_diff(scandir($uploadDir), array('.', '..'));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Ridwanique Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Welcome to the Admin Dashboard</h1>
        <p class="lead">Manage your website and files here.</p>

        <h3>Uploaded Files</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>File Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $file) : ?>
                    <tr>
                        <td><?= $file ?></td>
                        <td>
                            <a href="delete_file.php?file=<?= urlencode($file) ?>" class="btn btn-danger btn-sm">Delete</a>
                            <a href="http://ridwanique.local/<?= $file ?>" class="btn btn-primary btn-sm" target="_blank">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <hr>

        <a href="file_manager.php" class="btn btn-primary">Go to File Manager</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>
