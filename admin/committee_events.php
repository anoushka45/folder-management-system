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

// Fetch unique committees with their events using a JOIN
$committeesQuery = "SELECT DISTINCT committee.committee_id, committee.committee_name
                    FROM event_committee_mapping 
                    INNER JOIN committee ON event_committee_mapping.committee_id = committee.committee_id";

$resultCommittees = mysqli_query($conn, $committeesQuery);





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
// Check if the committee_id is provided in the URL
if (isset($_GET['committee_id'])) {
    $committeeId = $_GET['committee_id'];

    // Fetch committee name based on the provided committee_id
    $committeeQuery = "SELECT committee_name FROM committee WHERE committee_id = '$committeeId'";
    $resultCommittee = mysqli_query($conn, $committeeQuery);

    if ($resultCommittee && mysqli_num_rows($resultCommittee) > 0) {
        $committee = mysqli_fetch_assoc($resultCommittee);
        $committeeName = $committee['committee_name'];

        // Fetch main events associated with the provided committee_id (exclude subfolders)
        $eventsQuery = "SELECT event.event_id, event.event_name
                        FROM event_committee_mapping
                        INNER JOIN event ON event_committee_mapping.event_id = event.event_id
                        WHERE event_committee_mapping.committee_id = '$committeeId' 
                        AND event.parent_event_id IS NULL
                        ORDER BY time_stamp DESC"; // Exclude events with a parent_event_id

        $resultEvents = mysqli_query($conn, $eventsQuery);
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $committeeName; ?> Events</title>
            <!-- Include Bootstrap CSS -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
            <!-- Custom CSS -->
            <link rel="stylesheet" href="../style.css">

            <style>.folder-card{
        padding: 2px;
      }</style>
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
                       
                        <li class="nav-item">
                            <a class="nav-link" href="admin_index.php" style="margin-top:8px;">All Committees</a>
                        </li>
                        <li class="nav-item dropdown" style="margin-top:8px;">
    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Notifications <span class="badge badge-danger"><?php echo count($notifications); ?></span>
    </a>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="max-height: 300px; overflow-y: auto;">
        <!-- Generate HTML for each notification item -->
        <?php foreach ($notifications as $notification): ?>
            <?php
                // Fetch additional details such as committee_name for each notification
                $committeeNames = $notification['committee_name'];
                $notificationType = $notification['notification_type'];
                $message = $notification['message'];
            ?>
            <a class="dropdown-item" href="#">
                <strong><?php echo $notificationType; ?> (<?php echo $committeeNames; ?>):</strong> <?php echo $message; ?>
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
                    <h2 class="mb-4"><?php echo $committeeName; ?></h2>
                    <div class="row">
                        <?php
                        // Display main events associated with the committee
                        while ($event = mysqli_fetch_assoc($resultEvents)) {
                            ?>
                            <div class="col-md-3">
                                <div class="folder-card">
                                    <div class="card-body">
                                    <p><a href="event_page.php?event_id=<?php echo $event['event_id']; ?>">

                                                <?php echo '<img src="images/folder.png" alt="Folder">'; ?>
                                                <?php echo $event['event_name']; ?>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <footer class="footer">
    <img src="images/kjsit-logo.svg" alt="Logo">
</footer>
            <!-- Include Bootstrap JS -->
            <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
                integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
                crossorigin="anonymous"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
                integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
                crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
                integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
                crossorigin="anonymous"></script>
        </body>

        </html>

        <?php
    } else {
        // No committee found with the provided ID
        echo "No committee found with the provided ID";
    }
} else {
    // Error fetching committee details
    echo "Error fetching committee details: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>