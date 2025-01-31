<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Handle file renaming
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['old_name']) && isset($_POST['new_name'])) {
    $oldName = $_POST['old_name'];
    $newName = $_POST['new_name'];
    $uploadDir = "C:/xampp/htdocs/";

    // Check if the old file exists and rename
    if (file_exists($uploadDir . $oldName)) {
        rename($uploadDir . $oldName, $uploadDir . $newName);
        header('Location: dashboard.php');
        exit;
    } else {
        echo "File does not exist.";
    }
}

if (isset($_GET['file'])) {
    $file = $_GET['file'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rename File - Ridwanique Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Rename File</h1>
        <form action="rename_file.php" method="POST">
            <div class="mb-3">
                <label for="old_name" class="form-label">Current File Name</label>
                <input type="text" class="form-control" name="old_name" id="old_name" value="<?= $file ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="new_name" class="form-label">New File Name</label>
                <input type="text" class="form-control" name="new_name" id="new_name" required>
            </div>
            <button type="submit" class="btn btn-primary">Rename</button>
        </form>
        <hr>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>
