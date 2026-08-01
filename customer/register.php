<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";

include "../includes/header.php";


	/* REGISTER USER */

$message = "";

if (isset($_POST["register"])) {

	$name = trim($_POST["name"]);
	$email = trim($_POST["email"]);
	$phone = trim($_POST["phone"]);
	$password = trim($_POST["password"]);
	$confirm_password = trim($_POST["confirm_password"]);

	if (
		empty($name) ||
		empty($email) ||
		empty($phone) ||
		empty($password) ||
		empty($confirm_password)
	) {
		$message = "<div class='error-message'>All fields are required.</div>";
	}
	elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$message = "<div class='error-message'>Please enter a valid email address.</div>";
	}
	elseif (strlen($password) < 8) {
	$message = "<div class='error-message'>Password must be at least 8 characters long.</div>";
	}
	elseif ($password != $confirm_password) {
		$message = "<div class='error-message'>Passwords do not match.</div>";
	}
	else {
		$check_email = mysqli_query($conn, "
			SELECT id
			FROM users
			WHERE email = '$email'
			LIMIT 1
		");

		if (mysqli_num_rows($check_email) > 0) {
			$message = "<div class='error-message'>Email already exists.</div>";
		}
		else {
			$password = password_hash($password, PASSWORD_DEFAULT);

			$insert_user = mysqli_query($conn, "
				INSERT INTO users
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
					'$name',
					'$email',
					'$phone',
					'$password',
					'customer',
					1,
					NOW(),
					NOW()
				)
			");

			if ($insert_user) {
				$message = "<div class='success-message'>Registration completed successfully. You can now login.</div>";
			}
			else {
				$message = "<div class='error-message'>Something went wrong. Please try again.</div>";
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
			<p><a href="../index.php">Home</a> / Register</p>
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

				<button type="submit" name="register" class="btn-primary">Create Account</button>

				<div class="register-login">
					<p>Already have an account? <a href="login.php">Login Here</a></p>
				</div>
			</form>
		</div>
	</div>
</section>

<?php
include "../includes/footer.php";
?>