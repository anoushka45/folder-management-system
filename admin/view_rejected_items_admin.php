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





$sql = "SELECT * FROM notifications WHERE notification_type = 'Folder Added' OR notification_type = 'Restore Request' ORDER BY timestamp DESC LIMIT 5";
$result = mysqli_query($conn, $sql);

// Initialize an array to store notifications
$notifications = [];

// Check if there are any notifications
if (mysqli_num_rows($result) > 0) {
    // Fetch each notification and store it in the array
    while ($row = mysqli_fetch_assoc($result)) {
        $notifications[] = $row;
    }
}
// Check if the user is logged in



// Check if the event ID is provided in the URL
if (!isset($_GET['event_id'])) {
    // Redirect back to the referring page with an error message
    header("Location: ".$_SERVER['HTTP_REFERER']."?error=Event ID not provided");
    exit();
}

// Get the event ID from the URL
$eventId = $_GET['event_id'];

// Query to fetch rejected images/videos for the current event
$rejectedQuery = "SELECT * FROM mediafile WHERE event_id = '$eventId' AND approval_status = 'rejected' OR approval_status = 'pending'";
$resultRejected = mysqli_query($conn, $rejectedQuery);

if (!$resultRejected) {
    // Handle error if query fails
    echo "Error fetching rejected images/videos: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Rejected Items</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"><!--GOOGLE FONTS-->
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Play&display=swap" rel="stylesheet"> 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<!--FONT AWESOME-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
                integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../style.css">
  
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark">
                <a class="navbar-brand" href="#">
<p>logo</p>                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                       
                        <li class="nav-item">
                            <a class="nav-link" href="admin_index.php" style="margin-top:8px;">All Committees</a>
                        </li>
                        <li class="nav-item dropdown" style="margin-top:8px;">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Notifications <span class="badge badge-primary"><?php echo count($notifications); ?></span>
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="max-height: 300px; overflow-y: auto;">
        <!-- Generate HTML for each notification item -->
        <?php foreach ($notifications as $notification): ?>
            <?php
                // Fetch additional details such as committee_name for each notification
                $committeeName = $notification['committee_name'];
                $notificationType = $notification['notification_type'];
                $message = $notification['message'];
            ?>
            <a class="dropdown-item" href="#">
                <strong><?php echo $notificationType; ?> (<?php echo $committeeName; ?>):</strong> <?php echo $message; ?>
            </a>
        <?php endforeach; ?>
        <!-- Display a message if there are no notifications -->
        <?php if (empty($notifications)): ?>
            <a class="dropdown-item" href="#">No new notifications</a>
        <?php endif; ?>
    </div>
</li>
                        <li class="nav-item">
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="nav-link">
                                <button type="submit" name="logout" class="btn btn-link" style="color:white;">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
    <div class="wrapper">
    <div class="main-panel">
    <h1>Rejected Items</h1>

    <?php if (mysqli_num_rows($resultRejected) > 0): ?>
        <div class="row">
    <?php while ($media = mysqli_fetch_assoc($resultRejected)): ?>
        <div class="col-md-3">
            <form action="restore_admin.php?event_id=<?php echo $eventId; ?>" method="POST">
                <div class="card mb-3">
                    <div class="grid-container">
                        <!-- Create a container with a 1:1 aspect ratio -->
                        <img src="../uploads/<?php echo $media['file_name']; ?>" class="card-img-top image-slide" alt="Rejected Video">
                        <!-- Image set to cover the container without stretching -->
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $media['file_name']; ?></h5>
                        <!-- Add any additional information or actions related to the rejected image/video -->
                        <div class="checkbox-container">
                            <input type="checkbox" name="selected_images[]" value="<?php echo $media['file_name']; ?>">
                            <label for="selected_images[]">Select</label>
                        </div>
                        <?php if ($media['approval_status'] == 'pending'): ?>
                            <p class="text-danger">Request to restore</p>
                        <?php endif; ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-success">Restore</button>
                <input type="hidden" name="event_id" value="<?php echo $eventId; ?>"> <!-- Add this hidden input field -->
            </form>
        </div>
    <?php endwhile; ?>
</div>



    <?php else: ?>

        <div class="alert alert-info" role="alert">
            No rejected items found for this event.
        </div>
    <?php endif; ?>
</div>

    </div>

    <div class="footer">
                <div class="container">

                    <div class="social-links">
                        <a href="https://github.com/yourgithub"><i class="fa fa-github"></i></a>
                        <a href="https://linkedin.com/in/yourlinkedin"><i class="fa fa-linkedin"></i></a>
                    </div>

                </div>
                <div class="text-center">
                    <p>&copy; 2024 Anoushka Vyas. All rights reserved.</p>
                </div>
            </div>

      
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

  
</body>

</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
