<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";

/* LOGOUT */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["logout"])) {

    /* CSRF PROTECTION */
    require_csrf_token();

    /* DESTROY SESSION */
    session_unset();
    session_destroy();

    /* REDIRECT */
    header("Location: ../index.php");
    exit();
}

/* REJECT DIRECT GET ACCESS */
http_response_code(405);
exit("Method Not Allowed");

?>