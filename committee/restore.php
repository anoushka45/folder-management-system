<?php
// Include your database configuration file
include '_dbconnect.php';

// Start the session
session_start();



// Check if the user is logged in
if (!isset($_SESSION['committee_username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.html");
    exit();
}

// Get the username of the logged-in user from the session
$username = $_SESSION['committee_username'];

// Query the database to get the committee_id based on the username
$getCommitteeIdQuery = "SELECT committee_id FROM committee WHERE login_username = ?";
$stmt = mysqli_prepare($conn, $getCommitteeIdQuery);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    // Fetch the committee_id
    $row = mysqli_fetch_assoc($result);
    $committeeId = $row['committee_id'];

    // Check if the event ID is provided in the URL
    // Check if the event ID and event name are provided in the URL
    if (!isset($_GET['event_id'])) {
        // Redirect back to the referring page with an error message
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=Event ID or Event Name not provided");
        exit();
    }
    

    // Get the event ID and event name from the URL
    $eventId = $_GET['event_id'];
    // Change from $_POST to $_GET
    $getEventNameQuery = "SELECT event_name FROM event WHERE event_id = ?";
    $stmtEventName = mysqli_prepare($conn, $getEventNameQuery);
    mysqli_stmt_bind_param($stmtEventName, "i", $eventId);
    mysqli_stmt_execute($stmtEventName);
    $resultEventName = mysqli_stmt_get_result($stmtEventName);
    
    if ($resultEventName && mysqli_num_rows($resultEventName) > 0) {
        $row = mysqli_fetch_assoc($resultEventName);
        $eventName = $row['event_name'];
    } else {
        // Handle error if event name not found
        echo "Error: Event name not found for the provided event ID";
        exit();
    }
    // Function to create a notification
    function createNotification($conn, $committeeId, $eventId, $notificationType, $message)
    {
        // Fetch the committee name based on the committee_id
        $committeeNameQuery = "SELECT committee_name FROM committee WHERE committee_id = ?";
        $stmtCommitteeName = mysqli_prepare($conn, $committeeNameQuery);
        mysqli_stmt_bind_param($stmtCommitteeName, "i", $committeeId);
        mysqli_stmt_execute($stmtCommitteeName);
        $resultCommitteeName = mysqli_stmt_get_result($stmtCommitteeName);

        if ($resultCommitteeName && mysqli_num_rows($resultCommitteeName) > 0) {
            $row = mysqli_fetch_assoc($resultCommitteeName);
            $committeeName = $row['committee_name'];

            // Insert the notification with committee_name
            $notificationQuery = "INSERT INTO notifications (committee_id, committee_name, event_id, event_name, notification_type, message) VALUES (?, ?, ?, ?, ?, ?)";
            $stmtNotification = mysqli_prepare($conn, $notificationQuery);
            $stmtNotification = mysqli_prepare($conn, $notificationQuery);
            mysqli_stmt_bind_param($stmtNotification, "isssss", $committeeId, $committeeName, $eventId, $eventName, $notificationType, $message);
            $notificationResult = mysqli_stmt_execute($stmtNotification);



            if ($notificationResult) {
                return true;
            } else {
                echo "Error creating notification: " . mysqli_error($conn);
                return false;
            }
        } else {
            echo "Error fetching committee name: " . mysqli_error($conn);
            return false;
        }
    }

    // Check if the form is submitted and selected images/videos are present
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_images'])) {
        // Fetch selected images/videos to restore
        $selectedImages = $_POST['selected_images'];

        // Iterate over selected images/videos and update their approval status
        foreach ($selectedImages as $image) {
            // Perform update operation for each selected image/video
            $updateQuery = "UPDATE mediafile SET approval_status = 'pending' WHERE file_name = '$image' AND event_id = '$eventId'";
            $result = mysqli_query($conn, $updateQuery);
            if (!$result) {
                // Handle error if update fails
                echo "Error updating approval status: " . mysqli_error($conn);
            }
        }


    
// Check if the user clicked the delete button
if (isset($_POST['delete'])) {
    // Check if both event_id and media_id are provided
    if (isset($_POST['event_id'], $_POST['media_id'])) {
        // Sanitize the inputs
        $eventId = mysqli_real_escape_string($conn, $_POST['event_id']);
        $mediaId = mysqli_real_escape_string($conn, $_POST['media_id']);

        // Query to delete the media file from the database
        $deleteQuery = "DELETE FROM mediafile WHERE event_id = '$eventId' AND media_id = '$mediaId'";
        $deleteResult = mysqli_query($conn, $deleteQuery);

        if ($deleteResult) {
            // Media file deleted successfully
            // Redirect back to the same page or any other appropriate page
            header("Location: view_rejected_items.php?event_id=$eventId");
            exit();
        } else {
            // Error occurred while deleting the media file
            echo "Error deleting media file: " . mysqli_error($conn);
            exit();
        }
    } else {
        // Event ID or Media ID is missing
        echo "Event ID or Media ID is missing";
        exit();
    }
}



        // Create a notification
        $notificationType = "Restore Request";
        $message = "A request has been made to restore items for event: '$eventName' (ID: '$eventId')";
        $notificationCreated = createNotification($conn, $committeeId, $eventId, $notificationType, $message);

        if (!$notificationCreated) {
            // Handle error if notification creation fails
            echo "Error creating notification: " . mysqli_error($conn);
        }

        // Redirect back to the page after update
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        // No images/videos selected for restoring
        echo "No images/videos selected for restoring";
    }

} else {
    // Handle error if committee_id not found for the logged-in user
    echo "Error: Committee ID not found for the logged-in user";
}

// Close the database connection
mysqli_close($conn);
?>