<?php
// Check if selected images are sent via POST
if (isset($_POST['selected_items']) && !empty($_POST['selected_items'])) {
    // Directory where the images are stored
    $uploadDirectory = 'uploads/';

    // Loop through each selected image filename
    foreach ($_POST['selected_items'] as $filename) {
        // File path of the image
        $filePath = $uploadDirectory . $filename;

        // Check if the file exists and is readable
        if (file_exists($filePath) && is_readable($filePath)) {
            // Set appropriate headers for the download
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            
            // Clear output buffer
            ob_clean();
            flush();

            // Read the file and output its contents
            readfile($filePath);
            exit; // Exit after download
        } else {
            // Handle if the file doesn't exist or is not readable
            echo "Error: File '$filename' not found or cannot be read.";
        }
    }
} else {
    // Handle if no selected images are received
    echo "No images selected for download.";
}
?>
