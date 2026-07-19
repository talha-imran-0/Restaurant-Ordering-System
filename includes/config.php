<?php
session_start();
// Show Errors (Source : Google)
error_reporting(E_ALL);
ini_set("display_errors", 1);



// Set Timezone (Source : Google)
date_default_timezone_set("Asia/Karachi");


$server_name = "localhost";
$user_name = "root";
$password = "";
$database_name = "restaurant_ordering_system";


$conn = mysqli_connect(
    $server_name,
    $user_name,
    $password,
    $database_name
);


// Check Connection
if (!$conn)
{
    die("Database Connection Failed: " . mysqli_connect_error());
}

?>
