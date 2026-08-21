<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";

include "../includes/header.php";

/* ADMIN LOGIN */
$message = "";

/* ALREADY LOGGED-IN ADMIN */
if (
    isset($_SESSION["admin_id"]) &&
    isset($_SESSION["admin_role"]) &&
    $_SESSION["admin_role"] === "admin"
) {
    header("Location: dashboard.php");
    exit();
}

/* LOGIN ATTEMPT PROTECTION */
$max_login_attempts = 5;
$lockout_time = 15 * 60;

/* CREATE LOGIN ATTEMPT SESSION VALUES */
if (!isset($_SESSION["admin_login_attempts"])) {
    $_SESSION["admin_login_attempts"] = 0;
}

if (!isset($_SESSION["admin_login_lockout"])) {
    $_SESSION["admin_login_lockout"] = 0;
}

/* CHECK LOGIN LOCKOUT */
if ($_SESSION["admin_login_lockout"] > time()) {
    $remaining_time = ceil(
        ($_SESSION["admin_login_lockout"] - time()) / 60
    );

    $message = "<div class='error-message'>
                    Too many failed login attempts.
                    Please try again in " . $remaining_time . " minutes.
                </div>";
}

/* LOGIN */
if (
    isset($_POST["login"]) &&
    $_SESSION["admin_login_lockout"] <= time()
) {
    /* CSRF PROTECTION */
    require_csrf_token();

    $email    = trim($_POST["email"] ?? "");
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
        /* SECURE ADMIN LOGIN QUERY */
        $stmt = mysqli_prepare(
            $conn,
            "SELECT *
             FROM users
             WHERE email = ?
             AND role = 'admin'
             LIMIT 1"
        );

        if (!$stmt) {
            error_log(
                "Admin login query preparation failed: " . mysqli_error($conn)
            );

            $message = "<div class='error-message'>
                            Something went wrong.
                            Please try again.
                        </div>";
        } else {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);

            /* ADMIN FOUND */
            if (mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_assoc($result);

                /* CHECK ACCOUNT STATUS */
                if ($user["status"] != "active" && $user["status"] != 1) {
                    $_SESSION["admin_login_attempts"]++;

                    $message = "<div class='error-message'>
                                    Invalid email or password.
                                </div>";
                } else {
                    /* VERIFY PASSWORD */
                    if (password_verify($password, $user["password"])) {
                        /* RESET LOGIN ATTEMPTS */
                        $_SESSION["admin_login_attempts"] = 0;
                        $_SESSION["admin_login_lockout"]  = 0;

                        /* PREVENT SESSION FIXATION */
                        session_regenerate_id(true);

                        /* SET ADMIN SESSION */
                        $_SESSION["admin_id"]    = $user["id"];
                        $_SESSION["admin_name"]  = $user["name"];
                        $_SESSION["admin_email"] = $user["email"];
                        $_SESSION["admin_role"]  = $user["role"];

                        /* REMOVE CUSTOMER SESSION */
                        unset($_SESSION["user_id"]);
                        unset($_SESSION["user_name"]);
                        unset($_SESSION["user_email"]);
                        unset($_SESSION["user_role"]);

                        header("Location: dashboard.php");
                        exit();
                    } else {
                        /* FAILED LOGIN */
                        $_SESSION["admin_login_attempts"]++;

                        // Small delay after failed login.
                        sleep(2);

                        /* LOCK AFTER MAXIMUM ATTEMPTS */
                        if ($_SESSION["admin_login_attempts"] >= $max_login_attempts) {
                            $_SESSION["admin_login_lockout"]  = time() + $lockout_time;
                            $_SESSION["admin_login_attempts"] = 0;

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
            } else {
                /* ADMIN NOT FOUND */
                $_SESSION["admin_login_attempts"]++;

                // Small delay after failed login.
                sleep(2);

                /* LOCK AFTER MAXIMUM ATTEMPTS */
                if ($_SESSION["admin_login_attempts"] >= $max_login_attempts) {
                    $_SESSION["admin_login_lockout"]  = time() + $lockout_time;
                    $_SESSION["admin_login_attempts"] = 0;

                    $message = "<div class='error-message'>
                                    Too many failed login attempts.
                                    Please try again in 15 minutes.
                                </div>";
                } else {
                    // Same message prevents email/account enumeration.
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

<!-- ADMIN LOGIN -->
<section class="login section-padding">
    <div class="container">
        <div class="login-wrapper">
            <h2>Admin Login</h2>

            <?php echo $message; ?>

            <form method="POST">
                <!-- CSRF TOKEN -->
                <?php csrf_input(); ?>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="Enter your admin email address" value="<?php echo isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : ""; ?>" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>

                <button type="submit" name="login" class="btn-primary">
                    Login
                </button>

                <div class="login-register">
                    <p>Admin account required.</p>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include "../includes/footer.php"; ?>