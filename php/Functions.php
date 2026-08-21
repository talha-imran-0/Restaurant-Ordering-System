<?php

// Helper Functions


// Clean User Input
function clean_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);

    return $data;
}


// Redirect to Another Page
function redirect($page)
{
    header("Location: " . $page);
    exit();
}


// Set Success or Error Message
function set_message($message)
{
    $_SESSION["message"] = $message;
}


// Show Message
function show_message()
{
    if (isset($_SESSION["message"]))
    {
        echo "<p>" . htmlspecialchars($_SESSION["message"]) . "</p>";

        unset($_SESSION["message"]);
    }
}


// Check Login
function is_logged_in()
{
    if (isset($_SESSION["user_id"]))
    {
        return true;
    }

    return false;
}


// Validate Email
function is_valid_email($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        return true;
    }

    return false;
}


// Check Empty Field
function is_empty($data)
{
    if (empty($data))
    {
        return true;
    }

    return false;
}



// CSRF PROTECTION



// Generate CSRF Token
function generate_csrf_token()
{
    if (empty($_SESSION["csrf_token"]))
    {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }

    return $_SESSION["csrf_token"];
}


// Verify CSRF Token
function verify_csrf_token($token)
{
    if (
        empty($token) ||
        empty($_SESSION["csrf_token"])
    ) {
        return false;
    }

    return hash_equals(
        $_SESSION["csrf_token"],
        $token
    );
}


// Add CSRF Token To Form
function csrf_input()
{
    $token = generate_csrf_token();

    echo '<input type="hidden" name="csrf_token" value="' .
         htmlspecialchars($token, ENT_QUOTES, "UTF-8") .
         '">';
}


// Require Valid CSRF Token
function require_csrf_token()
{
    if ($_SERVER["REQUEST_METHOD"] !== "POST")
    {
        http_response_code(405);
        exit("Method Not Allowed");
    }

    $token = $_POST["csrf_token"] ?? "";

    if (!verify_csrf_token($token))
    {
        http_response_code(403);
        exit("Invalid CSRF token.");
    }
}

?>