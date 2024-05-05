<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!--GOOGLE FONTS-->
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Play&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<!--FONT AWESOME-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        body {
            background: #f1f1f1;
            margin: 0;
            display: flex;
            flex-direction: column;
            height: 100vh;
            background-image: url(https://images.unsplash.com/photo-1619252584172-a83a949b6efd?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D);

        }

        .navbar {
            background-color: #3e1adf;
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
            font-family: 'Roboto', sans-serif;

            background-color: #fff;
            padding: 50px;
            border-radius: 30px;
            box-shadow: 10px 10px 20px rgba(85, 85, 248, 0.1);
            width: 400px;
            margin: auto;
            margin-top: 30px;
            text-align: left;
        }


        h2 {
            color: #1c1b78;
            font-size: 30px;
            font-weight: thin;
            margin-bottom: 20px;
            text-align: center;
        }

        label {
            color: #555;
            font-size: 15px;
            margin-bottom: 8px;
            display: block;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }

        input:focus {
            border-color: #4caf50;
            outline: none;
        }

        .forgot-password {
            color: #555;
            font-size: 14px;
            text-align: right;
            margin-top: -10px;
            margin-bottom: 20px;
            display: block;
        }

        button {
            background-color: #3e1adf;
            color: white;
            padding: 15px 25px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
            width: 100%;
            display: block;
        }

        button:hover {
            background-color: #45a049;
        }

        @media (max-width: 400px) {
            form {
                width: 90%;
            }
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



        .footer {
            background-color: #1d1f21;
            color: #ffffff;
            padding: 30px 0;
            text-align: center;
            margin-top: 50px;
        }

        .social-links {
            margin-bottom: 20px;
        }

        .social-links a {
            color: #ffffff;
            margin: 0 10px;
            font-size: 24px;
        }
        .navbar-dark .navbar-brand img {
  max-height: 20px;
  width: auto;
  object-fit: cover;
}
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <a class="navbar-brand" href="#">
        <img src="images/yo.png" alt="logo">
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
                        <a class="dropdown-item" href="userlogin.php">User Login</a>
                        <a class="dropdown-item" href="../admin/facultylogin.php">Admin Login</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../committee/login.html">Committee login</a>
                    </div>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="#">User login</a>
                </li>
                
               
            </ul>
        </div>
    </nav>

    <form id="login-form" action="user_login.php" method="post" class="fade-in">
    <h2 style="font-weight: lighter;">User Login</h2>
    <div id="error-message" style="display: none; color: red; text-align: center;">Incorrect username or password.</div>

    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    

    <button type="submit">Login</button>
</form>


<footer class="footer">
        <div class="container">
            
            <div class="social-links">
                <a href="https://github.com/yourgithub"><i class="fa fa-github"></i></a>
                <a href="https://linkedin.com/in/yourlinkedin"><i class="fa fa-linkedin"></i></a>
            </div>
           
        </div>
        <div class="text-center">
            <p>&copy; 2024 Anoushka Vyas. All rights reserved.</p>
        </div>    </footer>

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
