<?php
// Include your database configuration file
include '_dbconnect.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST["username"]) ? $_POST["username"] : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";

    // Example query for Admin table, you can modify it based on your needs
    $query = "SELECT * FROM facultycoordinator WHERE login_username = '$username' AND login_password_hashed = '$password'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        // Login successful
        $_SESSION['admin_username'] = $username; // Prefix with 'admin_'
        header("Location: admin_index.php"); // Redirect to the dashboard or home page
        exit();
    } else {
        // Login failed
        header("Location: facultylogin.php?error=invalid_credentials"); // Redirect to the login page with an error parameter
    }

    // Close the database connection
    mysqli_close($conn);
}
?>

