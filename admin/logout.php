<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";

/* REQUIRE POST + CSRF TOKEN */
require_csrf_token();

/* DESTROY LOGIN SESSION */
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_email']);
unset($_SESSION['user_role']);

unset($_SESSION['admin_id']);
unset($_SESSION['admin_name']);
unset($_SESSION['admin_email']);
unset($_SESSION['admin_role']);

session_destroy();

/* REDIRECT TO HOME PAGE */
header("Location: ../index.php");
exit();

?>