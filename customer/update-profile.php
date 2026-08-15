<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";
require_once "../php/Auth.php";

require_customer();

$message = "";

$user_id = $_SESSION["user_id"];

/* UPDATE PROFILE */

if (isset($_POST["update_profile"])) {
    /* REQUIRE CSRF TOKEN */

    require_csrf_token();

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");

    if (
        empty($name) ||
        empty($email) ||
        empty($phone)
    ) {
        $message = "<div class='error-message'>Please fill all fields.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='error-message'>Please enter a valid email address.</div>";
    } elseif (!preg_match("/^[a-zA-Z .'-]+$/", $name)) {
        $message = "<div class='error-message'>Please enter a valid name.</div>";
    } elseif (!preg_match("/^[0-9+\-\s()]{7,20}$/", $phone)) {
        $message = "<div class='error-message'>Please enter a valid phone number.</div>";
    } else {
        /* CHECK DUPLICATE EMAIL */

        $check_stmt = mysqli_prepare(
            $conn,
            "SELECT id
             FROM users
             WHERE email = ?
             AND id != ?
             LIMIT 1"
        );

        if (!$check_stmt) {
            $message = "<div class='error-message'>Something went wrong. Please try again.</div>";
        } else {
            mysqli_stmt_bind_param(
                $check_stmt,
                "si",
                $email,
                $user_id
            );

            mysqli_stmt_execute($check_stmt);

            $check_email = mysqli_stmt_get_result($check_stmt);

            if (mysqli_num_rows($check_email) > 0) {
                $message = "<div class='error-message'>Email already exists.</div>";
            } else {
                /* UPDATE USER PROFILE */

                $update_stmt = mysqli_prepare(
                    $conn,
                    "UPDATE users
                     SET
                        name = ?,
                        email = ?,
                        phone = ?,
                        updated_at = NOW()
                     WHERE id = ?"
                );

                if (!$update_stmt) {
                    $message = "<div class='error-message'>Something went wrong.</div>";
                } else {
                    mysqli_stmt_bind_param(
                        $update_stmt,
                        "sssi",
                        $name,
                        $email,
                        $phone,
                        $user_id
                    );

                    if (mysqli_stmt_execute($update_stmt)) {
                        $_SESSION["user_name"] = $name;
                        $_SESSION["user_email"] = $email;

                        $message = "<div class='success-message'>Profile updated successfully.</div>";
                    } else {
                        $message = "<div class='error-message'>Something went wrong.</div>";
                    }

                    mysqli_stmt_close($update_stmt);
                }
            }

            mysqli_stmt_close($check_stmt);
        }
    }
}

/* GET USER */

$get_stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM users
     WHERE id = ?
     LIMIT 1"
);

$user = null;

if ($get_stmt) {
    mysqli_stmt_bind_param(
        $get_stmt,
        "i",
        $user_id
    );

    mysqli_stmt_execute($get_stmt);

    $get_user = mysqli_stmt_get_result($get_stmt);

    $user = mysqli_fetch_assoc($get_user);

    mysqli_stmt_close($get_stmt);
}

if (!$user) {
    die("User account not found.");
}

include "../includes/header.php";

?>

<!-- PAGE BANNER -->

<section class="page-banner">
    <div class="container">
        <div class="page-banner-content">
            <h1>Update Profile</h1>
            <p>
                <a href="../index.php">Home</a> / Update Profile
            </p>
        </div>
    </div>
</section>

<!-- UPDATE PROFILE -->

<section class="profile section-padding">
    <div class="container">
        <div class="profile-wrapper">
            <div class="profile-header">
                <h2>Update Profile</h2>

                <p>
                    Keep your personal information up to date.
                </p>
            </div>

            <?php echo $message; ?>

            <form method="POST">
                <!-- CSRF TOKEN -->

                <?php csrf_input(); ?>

                <div class="form-group">
                    <label>Full Name</label>

                    <input type="text" name="name" value="<?php echo htmlspecialchars($user["name"]); ?>" required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>

                    <input type="email" name="email" value="<?php echo htmlspecialchars($user["email"]); ?>" required>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>

                    <input type="text" name="phone" value="<?php echo htmlspecialchars($user["phone"]); ?>" required>
                </div>

                <div class="profile-buttons">
                    <button type="submit" name="update_profile" class="btn-primary" >
                        <i class="fa-solid fa-floppy-disk"></i>
                        Save Changes
                    </button>

                    <a href="profile.php" class="btn-secondary" >
                        <i class="fa-solid fa-arrow-left"></i>
                        Back
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php

include "../includes/footer.php";

?>