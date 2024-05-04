<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f1f1f1;
            margin: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .navbar {
            background-color: #b7202e;
            color: white;
            font-size: larger;
            width: 100%;
            margin-bottom: auto;
        }

        .navbar-toggler-icon {
            background-color: none;
        }

        .nav-item {
            color: white;
        }

        form {
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
            margin: auto;
        }

        h2 {
            color: #060101;
            margin-bottom: 30px;
        }

        label {
            display: block;
            margin: 15px 0 8px;
            color: #555;
            font-size: 14px;
            text-align: left;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 8px 0 20px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input:focus {
            border-color: #4caf50;
        }

        

        button {
            background-color: #b7202e;
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .footer {
            background-color: #333; /* Set your desired background color */
            color: #fff; /* Set your desired text color */
            padding: 15px 0;
            text-align: center;
        }

        .footer img {
            max-width: 100%;
            height: auto;
        }

        .social-links a {
            color: #fff;
            margin: 0 10px;
        }

        /* Additional Styling for Responsive Design */
        @media (max-width: 400px) {
            form {
                width: 90%;
            }
        }
/* Define the keyframes for the fade-in animation */
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

/* Apply the fade-in animation to the login form */
.fade-in {
    animation: fadeIn 1s ease-in-out;
}

        
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">
            <img src="images/kjsit-logo.svg" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Login
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="../user/userlogin.php">User Login</a>
                        <a class="dropdown-item" href="facultylogin.php">Admin Login</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../committee/login.html">Committee login</a>
                    </div>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="facultylogin.php">Admin</a>
                </li>
               
                
            </ul>
        </div>
    </nav>

    <form id="login-form" action="faculty_login.php" method="post" class="fade-in">
    <h2 style="font-weight: lighter;">Admin Login</h2>
    <div id="error-message" style="display: none; color: red; text-align: center;">Incorrect username or password.</div>

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

   

    <button type="submit">Login</button>
</form>


    <div class="footer">
        <div class="container">
            <img src="images/kjsit-logo.svg" alt="Logo">
            <div class="social-links">
                
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
        // Check if there's an error parameter in the URL
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');
        if (error === 'invalid_credentials') {
            // Display the error message container
            document.getElementById('error-message').style.display = 'block';
        }
    </script>
</body>
</html>
