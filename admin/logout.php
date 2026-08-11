<?php

require_once "../includes/config.php";

// Destroy Admin Session
unset($_SESSION['admin_id']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_email']);
unset($_SESSION['admin_role']);

session_destroy();

// Redirect To Customer Login
header("Location: ../customer/login.php");
exit();

?>