<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Define the base directory
$baseDir = realpath("C:/xampp/htdocs/") . DIRECTORY_SEPARATOR;

// Get the current directory from URL, default to base directory
$currentDir = isset($_GET['dir']) ? realpath(urldecode($baseDir . $_GET['dir'])) : $baseDir;

// Ensure $currentDir is valid
if ($currentDir === false || strpos($currentDir, $baseDir) !== 0) {
    die("Access denied.");
}

// Fetch all items in the directory
$items = array_diff(scandir($currentDir), array('..', '.'));

// Handle File Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file_upload'])) {
    $uploadFile = $_FILES['file_upload'];
    $targetFilePath = $currentDir . DIRECTORY_SEPARATOR . basename($uploadFile['name']);

    if ($uploadFile['error'] == 0) {
        if (move_uploaded_file($uploadFile['tmp_name'], $targetFilePath)) {
            header('Location: file_manager.php?dir=' . urlencode(str_replace($baseDir, "", $currentDir)));
            exit;
        } else {
            echo "Error uploading the file.";
        }
    }
}

// Handle Folder Upload (ZIP Extraction)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['folder_upload'])) {
    $zipFile = $_FILES['folder_upload'];
    $zipPath = $currentDir . DIRECTORY_SEPARATOR . basename($zipFile['name']);

    if ($zipFile['error'] == 0 && move_uploaded_file($zipFile['tmp_name'], $zipPath)) {
        $zip = new ZipArchive;
        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo($currentDir);
            $zip->close();
            unlink($zipPath); // Delete ZIP after extraction
            header('Location: file_manager.php?dir=' . urlencode(str_replace($baseDir, "", $currentDir)));
            exit;
        } else {
            echo "Error extracting ZIP.";
        }
    }
}

// Handle Rename
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rename_file'])) {
    $oldFilePath = $currentDir . DIRECTORY_SEPARATOR . $_POST['old_file_name'];
    $newFilePath = $currentDir . DIRECTORY_SEPARATOR . $_POST['new_file_name'];

    if (!file_exists($newFilePath)) {
        rename($oldFilePath, $newFilePath);
        header('Location: file_manager.php?dir=' . urlencode(str_replace($baseDir, "", $currentDir)));
        exit;
    } else {
        echo "File with this name already exists.";
    }
}

// Handle Permission Changes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_permissions'])) {
    $filePath = $currentDir . DIRECTORY_SEPARATOR . $_POST['file_name'];
    $permissions = 0;
    if (isset($_POST['read'])) $permissions |= 0444;
    if (isset($_POST['write'])) $permissions |= 0222;
    if (isset($_POST['execute'])) $permissions |= 0111;
    
    chmod($filePath, $permissions);
    header('Location: file_manager.php?dir=' . urlencode(str_replace($baseDir, "", $currentDir)));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Manager - Ridwanique Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>File Manager</h1>
        <p>Upload and manage your files and folders.</p>

        <!-- Back Button -->
        <?php if ($currentDir !== $baseDir): ?>
            <?php
                // Calculate the parent directory
                $parentDir = dirname($currentDir);
                // If the current directory is the base directory, set parent to base
                $parentDir = ($parentDir === $baseDir) ? '' : str_replace($baseDir, '', $parentDir);
            ?>
            <a href="file_manager.php?dir=<?php echo urlencode($parentDir); ?>" class="btn btn-secondary mb-3">⬅ Back</a>
        <?php endif; ?>

        <!-- File Upload Form -->
        <form action="file_manager.php?dir=<?php echo urlencode(str_replace($baseDir, "", $currentDir)); ?>" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Select file to upload</label>
                <input type="file" class="form-control" name="file_upload" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload File</button>
        </form>

        <!-- Folder Upload Form -->
        <form action="file_manager.php?dir=<?php echo urlencode(str_replace($baseDir, "", $currentDir)); ?>" method="POST" enctype="multipart/form-data" class="mt-3">
            <div class="mb-3">
                <label class="form-label">Upload ZIP to Extract as Folder</label>
                <input type="file" class="form-control" name="folder_upload" required>
            </div>
            <button type="submit" class="btn btn-warning">Upload & Extract</button>
        </form>

        <hr>

        <h3>Files & Folders</h3>
        <ul class="list-group">
            <?php foreach ($items as $item): 
                $itemPath = $currentDir . DIRECTORY_SEPARATOR . $item;
                $permissions = substr(sprintf('%o', fileperms($itemPath)), -3);
            ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <?php if (is_dir($itemPath)): ?>
                            📁 <a href="file_manager.php?dir=<?php echo urlencode(str_replace($baseDir, "", $itemPath)); ?>"><?php echo $item; ?></a>
                        <?php else: ?>
                            📄 <?php echo $item; ?>
                            <small class="text-muted">[<?php echo $permissions; ?>]</small>
                        <?php endif; ?>
                    </div>

                    <div>
                    <?php if (!is_dir($itemPath)): ?>
                            <a href="download.php?file=<?php echo urlencode(str_replace($baseDir, "", $itemPath)); ?>" class="btn btn-success btn-sm">Download</a>
                            <a href="edit_file.php?file=<?php echo urlencode(str_replace($baseDir, "", $itemPath)); ?>" class="btn btn-primary btn-sm">Edit</a>
                        <?php endif; ?>

                        <!-- Rename Form -->
                        <form action="file_manager.php?dir=<?php echo urlencode(str_replace($baseDir, "", $currentDir)); ?>" method="POST" class="d-inline">
                            <input type="hidden" name="old_file_name" value="<?php echo $item; ?>">
                            <input type="text" name="new_file_name" placeholder="Rename" required class="form-control form-control-sm d-inline" style="width: 150px;">
                            <button type="submit" name="rename_file" class="btn btn-warning btn-sm">Rename</button>
                        </form>

                        <!-- Permissions -->
                        <?php if (!is_dir($itemPath)): ?>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="file_name" value="<?php echo $item; ?>">
                                <label class="small">R <input type="checkbox" name="read" <?php if ($permissions[0] != '0') echo 'checked'; ?>></label>
                                <label class="small">W <input type="checkbox" name="write" <?php if ($permissions[1] != '0') echo 'checked'; ?>></label>
                                <label class="small">X <input type="checkbox" name="execute" <?php if ($permissions[2] != '0') echo 'checked'; ?>></label>
                                <button type="submit" name="change_permissions" class="btn btn-info btn-sm">Change</button>
                            </form>
                        <?php endif; ?>

                        <a href="delete_file.php?file=<?php echo urlencode($item); ?>" class="btn btn-danger btn-sm">Delete</a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <hr>
        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>
