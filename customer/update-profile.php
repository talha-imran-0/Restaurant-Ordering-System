<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";
require_once "../php/Auth.php";

require_customer();

$message = "";

$user_id = $_SESSION["user_id"];


/* UPDATE PROFILE */

if (isset($_POST["update_profile"])) {

	$name = trim($_POST["name"]);
	$email = trim($_POST["email"]);
	$phone = trim($_POST["phone"]);

	if (
		empty($name) ||
		empty($email) ||
		empty($phone)
	) {
		$message = "<div class='error-message'>Please fill all fields.</div>";
	}
	elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$message = "<div class='error-message'>Please enter a valid email address.</div>";
	}
	else {

		$check_email = mysqli_query($conn, "
			SELECT id
			FROM users
			WHERE email = '$email'
			AND id != '$user_id'
			LIMIT 1
		");

		if (mysqli_num_rows($check_email) > 0) {

			$message = "<div class='error-message'>Email already exists.</div>";

		}
		else {

			$update_user = mysqli_query($conn, "
				UPDATE users
				SET
					name = '$name',
					email = '$email',
					phone = '$phone',
					updated_at = NOW()
				WHERE id = '$user_id'
			");

			if ($update_user) {

				$_SESSION["user_name"] = $name;
				$_SESSION["user_email"] = $email;

				$message = "<div class='success-message'>Profile updated successfully.</div>";

			}
			else {

				$message = "<div class='error-message'>Something went wrong.</div>";

			}
		}
	}
}


/* GET USER */

$get_user = mysqli_query($conn, "
	SELECT *
	FROM users
	WHERE id = '$user_id'
	LIMIT 1
");

$user = mysqli_fetch_assoc($get_user);

include "../includes/header.php";

?>
	<!-- PAGE BANNER -->

<section class="page-banner">
	<div class="container">
		<div class="page-banner-content">
			<h1>Update Profile</h1>
			<p><a href="../index.php">Home</a> / Update Profile</p>
		</div>
	</div>
</section>

	<!-- UPDATE PROFILE -->

<section class="profile section-padding">
	<div class="container">
		<div class="profile-wrapper">

			<div class="profile-header">
				<h2>Update Profile</h2>
				<p>Keep your personal information up to date.</p>
			</div>

			<?php echo $message; ?>

			<form method="POST">

				<div class="form-group">
					<label>Full Name</label>
					<input
						type="text"
						name="name"
						value="<?php echo htmlspecialchars($user["name"]); ?>"
						required
					>
				</div>

				<div class="form-group">
					<label>Email Address</label>
					<input
						type="email"
						name="email"
						value="<?php echo htmlspecialchars($user["email"]); ?>"
						required
					>
				</div>

				<div class="form-group">
					<label>Phone Number</label>
					<input
						type="text"
						name="phone"
						value="<?php echo htmlspecialchars($user["phone"]); ?>"
						required
					>
				</div>

				<div class="profile-buttons">
					<button
						type="submit"
						name="update_profile"
						class="btn-primary"
					>
						<i class="fa-solid fa-floppy-disk"></i> Save Changes
					</button>

					<a
						href="profile.php"
						class="btn-secondary"
					>
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