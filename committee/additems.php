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
$committeeQuery = "SELECT committee_id FROM committee WHERE login_username = '$loggedInUser'";
$resultCommittee = mysqli_query($conn, $committeeQuery);

if ($resultCommittee) {
  $row = mysqli_fetch_assoc($resultCommittee);
  $committeeId = $row['committee_id'];

  // Check if an event ID is provided in the URL
  if (isset($_GET['event_id'])) {
    $eventId = $_GET['event_id'];
    

    // Query to fetch details of the selected event
    $eventQuery = "SELECT * FROM event WHERE event_id = '$eventId'";
    $resultEvent = mysqli_query($conn, $eventQuery);

    if ($resultEvent && mysqli_num_rows($resultEvent) > 0) {
      $event = mysqli_fetch_assoc($resultEvent);
      $eventName = $event['event_name'];

      $subfolderQuery = "SELECT * FROM event WHERE parent_event_id = '$eventId'";
      $resultSubfolders = mysqli_query($conn, $subfolderQuery);
      $notificationsQuery = "SELECT * FROM notifications WHERE  notification_type = 'item rejected' ORDER BY timestamp DESC LIMIT 10";
      $resultNotifications = mysqli_query($conn, $notificationsQuery);

      ?>


      <!DOCTYPE html>
      <html lang="en">

      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
          <?php echo $eventName; ?>
        </title>
        <!-- Include Bootstrap CSS -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Play&display=swap" rel="stylesheet"> 
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<!--FONT AWESOME-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
                integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
        <!-- Custom CSS -->
        <link rel="stylesheet" href="../style.css">
        <style>
          .folder-card {
            padding: 2px;
          }

          #createSubfolderForm {
            margin-top: 10px;
          }

          #downloadButton:hover,
          #rejectButton:hover {
            background-color: green;
            /* Darker green */
          }

         

          #b2:hover {
            background-color: green;
          }


          #downloadButton {
            background-color: #3617c2;
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
            border-radius: 5px;
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
              <li class="nav-item active">
                <a class="nav-link" href="index.php" style="margin-top:7px;">All Events</a>
              </li>

              <li class="nav-item dropdown" style="margin-top: 8px;">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown"
                  aria-haspopup="true" aria-expanded="false">
                  Notifications <span class="badge badge-primary"><?php echo mysqli_num_rows($resultNotifications); ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown"
                  style="max-height: 300px; overflow-y: auto;">
                  <!-- Check if there are any notifications -->
                  <?php if (mysqli_num_rows($resultNotifications) > 0): ?>
                    <!-- Notification items -->
                    <ul class="list-unstyled">
                      <?php while ($notification = mysqli_fetch_assoc($resultNotifications)): ?>
                        <li class="dropdown-item">
                          <strong><?php echo $notification['notification_type']; ?></strong>
                          <?php echo $notification['message']; ?>
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
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="nav-link">
                  <button type="submit" name="logout" class="btn btn-link" style="color:white;">Logout</button>
                </form>
              </li>
            </ul>
          </div>
        </nav>


        <div class="wrapper">
          <button class="btn" onclick="window.location.href='index.php'"
            style="font-size:20px; color:black; margin-left:10px; font-weight:bold;"> &#x2190; </button>
          <a href="view_rejected_items.php?event_id=<?php echo $eventId; ?>" id="b2" class="btn btn-danger">Rejected Items</a>

          <div class="main-panel">


            <h2>
              <?php echo $eventName; ?>
            </h2>

                     <!-- Display Subfolders -->
            <div class="row">
              <?php
              while ($subfolder = mysqli_fetch_assoc($resultSubfolders)) {
                ?>
                <div class="col-md-3">
                  <a href="subfolder.php?event_id=<?php echo $subfolder['event_id']; ?>" class="folder-card">
                    <div class="card-body">
                      <p class="card-title" style="color:black;"><img src="images/folder.png">
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
            <br>




            <div class="row">
              <div class="col-md-6">
                <div class="card mb-3" style="box-shadow: 5px 5px 5px gray; border-radius: 20px;">
                  <div class="card-body">
                    <h5 class="card-title">Create New Subfolder</h5>
                    <form action="addfolder.php" method="post">
                      <div class="form-group">
                        <label for="subfolderName">Subfolder Name</label>
                        <input type="text" class="form-control" id="subfolderName" name="folderName" required>
                        <!-- Pass the parent folder ID as a hidden input -->
                        <input type="hidden" name="parentFolderId" value="<?php echo $eventId; ?>">
                      </div>
                      <button type="submit" class="btn btn-success" >Create Subfolder</button>
                    </form>


                  </div>

                </div>
              </div>


              <div class="col-md-6">
                <div class="card mb-3" style="box-shadow: 5px 5px 5px gray; border-radius: 20px; padding:4px;">
                  <div class="card-body">
                    <h5 class="card-title">Upload Items to this folder</h5>
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                      <div class="form-group row">
                        <div class="col">
                          <label for="images">Upload Images</label>
                          <input type="file" class="form-control-file" id="images" name="images[]" multiple accept="image/*">
                        </div>
                        <div class="col">
                          <label for="videos">Upload Videos</label>
                          <input type="file" class="form-control-file" id="videos" name="videos[]" multiple accept="video/*">
                        </div>
                      </div>
                      <input type="hidden" name="eventId" value="<?php echo $eventId; ?>">
                      <button type="submit" class="btn btn-success" >Upload</button>
                    </form>
                  </div>
                </div>
              </div>
            </div>

            <hr>

            <!-- Check if there are uploaded images -->
            <?php
            $mediaQuery = "SELECT COUNT(*) AS num_images FROM mediafile WHERE event_id = '$eventId' AND file_type = 'photo'";
            $result = mysqli_query($conn, $mediaQuery);
            $row = mysqli_fetch_assoc($result);
            $numImages = $row['num_images'];
            ?>

            <!-- Show text and delete button only if images are present -->
            <?php if ($numImages > 0): ?>
              <h5 style="  font-weight:normal;">Images directly uploaded for this folder will appear here:</h5>
              <br>
              <!-- Display Uploaded Images -->
              <form id="downloadForm" method="post" action="process_selected_images.php?event_id=<?php echo $eventId; ?>">
                <div class="grid-container">
                  <?php
                  // Query to fetch uploaded images for the current event
                  $mediaQuery = "SELECT * FROM mediafile WHERE event_id = '$eventId' AND file_type = 'photo' AND (approval_status = 'approved' OR approval_status = 'awaiting')";
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
                        <label class="checkbox-container">
                          <input type="checkbox" name="selected_items[]" value="<?php echo $media['file_name']; ?>">
                          Select
                        </label>
                      </div>
                    </div>

                    <?php
                  }
                  ?>
                </div>
                <button id="b2" type="submit">Delete Selected Images</button>
                <button id="downloadButton" type="submit">Download Images</button>

              </form>
            <?php endif; ?>
<hr>
<!-- Check if there are uploaded videos -->
<?php
$videoQuery = "SELECT COUNT(*) AS num_videos FROM mediafile WHERE event_id = '$eventId' AND file_type = 'video'";
$result = mysqli_query($conn, $videoQuery);
$row = mysqli_fetch_assoc($result);
$numVideos = $row['num_videos'];
?>

<!-- Show text and delete button only if videos are present -->
<?php if ($numVideos > 0): ?>
  <h5 style="font-weight:lighter;">Videos directly uploaded for this folder will appear here:</h5>
  <br>
  <!-- Display Uploaded Videos -->
  <div class="row-md-2">
    <?php
    // Query to fetch uploaded videos for the current event
    $mediaQuery = "SELECT * FROM mediafile WHERE event_id = '$eventId' AND file_type = 'video' AND (approval_status = 'approved' OR approval_status = 'awaiting')";
    $resultMedia = mysqli_query($conn, $mediaQuery);

    while ($media = mysqli_fetch_assoc($resultMedia)) {
      ?>
      
      <form id="downloadForm" method="post" action="process_selected_images.php?event_id=<?php echo $eventId; ?>">

<div class="card video-card mb-2">
<div class="card-body">
  <h5 class="card-title video-title" data-src="<?php echo $media['file_name']; ?>" style="cursor: pointer;">
      <?php echo $media['file_name']; ?>
  </h5>
  
      <!-- Add any additional information or actions related to the video -->
      <label class="checkbox-container">
          <input type="checkbox" name="selected_videos[]" value="<?php echo $media['file_name']; ?>">
          Select
      </label>
  
</div>
</div>



<?php
}
?>
</div>
<button id="b2" type="submit">Delete Selected Videos</button>
</form>
<?php endif; ?>

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

        <div class="modal fade" id="slideshowModal" tabindex="-1" role="dialog" aria-labelledby="slideshowModalLabel" aria-hidden="true">
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
                            <div class="carousel-item <?php if ($active) echo 'active'; ?>">
                                <img src="../uploads/<?php echo $media['file_name']; ?>" class="d-block w-100" alt="Image">
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

<div class="modal fade" id="videoModal" tabindex="-1" role="dialog" aria-labelledby="videoModalLabel" aria-hidden="true">
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


        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
      </body>
      <script>
$(document).ready(function() {
    // Function to handle click on image
    $('.card-img-top').click(function() {
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
                            alert("Downloading successfully. Please refresh page .");
                        }
                    });
});


document.addEventListener('DOMContentLoaded', function() {
    var videoTitles = document.querySelectorAll('.video-title');

    videoTitles.forEach(function(videoTitle) {
        videoTitle.addEventListener('click', function() {
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

      </html>

      <?php
    } else {
      // Event not found
      echo "Event not found";
    }
  } else {
    // Event ID not provided in the URL
    echo "Event ID not provided";
  }
} else {
  // Unable to fetch committee details
  echo "Error fetching committee details: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);
?>