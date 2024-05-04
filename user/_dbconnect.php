<?php
$servername = "localhost";
$username = "root";
$password = '';
$database = "kjsit";
$port = 3307;

$conn = mysqli_connect($servername, $username, $password, $database, $port);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Do not close the connection here

// You can include this file in other PHP scripts and use $conn for database operations
?>
