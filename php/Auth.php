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
    if (
        !isset($_SESSION["admin_id"]) ||
        !isset($_SESSION["admin_role"]) ||
        $_SESSION["admin_role"] !== "admin"
    ) {

        header("Location: login.php");
        exit();

    }
}


// Check Customer Login
function require_customer()
{
    require_login();

    if ($_SESSION["user_role"] != "customer")
    {
        header("Location: ../index.php");
        exit();
    }
}
?>