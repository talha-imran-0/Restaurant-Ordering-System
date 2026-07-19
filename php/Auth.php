<?php
// Authentication Functions


// Check User Login
function require_login()
{
    if (!isset($_SESSION["user_id"]))
    {
        header("Location: login.php");
        exit();
    }
}


// Check Admin Login
function require_admin()
{
    require_login();

    if ($_SESSION["role"] != "admin")
    {
        header("Location: ../index.php");
        exit();
    }
}


// Check Customer Login
function require_customer()
{
    require_login();

    if ($_SESSION["role"] != "customer")
    {
        header("Location: ../index.php");
        exit();
    }
}
?>
