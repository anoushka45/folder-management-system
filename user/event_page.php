<?php
// Include your database configuration file
include '_dbconnect.php';

// Start the session
session_start();

// Check if the user clicked the logout button
if (isset($_POST['logout'])) {
    // Unset all session variables

    unset($_SESSION['username']);

    // Redirect the user to the login page
    header("Location:userlogin.php");
    exit();
}


// Check if there are any notifications

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location:userlogin.php");
    exit();
}
// Ch
if (isset($_GET['event_id'])) {
    $eventId = $_GET['event_id'];




    // Query to fetch details of the current subfolder
    $subfolderQuery = "SELECT * FROM event WHERE event_id = '$eventId'";
    $resultSubfolder = mysqli_query($conn, $subfolderQuery);

    if ($resultSubfolder && mysqli_num_rows($resultSubfolder) > 0) {
        $subfolder = mysqli_fetch_assoc($resultSubfolder);
        $subfolderName = $subfolder['event_name'];

        // Query to fetch subfolders of the current subfolder
        $subfolderQuery = "SELECT * FROM event WHERE parent_event_id = '$eventId'";
        $resultSubfolders = mysqli_query($conn, $subfolderQuery);
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <!-- Add your HTML head content here -->
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>
                <?php echo $subfolderName; ?>
            </title>
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
            <!-- Include Bootstrap CSS -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
            <!-- Custom CSS -->
            <link rel="stylesheet" href="../style.css">

            <style>
                #b1 {
                    background-color: #4CAF50;
                    /* Green */
                    border: none;
                    color: white;
                    padding: 5px 15px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 16px;
                    margin: 4px 2px;
                    cursor: pointer;
                    border-radius: 10px;
                }

                #downloadButton:hover,
                #rejectButton:hover {
                    background-color: green;
                    /* Darker green */
                }


                #downloadButton {
                    background-color: #b7202e;
                    /* Green */
                    border: none;
                    color: white;
                    padding: 10px 10px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 16px;
                    margin: 10px 2px;
                    cursor: pointer;
                    border-radius: 10px;
                }

                #rejectButton {
                    background-color: #b7202e;
                    /* Green */
                    border: none;
                    color: white;
                    padding: 10px 10px;
                    text-align: center;
                    text-decoration: none;
                    display: inline-block;
                    font-size: 16px;
                    margin: 10px 2px;
                    cursor: pointer;
                    border-radius: 10px;
                }

                .folder-card {
                    padding: 2px;
                }
            </style>
        </head>

        <body>
            <!-- Include your navbar here -->
            <!-- You can copy the navbar code from index.php -->

            <nav class="navbar navbar-expand-lg navbar-dark">
                <a class="navbar-brand" href="#">
                    <img src="images/yo.png" alt="Logo">
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


<?php
                $numImages = 0;

// Query to fetch the number of images
$mediaQuery = "SELECT COUNT(*) AS num_images FROM mediafile WHERE event_id = '$eventId' AND file_type = 'photo' and approval_status= 'approved'";
$result = mysqli_query($conn, $mediaQuery);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $numImages = $row['num_images'];
}

$videoQuery = "SELECT COUNT(*) AS num_videos FROM mediafile WHERE event_id = '$eventId' AND file_type = 'video' AND approval_status = 'approved'";
$result = mysqli_query($conn, $videoQuery);
$row = mysqli_fetch_assoc($result);
$numVideos = $row['num_videos'];

?>

<?php if (mysqli_num_rows($resultSubfolders) == 0 && $numImages == 0 && $numVideos == 0): ?>
<div class="d-flex justify-content-center align-items-center"
style="height: 300px; border: 2px dashed #ccc; border-radius: 10px; background-color: #f9f9f9;">
<p style="font-size: 20px; color: #888;">No items uploaded for this Event!</p>
</div>
                    <?php else: ?>
                       
                        <h2><?php echo $subfolderName; ?></h2>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <?php
                                    while ($subfolder = mysqli_fetch_assoc($resultSubfolders)) {
                                        ?>
                                        <div class="col-md-3">
                                            <a href="subfolder_view.php?event_id=<?php echo $subfolder['event_id']; ?>"
                                                class="folder-card">

                                                <div class="card-body">
                                                    <p class="card-title" style="color: black; "><img src="images/folder.png">
                                                        <?php echo $subfolder['event_name']; ?>
                                                    </p>
                                                    <!-- Add any additional information or actions related to the subfolder -->
                                                </div>
                                            </a>

                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>



                        <?php if ($numImages > 0): ?>
                            
                            <hr>

                            <!-- Display Uploaded Images -->
                            <form id="downloadForm" method="post"
                                action="process_selected_images_admin.php?event_id=<?php echo $eventId; ?>">

                                <h5 style=" font-weight:lighter;">Images directly uploaded for this folder will appear here:
                                </h5>
                                <br>

                                <div class="grid-container">
                                    <?php
                                    // Query to fetch uploaded images for the current event
                                    $mediaQuery = "SELECT * FROM mediafile WHERE event_id = '$eventId' AND file_type = 'photo' AND approval_status = 'approved' ";
                                    $resultMedia = mysqli_query($conn, $mediaQuery);

                                    while ($media = mysqli_fetch_assoc($resultMedia)) {
                                        ?>
                                        <div class="card mb-3">
                                            <img src="../uploads/<?php echo $media['file_name']; ?>" class="card-img-top image-slide"
                                                alt="Uploaded Image" data-toggle="modal" data-target="#slideshowModal"
                                                data-image="<?php echo $media['file_name']; ?>">
                                            <div class="card-body">
                                                <h5 class="card-title">
                                                    <?php echo $media['file_name']; ?>
                                                </h5>
                                                <!-- Add any additional information or actions related to the image -->
                                                <div class="checkbox-container">
                                                    <input type="checkbox" name="selected_items[]"
                                                        value="<?php echo $media['file_name']; ?>">
                                                    <label for="selected_items[]">Select</label>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <button id="downloadButton" type="button">Download Images</button>
                            </form>

                        <?php endif; ?>

                        <hr>
                       
                        <?php if ($numVideos > 0): ?>
                            <h5 style=" font-weight:lighter;">Videos directly uploaded for this folder will appear here:</h5>
                            <br>
                            <!-- Display Uploaded Videos -->
                            <div class="row-md-3 p-2">
                                <?php
                                // Query to fetch uploaded videos for the current event
                                $mediaQuery = "SELECT * FROM mediafile WHERE event_id = '$eventId' AND file_type = 'video' AND approval_status = 'approved'";
                                $resultMedia = mysqli_query($conn, $mediaQuery);

                                while ($media = mysqli_fetch_assoc($resultMedia)) {
                                    ?>

                                    <form id="downloadForm" method="post"
                                        action="process_selected_images_admin.php?event_id=<?php echo $eventId; ?>">

                                        <div class="  card video-card mb-2 ">
                                            <div class="card-body">
                                                <h5 class="card-title video-title" data-src="<?php echo $media['file_name']; ?>"
                                                    style="cursor: pointer;">
                                                    <?php echo $media['file_name']; ?>
                                                </h5>
                                                
                                            </div>
                                        </div>



                                        <?php
                                }
                                ?>
                            </div>
                            </form>


                        <?php endif; ?>
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
            <!-- Slideshow Modal -->
            <div class="modal fade" id="slideshowModal" tabindex="-1" role="dialog" aria-labelledby="slideshowModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    <?php
                                    // Loop through images and generate carousel items
                                    $resultMedia = mysqli_query($conn, $mediaQuery);
                                    $active = true; // Set the first image as active
                                    while ($media = mysqli_fetch_assoc($resultMedia)) {
                                        ?>
                                        <div class="carousel-item <?php if ($active)
                                            echo 'active'; ?>">
                                            <img src="../uploads/<?php echo $media['file_name']; ?>" class="d-block w-100"
                                                alt="Image">
                                        </div>
                                        <?php
                                        $active = false; // Set subsequent images as inactive
                                    }
                                    ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="videoModalLabel">Video</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="videoModalBody">
                            <video id="videoModalVideo" controls style="width: 100%;">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Your existing footer code -->

            <!-- Include Bootstrap JS -->
            <!-- Include jQuery -->
            <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

            <!-- Include Bootstrap JS -->
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

            <!-- JavaScript for Slideshow -->
            <script>
                $(document).ready(function () {
                    // Function to handle click on image
                    $('.card-img-top').click(function () {
                        var imageSrc = $(this).attr('src');
                        var img = $('<img>').attr('src', imageSrc).addClass('d-block w-100');
                        var item = $('<div>').addClass('carousel-item active').append(img);
                        $('#slideshowModal .carousel-inner').empty().append(item);
                        $('#slideshowModal').modal('show');
                    });

                    // Function to handle click on download button
                    $('#downloadButton').click(function () {
                        // Check if any images are selected
                        if ($('input[name="selected_items[]"]:checked').length === 0) {
                            // Display alert if no images are selected
                            alert('No images selected for downloading!');
                        } else {
                            // Submit the form if images are selected
                            var form = $('#downloadForm');
                            form.attr('action', '../download_selected_images.php'); // Update action attribute
                            form.submit();
                        }
                    });
                });


                document.addEventListener('DOMContentLoaded', function () {
                    var videoTitles = document.querySelectorAll('.video-title');

                    videoTitles.forEach(function (videoTitle) {
                        videoTitle.addEventListener('click', function () {
                            var videoSrc = '../uploads/videos/' + this.getAttribute('data-src');
                            var modalBody = document.getElementById('videoModalBody');
                            var modalVideo = document.getElementById('videoModalVideo');

                            // Set video source and display modal
                            modalVideo.src = videoSrc;
                            $('#videoModal').modal('show');
                        });
                    });
                });

            </script>
        </body>


        </html>

        <?php
    } else {
        // Subfolder not found
        echo "Subfolder not found";
    }
} else {
    // Event ID not provided in the URL
    echo "Event ID not provided";
}

// Close the database connection
mysqli_close($conn);
?>