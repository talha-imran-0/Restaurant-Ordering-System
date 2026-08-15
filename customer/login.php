<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";

include "../includes/header.php";

/* LOGIN USER */
$message = "";

/* LOGIN ATTEMPT PROTECTION */
$max_login_attempts = 5;
$lockout_time = 15 * 60;

/* CREATE LOGIN ATTEMPT SESSION VALUES */
if (!isset($_SESSION["login_attempts"])) {
    $_SESSION["login_attempts"] = 0;
}

if (!isset($_SESSION["login_lockout"])) {
    $_SESSION["login_lockout"] = 0;
}

/* CHECK LOGIN LOCKOUT */
if ($_SESSION["login_lockout"] > time()) {
    $remaining_time = ceil(($_SESSION["login_lockout"] - time()) / 60);

    $message = "<div class='error-message'>
        Too many failed login attempts.
        Please try again in " . $remaining_time . " minutes.
    </div>";
}

/* LOGIN */
if (isset($_POST["login"]) && $_SESSION["login_lockout"] <= time()) {

    /* CSRF PROTECTION */
    require_csrf_token();

    $email = trim($_POST["email"] ?? "");
    $password = trim($_POST["password"] ?? "");

    /* VALIDATE INPUT */
    if (empty($email) || empty($password)) {
        $message = "<div class='error-message'>
            Please fill all fields.
        </div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='error-message'>
            Please enter a valid email address.
        </div>";
    } else {

        /* SECURE LOGIN QUERY */
        $stmt = mysqli_prepare(
            $conn,
            "SELECT * FROM users WHERE email = ? LIMIT 1"
        );

        if (!$stmt) {
            error_log("Login query preparation failed: " . mysqli_error($conn));

            $message = "<div class='error-message'>
                Something went wrong.
                Please try again.
            </div>";
        } else {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            /* USER FOUND */
            if (mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);

                /* CHECK ACCOUNT STATUS */
                if ($user["status"] != "active" && $user["status"] != 1) {
                    $_SESSION["login_attempts"]++;

                    /* Keep same error message for account enumeration protection. */
                    $message = "<div class='error-message'>
                        Invalid email or password.
                    </div>";
                } else {

                    /* VERIFY PASSWORD */
                    if (password_verify($password, $user["password"])) {

                        /* RESET LOGIN ATTEMPTS */
                        $_SESSION["login_attempts"] = 0;
                        $_SESSION["login_lockout"] = 0;

                        /* PREVENT SESSION FIXATION */
                        session_regenerate_id(true);

                        /* ADMIN ACCOUNT USED ON CUSTOMER LOGIN */
                        if ($user["role"] === "admin") {
                            header("Location: ../admin/login.php");
                            exit();
                        }

                        /* CUSTOMER LOGIN */
                        $_SESSION["user_id"] = $user["id"];
                        $_SESSION["user_name"] = $user["name"];
                        $_SESSION["user_email"] = $user["email"];
                        $_SESSION["user_role"] = $user["role"];

                        /* REMOVE ADMIN SESSION */
                        unset($_SESSION["admin_id"]);
                        unset($_SESSION["admin_name"]);
                        unset($_SESSION["admin_email"]);
                        unset($_SESSION["admin_role"]);

                        /* CUSTOMER LOGIN SUCCESS */
                        header("Location: ../index.php");
                        exit();
                    } else {

                        /* FAILED LOGIN */
                        $_SESSION["login_attempts"]++;

                        /* Small delay after failed login. */
                        sleep(2);

                        /* LOCK AFTER MAXIMUM ATTEMPTS */
                        if ($_SESSION["login_attempts"] >= $max_login_attempts) {
                            $_SESSION["login_lockout"] = time() + $lockout_time;
                            $_SESSION["login_attempts"] = 0;

                            $message = "<div class='error-message'>
                                Too many failed login attempts.
                                Please try again in 15 minutes.
                            </div>";
                        } else {
                            $message = "<div class='error-message'>
                                Invalid email or password.
                            </div>";
                        }
                    }
                }
            } /* USER NOT FOUND */ else {
                $_SESSION["login_attempts"]++;

                /* Small delay after failed login. */
                sleep(2);

                /* LOCK AFTER MAXIMUM ATTEMPTS */
                if ($_SESSION["login_attempts"] >= $max_login_attempts) {
                    $_SESSION["login_lockout"] = time() + $lockout_time;
                    $_SESSION["login_attempts"] = 0;

                    $message = "<div class='error-message'>
                        Too many failed login attempts.
                        Please try again in 15 minutes.
                    </div>";
                } else {

                    /* Same message as wrong password. This prevents email enumeration. */
                    $message = "<div class='error-message'>
                        Invalid email or password.
                    </div>";
                }
            }

            mysqli_stmt_close($stmt);
        }
    }
}

?>

<!-- PAGE BANNER -->
<section class="page-banner">
    <div class="container">
        <div class="page-banner-content">
            <h1>Login</h1>
            <p>
                <a href="../index.php">Home</a> / Login
            </p>
        </div>
    </div>
</section>

<!-- LOGIN -->
<section class="login section-padding">
    <div class="container">
        <div class="login-wrapper">
            <h2>Welcome Back</h2>

            <?php echo $message; ?>

            <form method="POST">

                <!-- CSRF TOKEN -->
                <?php csrf_input(); ?>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="Enter your email address" value="<?php echo isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : ""; ?>" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>

                <button type="submit" name="login" class="btn-primary">Login</button>

                <div class="login-register">
                    <p>
                        Don't have an account?
                        <a href="register.php">Register Here</a>
                    </p>
                </div>

            </form>
        </div>
    </div>
</section>

<?php

include "../includes/footer.php";

?>