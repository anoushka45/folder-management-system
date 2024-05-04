<?php
include '_dbconnect.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventId = $_POST['eventId'];
    
    // Fetch committee ID based on login session
    session_start();
    if(isset($_SESSION['committee_username'])) {
        $username = $_SESSION['committee_username'];
        $committeeQuery = "SELECT committee_id FROM committee WHERE login_username = '$username'";
        
        // Execute the query
        $resultCommittee = mysqli_query($conn, $committeeQuery);
        
        if($resultCommittee && mysqli_num_rows($resultCommittee) > 0) {
            $row = mysqli_fetch_assoc($resultCommittee);
            $committeeId = $row['committee_id'];
            
            // Process image uploads
            if (!empty($_FILES['images']['name'][0])) {
                // Loop through each image file
                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    $imageName = $_FILES['images']['name'][$key];
                    $imageType = $_FILES['images']['type'][$key];
                    $imageTmpName = $_FILES['images']['tmp_name'][$key];
                    $imageError = $_FILES['images']['error'][$key];
                    $imageSize = $_FILES['images']['size'][$key];
                    
                    // Check if uploaded file is a valid image
                    if ($imageError === 0) {
                        $imageFileType = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
                        $allowedImageTypes = array('jpg', 'jpeg', 'png', 'gif');
                        
                        if (in_array($imageFileType, $allowedImageTypes)) {
                            // Generate unique file name
                            $fileName = uniqid('img_') . '.' . $imageFileType;
                            $filePath = '../uploads/' . $fileName;
                            
                            // Move uploaded file to desired location
                            if (move_uploaded_file($imageTmpName, $filePath)) {
                                // Insert file information into database
                                $insertQuery = "INSERT INTO mediafile (event_id, committee_id, file_name, file_type, approval_status) VALUES ('$eventId', '$committeeId', '$fileName', 'photo', 'awaiting')";
                                $resultInsert = mysqli_query($conn, $insertQuery);
                                if(!$resultInsert) {
                                    echo "Error inserting data: " . mysqli_error($conn);
                                }
                            } else {
                                echo "Failed to upload image.";
                            }
                        } else {
                            echo "Unsupported image file type.";
                        }
                    } else {
                        echo "Error uploading image.";
                    }
                }
            }
            
            // Process video uploads
            if (!empty($_FILES['videos']['name'][0])) {
                // Loop through each video file
                foreach ($_FILES['videos']['tmp_name'] as $key => $tmp_name) {
                    $videoName = $_FILES['videos']['name'][$key];
                    $videoType = $_FILES['videos']['type'][$key];
                    $videoTmpName = $_FILES['videos']['tmp_name'][$key];
                    $videoError = $_FILES['videos']['error'][$key];
                    $videoSize = $_FILES['videos']['size'][$key];
                    
                    // Check if uploaded file is a valid video
                    if ($videoError === 0) {
                        $videoFileType = strtolower(pathinfo($videoName, PATHINFO_EXTENSION));
                        $allowedVideoTypes = array('mp4', 'avi', 'mov', 'wmv');
                        
                        if (in_array($videoFileType, $allowedVideoTypes)) {
                            // Generate unique file name
                            $fileName = uniqid('video_') . '.' . $videoFileType;
                            
                            // Upload video to external storage (e.g., Amazon S3)
                            // Replace the following code with the logic to upload the video
                            // to your chosen external storage service
                            $uploaded = move_uploaded_file($videoTmpName, '../uploads/videos/' . $fileName);
                            
                            if ($uploaded) {
                                // Insert file information into database
                                $insertQuery = "INSERT INTO mediafile (event_id, committee_id, file_name, file_type, approval_status) VALUES ('$eventId', '$committeeId', '$fileName', 'video', 'awaiting')";
                                $resultInsert = mysqli_query($conn, $insertQuery);
                                if(!$resultInsert) {
                                    echo "Error inserting data: " . mysqli_error($conn);
                                }
                            } else {
                                echo "Failed to upload video.";
                            }
                        } else {
                            echo "Unsupported video file type.";
                        }
                    } else {
                        echo "Error uploading video.";
                    }
                }
            }
            
            // Redirect back to the same page after upload
            header("Location: {$_SERVER['HTTP_REFERER']}");
            exit();
        } else {
            echo "Error fetching committee ID.";
        }
    } else {
        echo "User session not found.";
    }
} else {
    echo "Invalid request method.";
}
?>
