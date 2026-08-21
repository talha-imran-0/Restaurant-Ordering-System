<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";
require_once "../php/Auth.php";

require_customer();

/* GET USER */

$user_id = $_SESSION["user_id"];

$get_stmt = mysqli_prepare(
    $conn,
    "SELECT *
     FROM users
     WHERE id = ?
     LIMIT 1"
);

$user = null;

if ($get_stmt) {
    mysqli_stmt_bind_param($get_stmt, "i", $user_id);
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
            <h1>My Profile</h1>
            <p><a href="../index.php">Home</a> / My Profile</p>
        </div>
    </div>
</section>

<!-- PROFILE -->

<section class="profile section-padding">
    <div class="container">
        <div class="profile-wrapper">
            <div class="profile-header">
                <h2>
                    Welcome,
                    <?php echo htmlspecialchars($user["name"]); ?>
                </h2>

                <p>Manage your account information.</p>
            </div>

            <div class="profile-card">
                <div class="profile-item">
                    <label>Full Name</label>
                    <p><?php echo htmlspecialchars($user["name"]); ?></p>
                </div>

                <div class="profile-item">
                    <label>Email Address</label>
                    <p><?php echo htmlspecialchars($user["email"]); ?></p>
                </div>

                <div class="profile-item">
                    <label>Phone Number</label>
                    <p><?php echo htmlspecialchars($user["phone"]); ?></p>
                </div>

                <div class="profile-item">
                    <label>Account Type</label>
                    <p><?php echo htmlspecialchars(ucfirst($user["role"])); ?></p>
                </div>

                <div class="profile-item">
                    <label>Member Since</label>
                    <p><?php echo date("d M Y", strtotime($user["created_at"])); ?></p>
                </div>
            </div>

            <div class="profile-buttons">
                <a href="update-profile.php" class="btn-primary" >
                    Update Profile
                </a>

                <a href="change-password.php" class="btn-change-password btn-secondary" >
                    Change Password
                </a>

                <a href="order_tracking.php" class="btn-primary" >
                    My Orders
                </a>
            </div>
        </div>
    </div>
</section>

<?php

include "../includes/footer.php";

?>