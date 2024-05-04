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

// Function to create a notification
function createNotification($conn, $committeeId, $eventId, $notificationType, $message) {
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
        $notificationQuery = "INSERT INTO notifications (committee_id, event_id, notification_type, message, committee_name) VALUES (?, ?, ?, ?, ?)";
        $stmtNotification = mysqli_prepare($conn, $notificationQuery);
        mysqli_stmt_bind_param($stmtNotification, "iisss", $committeeId, $eventId, $notificationType, $message, $committeeName);
        $notificationResult = mysqli_stmt_execute($stmtNotification);

        if ($notificationResult) {
            return true;
        } else {
            echo "Error creating notification: " . mysqli_error($conn);
            return false;
        }
    } else {
        echo "Please login again!!" . mysqli_error($conn);
        return false;
    }
}

// Fetch the logged-in user's username and committee ID
$loggedInUser = $_SESSION['committee_username'];
$committeeQuery = "SELECT committee_id FROM committee WHERE login_username = ?";
$stmtCommittee = mysqli_prepare($conn, $committeeQuery);
mysqli_stmt_bind_param($stmtCommittee, "s", $loggedInUser);
mysqli_stmt_execute($stmtCommittee);
$resultCommittee = mysqli_stmt_get_result($stmtCommittee);

if ($resultCommittee) {
    $row = mysqli_fetch_assoc($resultCommittee);
    $committeeId = $row['committee_id'];

    // Get the folder name and parent folder ID from the form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $folderName = $_POST["folderName"];
        $parentFolderId = isset($_POST["parentFolderId"]) ? $_POST["parentFolderId"] : NULL;

        // If a parent folder ID is provided, check if it exists
        if ($parentFolderId !== NULL) {
            $checkParentQuery = "SELECT * FROM event WHERE event_id = ?";
            $stmtParentCheck = mysqli_prepare($conn, $checkParentQuery);
            mysqli_stmt_bind_param($stmtParentCheck, "i", $parentFolderId);
            mysqli_stmt_execute($stmtParentCheck);
            $resultParentCheck = mysqli_stmt_get_result($stmtParentCheck);

            if ($resultParentCheck && mysqli_num_rows($resultParentCheck) > 0) {
                // Parent folder exists, proceed with inserting the subfolder
                $insertQuery = "INSERT INTO event (event_name, parent_event_id) VALUES (?, ?)";
                $stmtInsert = mysqli_prepare($conn, $insertQuery);
                mysqli_stmt_bind_param($stmtInsert, "si", $folderName, $parentFolderId);
                $result = mysqli_stmt_execute($stmtInsert);

                if ($result) {
                    // Retrieve the auto-generated event ID
                    $eventId = mysqli_insert_id($conn);

                    // Insert data into the event_committee_mapping table
                    $mappingQuery = "INSERT INTO event_committee_mapping (event_id, committee_id) VALUES (?, ?)";
                    $stmtMapping = mysqli_prepare($conn, $mappingQuery);
                    mysqli_stmt_bind_param($stmtMapping, "ii", $eventId, $committeeId);
                    $mappingResult = mysqli_stmt_execute($stmtMapping);

                    if ($mappingResult) {
                        // Subfolder added successfully

                        // Create a notification
                        $notificationType = "Folder Added";
                        $message = "A new folder '{$folderName}' has been added.";
                        $notificationCreated = createNotification($conn, $committeeId, $eventId, $notificationType, $message);

                        if ($notificationCreated) {
                            // Notification created successfully
                            header("Location: {$_SERVER['HTTP_REFERER']}");
                            exit();
                        }
                    } else {
                        // Error adding folder to event_committee_mapping
                        echo "Error: " . mysqli_error($conn);
                    }
                } else {
                    // Error adding folder to event table
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                // Parent folder does not exist
                echo "Parent folder does not exist";
            }
        } else {
            // No parent folder ID provided, insert the main event directly
            $insertQuery = "INSERT INTO event (event_name, parent_event_id) VALUES (?, NULL)";
            $stmtInsert = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($stmtInsert, "s", $folderName);
            $result = mysqli_stmt_execute($stmtInsert);

            if ($result) {
                // Retrieve the auto-generated event ID
                $eventId = mysqli_insert_id($conn);

                // Insert data into the event_committee_mapping table
                $mappingQuery = "INSERT INTO event_committee_mapping (event_id, committee_id) VALUES (?, ?)";
                $stmtMapping = mysqli_prepare($conn, $mappingQuery);
                mysqli_stmt_bind_param($stmtMapping, "ii", $eventId, $committeeId);
                $mappingResult = mysqli_stmt_execute($stmtMapping);

                if ($mappingResult) {
                    // Main event added successfully

                    // Create a notification
                    $notificationType = "Folder Added";
                    $message = "A new folder '{$folderName}' has been added.";
                    $notificationCreated = createNotification($conn, $committeeId, $eventId, $notificationType, $message);

                    if ($notificationCreated) {
                        // Notification created successfully
                        header("Location: {$_SERVER['HTTP_REFERER']}");
                        exit();
                    }
                } else {
                    // Error adding folder to event_committee_mapping
                    echo "Error: " . mysqli_error($conn);
                }
            } else {
                // Error adding folder to event table
                echo "Error: " . mysqli_error($conn);
            }
        }
    } else {
        // Redirect to the homepage if accessed directly
        header("Location: index.php");
        exit();
    }
} else {
    // Unable to fetch committee details
    echo "Error fetching committee details: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>
