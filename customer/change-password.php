<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";
require_once "../php/Auth.php";

require_customer();

$message = "";
$user_id = $_SESSION["user_id"];

/* CHANGE PASSWORD */
if (isset($_POST["change_password"])) {
	/* CSRF PROTECTION */
	require_csrf_token();

	$current_password = trim($_POST["current_password"] ?? "");
	$new_password = trim($_POST["new_password"] ?? "");
	$confirm_password = trim($_POST["confirm_password"] ?? "");

	if (
		empty($current_password) ||
		empty($new_password) ||
		empty($confirm_password)
	) {
		$message = "<div class='error-message'>Please fill all fields.</div>";
	} elseif (strlen($new_password) < 8) {
		$message = "<div class='error-message'>Password must be at least 8 characters long.</div>";
	} elseif (!preg_match("/[A-Z]/", $new_password)) {
		$message = "<div class='error-message'>Password must contain at least one uppercase letter.</div>";
	} elseif (!preg_match("/[a-z]/", $new_password)) {
		$message = "<div class='error-message'>Password must contain at least one lowercase letter.</div>";
	} elseif (!preg_match("/[0-9]/", $new_password)) {
		$message = "<div class='error-message'>Password must contain at least one number.</div>";
	} elseif (strlen($new_password) > 72) {
		$message = "<div class='error-message'>Password must not be longer than 72 characters.</div>";
	} elseif ($new_password != $confirm_password) {
		$message = "<div class='error-message'>Passwords do not match.</div>";
	} elseif ($current_password == $new_password) {
		$message = "<div class='error-message'>New password must be different from your current password.</div>";
	} else {
		/* GET CURRENT USER PASSWORD */
		$get_stmt = mysqli_prepare(
			$conn,
			"SELECT password FROM users WHERE id = ? LIMIT 1"
		);

		if (!$get_stmt) {
			$message = "<div class='error-message'>Something went wrong. Please try again.</div>";
		} else {
			mysqli_stmt_bind_param($get_stmt, "i", $user_id);
			mysqli_stmt_execute($get_stmt);

			$get_user = mysqli_stmt_get_result($get_stmt);
			$user = mysqli_fetch_assoc($get_user);
			mysqli_stmt_close($get_stmt);

			if (!$user) {
				$message = "<div class='error-message'>User account not found.</div>";
			} elseif (!password_verify($current_password, $user["password"])) {
				$message = "<div class='error-message'>Current password is incorrect.</div>";
			} else {
				/* HASH NEW PASSWORD */
				$new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

				/* UPDATE PASSWORD */
				$update_stmt = mysqli_prepare(
					$conn,
					"UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?"
				);

				if (!$update_stmt) {
					$message = "<div class='error-message'>Something went wrong.</div>";
				} else {
					mysqli_stmt_bind_param($update_stmt, "si", $new_password_hash, $user_id);

					if (mysqli_stmt_execute($update_stmt)) {
						$message = "<div class='success-message'>Password changed successfully.</div>";
					} else {
						$message = "<div class='error-message'>Something went wrong.</div>";
					}

					mysqli_stmt_close($update_stmt);
				}
			}
		}
	}
}

include "../includes/header.php";

?>

<!-- PAGE BANNER -->
<section class="page-banner">
	<div class="container">
		<div class="page-banner-content">
			<h1>Change Password</h1>
			<p>
				<a href="../index.php">Home</a> / Change Password
			</p>
		</div>
	</div>
</section>

<!-- CHANGE PASSWORD -->
<section class="profile section-padding">
	<div class="container">
		<div class="profile-wrapper">
			<div class="profile-header">
				<h2>Change Password</h2>
				<p>Choose a strong password to keep your account secure.</p>
			</div>

			<?php echo $message; ?>

			<form method="POST">
				<!-- CSRF TOKEN -->
				<?php csrf_input(); ?>

				<div class="form-group">
					<label>Current Password</label>
					<input type="password" name="current_password" placeholder="Enter current password" required>
				</div>

				<div class="form-group">
					<label>New Password</label>
					<input type="password" name="new_password" placeholder="Enter new password" required>
				</div>

				<div class="form-group">
					<label>Confirm Password</label>
					<input type="password" name="confirm_password" placeholder="Confirm new password" required>
				</div>

				<div class="profile-buttons">
					<button type="submit" name="change_password" class="btn-primary">
						<i class="fa-solid fa-key"></i> Change Password
					</button>

					<a href="profile.php" class="btn-secondary">
						<i class="fa-solid fa-arrow-left"></i> Back
					</a>
				</div>
			</form>
		</div>
	</div>
</section>

<?php
include "../includes/footer.php";
?>