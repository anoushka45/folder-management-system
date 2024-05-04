<?php
// Include your database configuration file
include '_dbconnect.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST["username"]) ? $_POST["username"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";

    $query = "SELECT * FROM user WHERE login_username = '$username' AND login_password_hashed = '$password'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Login successful
        $_SESSION['username'] = $username;
        header("Location: user_index.php"); // Redirect to the dashboard or home page
        exit();
    } else {
        // Login failed
        header("Location: userlogin.php?error=invalid_credentials"); // Redirect to the login page with an error parameter
    }

    // Close the database connection
    mysqli_close($conn);
}
?>
