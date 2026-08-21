<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";

include "../includes/header.php";

/* REGISTER USER */

$message = "";

if (isset($_POST["register"])) {
    /* REQUIRE CSRF TOKEN */

    require_csrf_token();

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = trim($_POST["password"] ?? "");
    $confirm_password = trim($_POST["confirm_password"] ?? "");

    if (
        empty($name) ||
        empty($email) ||
        empty($phone) ||
        empty($password) ||
        empty($confirm_password)
    ) {
        $message = "<div class='error-message'>All fields are required.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='error-message'>Please enter a valid email address.</div>";
    } elseif (!preg_match("/^[a-zA-Z .'-]+$/", $name)) {
        $message = "<div class='error-message'>Please enter a valid name.</div>";
    } elseif (!preg_match("/^[0-9+\-\s()]{7,20}$/", $phone)) {
        $message = "<div class='error-message'>Please enter a valid phone number.</div>";
    } elseif (strlen($password) < 8) {
        $message = "<div class='error-message'>Password must be at least 8 characters long.</div>";
    } elseif (!preg_match("/[A-Z]/", $password)) {
        $message = "<div class='error-message'>Password must contain at least one uppercase letter.</div>";
    } elseif (!preg_match("/[a-z]/", $password)) {
        $message = "<div class='error-message'>Password must contain at least one lowercase letter.</div>";
    } elseif (!preg_match("/[0-9]/", $password)) {
        $message = "<div class='error-message'>Password must contain at least one number.</div>";
    } elseif (strlen($password) > 72) {
        $message = "<div class='error-message'>Password must not be longer than 72 characters.</div>";
    } elseif ($password != $confirm_password) {
        $message = "<div class='error-message'>Passwords do not match.</div>";
    } else {
        /* CHECK DUPLICATE EMAIL */

        $check_stmt = mysqli_prepare(
            $conn,
            "SELECT id
             FROM users
             WHERE email = ?
             LIMIT 1"
        );

        if (!$check_stmt) {
            $message = "<div class='error-message'>Something went wrong. Please try again.</div>";
        } else {
            mysqli_stmt_bind_param($check_stmt, "s", $email);
            mysqli_stmt_execute($check_stmt);

            $check_email = mysqli_stmt_get_result($check_stmt);

            if (mysqli_num_rows($check_email) > 0) {
                $message = "<div class='error-message'>Email already exists.</div>";

                mysqli_stmt_close($check_stmt);
            } else {
                mysqli_stmt_close($check_stmt);

                /* HASH PASSWORD */

                $password_hash = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                /* INSERT NEW CUSTOMER */

                $insert_stmt = mysqli_prepare(
                    $conn,
                    "INSERT INTO users
                    (
                        name,
                        email,
                        phone,
                        password,
                        role,
                        status,
                        created_at,
                        updated_at
                    )
                    VALUES
                    (
                        ?,
                        ?,
                        ?,
                        ?,
                        'customer',
                        1,
                        NOW(),
                        NOW()
                    )"
                );

                if (!$insert_stmt) {
                    $message = "<div class='error-message'>Something went wrong. Please try again.</div>";
                } else {
                    mysqli_stmt_bind_param(
                        $insert_stmt,
                        "ssss",
                        $name,
                        $email,
                        $phone,
                        $password_hash
                    );

                    if (mysqli_stmt_execute($insert_stmt)) {
                        $message = "<div class='success-message'>Registration completed successfully. You can now login.</div>";
                    } else {
                        $message = "<div class='error-message'>Something went wrong. Please try again.</div>";
                    }

                    mysqli_stmt_close($insert_stmt);
                }
            }
        }
    }
}

?>

<!-- PAGE BANNER -->

<section class="page-banner">
    <div class="container">
        <div class="page-banner-content">
            <h1>Register</h1>
            <p>
                <a href="../index.php">Home</a> / Register
            </p>
        </div>
    </div>
</section>

<!-- REGISTER -->

<section class="register section-padding">
    <div class="container">
        <div class="register-wrapper">
            <h2>Create Your Account</h2>

            <?php echo $message; ?>

            <form method="POST">
                <!-- CSRF TOKEN -->

                <?php csrf_input(); ?>

                <div class="form-group">
                    <label>Full Name</label>

                    <input type="text" name="name" placeholder="Enter your full name" value="<?php echo isset($_POST["name"]) ? htmlspecialchars($_POST["name"]) : ""; ?>" required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>

                    <input type="email" name="email" placeholder="Enter your email address" value="<?php echo isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : ""; ?>" required>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>

                    <input type="text" name="phone" placeholder="03XXXXXXXXX" value="<?php echo isset($_POST["phone"]) ? htmlspecialchars($_POST["phone"]) : ""; ?>" required>
                </div>

                <div class="form-group">
                    <label>Password</label>

                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>

                    <input type="password" name="confirm_password" placeholder="Confirm your password" required>
                </div>

                <button type="submit" name="register" class="btn-primary" >
                    Create Account
                </button>

                <div class="register-login">
                    <p>
                        Already have an account?
                        <a href="login.php">
                            Login Here
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>

<?php

include "../includes/footer.php";

?>