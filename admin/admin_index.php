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


// Include your CSS and other head elements here



// Ch
// Fetch unique committees with their events using a JOIN
$committeesQuery = "SELECT DISTINCT committee.committee_id, committee.committee_name
                    FROM event_committee_mapping 
                    INNER JOIN committee ON event_committee_mapping.committee_id = committee.committee_id";

$resultCommittees = mysqli_query($conn, $committeesQuery);
// Fetch notifications with associated event IDs and committee names
$sql = "SELECT notifications.*, event_committee_mapping.event_id, committee.committee_name 
        FROM notifications 
        LEFT JOIN event_committee_mapping ON notifications.event_id = event_committee_mapping.event_id 
        LEFT JOIN committee ON event_committee_mapping.committee_id = committee.committee_id 
        WHERE notifications.notification_type IN ('Folder Added', 'Restore Request') 
        ORDER BY notifications.timestamp DESC LIMIT 5";$result = mysqli_query($conn, $sql);

// Initialize an array to store notifications
$notifications = [];

// Check if there are any notifications
if (mysqli_num_rows($result) > 0) {
    // Fetch each notification and store it in the array
    while ($row = mysqli_fetch_assoc($result)) {
        $notifications[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Index</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="../style.css">

    
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
          <a class="navbar-brand" href="#">
            <img src="images/kjsit-logo.svg" alt="Logo">

          </a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
              <li class="nav-item active">
                <a class="nav-link" href="admin_index.php" style="margin-top:8px;">All Committees</a>
              </li>
              
             <!-- Notification dropdown -->
             <li class="nav-item dropdown" style="margin-top:8px;">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Notifications <span class="badge badge-danger"><?php echo count($notifications); ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="max-height: 300px; overflow-y: auto;">
            <!-- Generate HTML for each notification item -->
            <?php foreach ($notifications as $notification): ?>
                <?php
                    $notificationType = $notification['notification_type'];
                    $committeeName = $notification['committee_name'];
                    $message = $notification['message'];
                    $eventId = $notification['event_id']; // Get the event ID from the query
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


            <!-- End of Notification dropdown -->
              
              <li class="nav-item">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="nav-link">
                  <button type="submit" name="logout" class="btn btn-link" style="color:white; ">Logout</button>
                </form>
              </li>
            </ul>
          </div>
        </nav>

<!-- Main content -->

<div class="wrapper">
<div class="main-panel">
    <h2>All Committees</h2>
    <div class="row">
    <?php while ($committee = mysqli_fetch_assoc($resultCommittees)): ?>
        <div class="col-md-3">
            <a href="committee_events.php?committee_id=<?php echo $committee['committee_id']; ?>" class="folder-card">
                <div class="card-body" style="padding:4px;">
                    <p  style="color: black;">
                        <img src="images/folder.png" alt="Folder">
                        <?php echo $committee['committee_name']; ?>
    </p>
                </div>
            </a>
        </div>
    <?php endwhile; ?>
</div>

</div>
</div>


<!-- Footer -->
<footer class="footer">
    <img src="images/kjsit-logo.svg" alt="Logo">
</footer>

<!-- Include Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>

<?php
// Close the database connection
mysqli_close($conn);
?>
