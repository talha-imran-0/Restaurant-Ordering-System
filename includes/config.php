<?php

/* START SESSION SAFELY */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ERROR REPORTING */

error_reporting(E_ALL);
ini_set("display_errors", 1);

/* TIMEZONE */

date_default_timezone_set("Asia/Karachi");

/* DATABASE */

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

/* CHECK CONNECTION */

if (!$conn) {
    die(
        "Database Connection Failed: " .
        mysqli_connect_error()
    );
}

?>