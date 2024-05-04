<?php
// Include your database configuration file
include '_dbconnect.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['committee_username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.html");
    exit();
}

// Check if the event ID is provided in the URL
if (!isset($_GET['event_id'])) {
    // Redirect back to the referring page with an error message
    header("Location: ".$_SERVER['HTTP_REFERER']."?error=Event ID not provided");
    exit();
}

// Get the event ID from the URL
$eventId = $_GET['event_id'];

// Check if the form is submitted and selected images/videos are present
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize an array to store selected media files (images/videos)
    $selectedMedia = [];

    // Check if selected images are present
    if (isset($_POST['selected_items'])) {
        // Fetch selected images to delete
        $selectedImages = $_POST['selected_items'];
        // Add selected images to the array
        $selectedMedia = array_merge($selectedMedia, $selectedImages);
    }

    // Check if selected videos are present
    if (isset($_POST['selected_videos'])) {
        // Fetch selected videos to delete
        $selectedVideos = $_POST['selected_videos'];
        // Add selected videos to the array
        $selectedMedia = array_merge($selectedMedia, $selectedVideos);
    }

    // Iterate over selected media files and delete them from the database
    foreach ($selectedMedia as $media) {
        // Perform delete operation for each selected media file
        $deleteQuery = "DELETE FROM mediafile WHERE file_name = '$media' AND event_id = '$eventId'";
        $result = mysqli_query($conn, $deleteQuery);
        if (!$result) {
            // Handle error if deletion fails
            echo "Error deleting media file: " . mysqli_error($conn);
        }
    }

    // Redirect back to the page after deletion
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit();
} else {
    // No media files selected for deletion
    echo "No media files selected for deletion";
}

// Close the database connection
mysqli_close($conn);
?>
