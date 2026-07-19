<?php
// Helper Functions



// Clean User Input
function clean_input($data)
{
    global $conn;

    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);

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
        echo "<p>" . $_SESSION["message"] . "</p>";

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
?>
