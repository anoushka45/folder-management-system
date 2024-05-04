<?php
// Include your database configuration file
include '_dbconnect.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST["username"]) ? $_POST["username"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";

    // Example query for Committee table, you can modify it based on your needs
    $query = "SELECT * FROM Committee WHERE login_username = '$username' AND login_password_hashed = '$password'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Login successful
        $_SESSION['committee_username'] = $username;
        header("Location: index.php"); // Redirect to the dashboard or home page
        exit();
    } else {
        // Login failed
        header("Location: login.html?error=invalid_credentials"); // Redirect to the login page with an error parameter

        echo "Invalid username or password";
    }

    // Close the database connection
    mysqli_close($conn);
}
?>