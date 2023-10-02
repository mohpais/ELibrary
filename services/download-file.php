<?php
$file = $_GET['file']; // Get the file name or path from a query parameter
// Add security checks and validation here, e.g., check user permissions, sanitize input, etc.
$file_path = '../uploads/' . $file; // Set the path to your folder
if (file_exists($file_path)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file_path));
    readfile($file_path);
    exit;
} else {
    // File not found or permission denied
    http_response_code(404);
    echo 'File not found';
}
?>
