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
    header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=Event ID not provided");
    exit();
}

$eventId = $_GET['event_id'];

// Fetch event name based on event ID
$eventNameQuery = "SELECT event_name FROM event WHERE event_id = ?";
$stmtEventName = mysqli_prepare($conn, $eventNameQuery);
mysqli_stmt_bind_param($stmtEventName, "s", $eventId);
mysqli_stmt_execute($stmtEventName);
$resultEventName = mysqli_stmt_get_result($stmtEventName);

if ($resultEventName && mysqli_num_rows($resultEventName) > 0) {
    $rowEventName = mysqli_fetch_assoc($resultEventName);
    $eventName = $rowEventName['event_name'];
} else {
    // Handle error if event name not found
    echo "Error: Event name not found";
    exit();
}

// Check if the form is submitted and selected images/videos are present
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_items'])) {
    // Fetch selected images/videos to reject
    $selectedImages = $_POST['selected_items'];

    // Update status of selected images/videos to "rejected"
    $updateRejectedQuery = "UPDATE mediafile SET approval_status = 'rejected' WHERE file_name IN ('" . implode("','", $selectedImages) . "') AND event_id = ?";
    $stmtRejected = mysqli_prepare($conn, $updateRejectedQuery);
    mysqli_stmt_bind_param($stmtRejected, "s", $eventId);
    $resultRejected = mysqli_stmt_execute($stmtRejected);

    if (!$resultRejected) {
        // Handle error if update fails
        echo "Error updating image/video status to rejected: " . mysqli_error($conn);
        exit();
    }

    // Prepare the insert notification query
    $insertNotificationQuery = "INSERT INTO notifications (committee_id, committee_name, event_id, event_name, notification_type, message, timestamp) VALUES (?, ?, ?, ?, 'item rejected', ?, NOW())";
    $stmtInsertNotification = mysqli_prepare($conn, $insertNotificationQuery);
    mysqli_stmt_bind_param($stmtInsertNotification, "ssiss", $committeeId, $committeeName, $eventId, $eventName, $notificationMessage);

    // Set the notification message
    $notificationMessage = "Admin has rejected " . count($selectedImages) . " item(s) from event '" . $eventName . "'";

    // Execute the insert notification query
    $resultInsertNotification = mysqli_stmt_execute($stmtInsertNotification);

    if (!$resultInsertNotification) {
        // Handle error if insertion fails
        echo "Error creating notification: " . mysqli_error($conn);
        exit();
    }

    // Update status of non-selected images/videos to "approved"
    $updateApprovedQuery = "UPDATE mediafile SET approval_status = 'approved' WHERE event_id = ? AND file_name NOT IN ('" . implode("','", $selectedImages) . "') AND approval_status != 'rejected'";
    $stmtApproved = mysqli_prepare($conn, $updateApprovedQuery);
    mysqli_stmt_bind_param($stmtApproved, "s", $eventId);
    $resultApproved = mysqli_stmt_execute($stmtApproved);

    if (!$resultApproved) {
        // Handle error if update fails
        echo "Error updating image/video status to approved: " . mysqli_error($conn);
        exit();
    }

    // Redirect back to the page after updating statuses
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['approve_all'])) {
    // Approve all images/videos
    $updateAllApprovedQuery = "UPDATE mediafile SET approval_status = 'approved' WHERE event_id = ? AND approval_status != 'rejected'";
    $stmtAllApproved = mysqli_prepare($conn, $updateAllApprovedQuery);
    mysqli_stmt_bind_param($stmtAllApproved, "s", $eventId);
    $resultAllApproved = mysqli_stmt_execute($stmtAllApproved);

    if (!$resultAllApproved) {
        // Handle error if update fails
        echo "Error updating all image/video statuses to approved: " . mysqli_error($conn);
        exit();
    }

    // Redirect back to the page after updating statuses
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
} else {
    // Redirect back to the page if the form is not submitted correctly
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit();
}
?>
