<?php
// Include your database configuration file
include '_dbconnect.php';

// Start the session
session_start();

// Check if the user clicked the logout button
if (isset($_POST['logout'])) {


    unset($_SESSION['username']);


    // Redirect the user to the login page
    header("Location:userlogin.php");
    exit();
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location:userlogin.php");
    exit();
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
            <link rel="preconnect" href="https://fonts.gstatic.com">
            <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Play&display=swap" rel="stylesheet">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
                integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
            <!--FONT AWESOME-->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
                integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
                integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
            <!-- Custom CSS -->
            <link rel="stylesheet" href="../style.css">

            <style>
                .folder-card {
                    padding: 2px;
                }
            </style>
        </head>

        <body>
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-dark">
                <a class="navbar-brand" href="#">
                <img src="images/yo.png" alt="logo">
            </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto">

                        <li class="nav-item">
                            <a class="nav-link" href="user_index.php" style="margin-top:8px;">All Committees</a>
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