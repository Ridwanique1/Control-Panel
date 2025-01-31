<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$targetDir = "C:/xampp/htdocs/";

// Check if file is specified for editing
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    $filePath = $targetDir . $file;

    // Check if the file exists
    if (!file_exists($filePath)) {
        die("File not found.");
    }

    // Read file content
    $fileContent = file_get_contents($filePath);
    $fileExtension = pathinfo($file, PATHINFO_EXTENSION);
}

// Handle file save
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_file'])) {
    $newContent = $_POST['file_content'];
    
    // Save the new content to the file
    file_put_contents($filePath, $newContent);

    header('Location: file_manager.php');
    exit;
}

// Determine Prism.js language class
$languageClass = "language-markup"; // Default to markup
$highlightedExtensions = [
    'php' => 'language-php',
    'html' => 'language-html',
    'css' => 'language-css',
    'js' => 'language-javascript',
    'json' => 'language-json',
    'txt' => 'language-none',
];

if (isset($highlightedExtensions[$fileExtension])) {
    $languageClass = $highlightedExtensions[$fileExtension];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit File - Ridwanique Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/prismjs/themes/prism-okaidia.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/prismjs/prism.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Edit File: <?php echo $file; ?></h1>
        
        <form action="edit_file.php?file=<?php echo $file; ?>" method="POST">
            <div class="mb-3">
                <label class="form-label">File Content:</label>
                
                <!-- Hidden textarea to store content -->
                <textarea name="file_content" id="file_content" style="display: none;"><?php echo htmlspecialchars($fileContent); ?></textarea>
                
                <!-- Editable code block -->
                <pre id="editor" contenteditable="true" class="p-3 border rounded <?php echo $languageClass; ?>"><?php echo htmlspecialchars($fileContent); ?></pre>
            </div>
            <button type="submit" name="save_file" class="btn btn-success">Save Changes</button>
        </form>

        <hr>

        <a href="file_manager.php" class="btn btn-secondary">Back to File Manager</a>
    </div>

    <script>
        // Sync content from the editable div to the hidden textarea before submission
        document.querySelector("form").addEventListener("submit", function() {
            document.getElementById("file_content").value = document.getElementById("editor").innerText;
        });

        // Highlight Prism.js on content load
        document.addEventListener("DOMContentLoaded", function() {
            Prism.highlightAll();
        });
    </script>
</body>
</html>
