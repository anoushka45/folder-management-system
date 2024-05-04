<?php
// Include your database configuration file
include '_dbconnect.php';

// Start the session
session_start();

// Check if the user clicked the logout button
if (isset($_POST['logout'])) {
    // Unset all session variables
    unset($_SESSION['committee_username']);
   
    // Redirect the user to the login page
    header("Location: login.html");
    exit();
}

// Check if the user is logged in
if (!isset($_SESSION['committee_username'])) {
    // Redirect to the login page if not logged in
    header("Location: login.html");
    exit();
}
// Fetch the logged-in user's username and committee ID
$loggedInUser = $_SESSION['committee_username'];
$committeeQuery = "SELECT committee_id, committee_name FROM committee WHERE login_username = '$loggedInUser'";
$resultCommittee = mysqli_query($conn, $committeeQuery);

if ($resultCommittee) {
    $row = mysqli_fetch_assoc($resultCommittee);
    $committeeId = $row['committee_id'];
    $committeeName = $row['committee_name'];

    // Query to fetch all folders added by the committee
    $foldersQuery = "SELECT * FROM event 
                 WHERE parent_event_id IS NULL 
                 AND event_id IN (
                     SELECT event_id FROM event_committee_mapping WHERE committee_id = '$committeeId'
                 )
                 ORDER BY time_stamp DESC";

    $resultFolders = mysqli_query($conn, $foldersQuery);
    $notificationsQuery = "SELECT * FROM notifications WHERE  notification_type = 'item rejected' ORDER BY timestamp DESC LIMIT 5";
    $resultNotifications = mysqli_query($conn, $notificationsQuery);
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!--GOOGLE FONTS-->
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Play&display=swap" rel="stylesheet"> 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<!--FONT AWESOME-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
                integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
                <link rel="stylesheet" href="../style.css">
        <title>My Folders</title>
       
        <style>.navbar {
  background-color: #4c24ff;
  /* Set your desired background color */
}
.footer{
    background-color: #1d1f21;

}
#b2{
    background-color: #3e1adf;
}
</style>
    </head>
    <body>

    <nav class="navbar navbar-expand-lg navbar-dark">
                <a class="navbar-brand" href="#">
                    <p>logo</p>
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">
                        
                       
                        <li class="nav-item dropdown" style="margin-top: 8px;">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Notifications <span class="badge badge-primary"><?php echo mysqli_num_rows($resultNotifications); ?></span>
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="max-height: 300px; overflow-y: auto;">
        <!-- Check if there are any notifications -->
        <?php if (mysqli_num_rows($resultNotifications) > 0): ?>
            <!-- Notification items -->
            <ul class="list-unstyled">
                <?php while ($notification = mysqli_fetch_assoc($resultNotifications)): ?>
                    <li class="dropdown-item">
                        <strong><?php echo $notification['notification_type']; ?></strong> <?php echo $notification['message']; ?>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <!-- Display a message if there are no notifications -->
            <a class="dropdown-item" href="#">No new notifications</a>
        <?php endif; ?>
    </div>
</li>

                        <li class="nav-item">
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                                class="nav-link">
                                <button type="submit" name="logout" class="btn btn-link" style="color:white;">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="wrapper">
    <div class="main-panel">
        <h1 style="font-size:25px;">Welcome back, <?php echo $committeeName; ?>!</h1>
        <h2 style="font-size:30px;">My Folders</h2>

        <div class="row">
            <div class="col-md-4">
                <!-- Button to add a new folder -->
                <button type="button" id="b2" class="btn  mb-3" data-toggle="modal" data-target="#addFolderModal">
                    Add New Folder
                </button>
            </div>
        </div>

        <div class="row">
                <?php
                // Loop through each folder and display it
                while ($folder = mysqli_fetch_assoc($resultFolders)) {
                    echo '<div class="col-md-3">';
                    echo '<div class="folder-card" id="folder_' . $folder['event_id'] . '" style="position: relative;">'; // Add position relative and unique identifier

                    // Anchor tag for folder name
                    echo '<a href="additems.php?event_id=' . $folder['event_id'] . '" style="padding: 5px;">';
                    echo '<p><img src="images/folder.png" alt="Folder">' . $folder['event_name'] . '</p>';
                    echo '</a>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>

    </div>
</div>

<!-- Modal for adding a new folder -->
<div class="modal fade" id="addFolderModal" tabindex="-1" role="dialog" aria-labelledby="addFolderModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFolderModalLabel">Add New Folder</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="addfolder.php" method="post">
                    <div class="form-group">
                        <label for="folderName">Folder Name</label>
                        <input type="text" class="form-control" id="folderName" name="folderName" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Folder</button>
                </form>
            </div>
        </div>
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
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
                integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
                crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
                integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
                crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
                integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
                crossorigin="anonymous"></script>
               
    </body>
    </html>

    <?php
} else {
    // Unable to fetch committee details
    header("Location: login.html");
    exit();

}

// Close the database connection
mysqli_close($conn);
?>
