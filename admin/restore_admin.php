<?php
// Include your database configuration file
include '_dbconnect.php';

// Start the session
session_start();

// Check if the user clicked the logout button
if (isset($_POST['logout'])) {
    // Unset all session variables specific to admin

    unset($_SESSION['admin_username']);


    // Redirect the user to the login page
    header("Location: facultylogin.php");
    exit();
}

// Check if the user is logged in
if (!isset($_SESSION['admin_username'])) {
    // Redirect to the login page if not logged in
    header("Location: facultylogin.php");
    exit();
}




// Check if the event ID is provided in the URL
if (!isset($_GET['event_id'])) {
    // Redirect back to the referring page with an error message
    header("Location: ".$_SERVER['HTTP_REFERER']."?error=Event ID not provided");
    exit();
}

// Get the event ID from the URL
// Get the event ID from the form data instead of $_GET
$eventId = $_POST['event_id'];

// Check if the form is submitted and selected images/videos are present
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_images'])) {
    // Fetch selected images/videos to restore
    $selectedImages = $_POST['selected_images'];
    
    // Iterate over selected images/videos and update their approval status
    foreach ($selectedImages as $image) {
        // Perform update operation for each selected image/video
        $updateQuery = "UPDATE mediafile SET approval_status = 'approved' WHERE file_name = '$image' AND event_id = '$eventId'";
        $result = mysqli_query($conn, $updateQuery);
        if (!$result) {
            // Handle error if update fails
            echo "Error updating approval status: " . mysqli_error($conn);
        }
    }
    
    // Redirect back to the page after update
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit();
} else {
    // No images/videos selected for restoring
    echo "No images/videos selected for restoring";
}

// Close the database connection
mysqli_close($conn);
?>
