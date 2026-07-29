<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";

include "../includes/header.php";


	/* LOGIN USER */

$message = "";

if (isset($_POST["login"])) {

	$email = trim($_POST["email"]);
	$password = trim($_POST["password"]);

	if (empty($email) || empty($password)) {
		$message = "<div class='error-message'>Please fill all fields.</div>";
	} else {
		$get_user = mysqli_query($conn, "
			SELECT *
			FROM users
			WHERE email = '$email'
			AND status = 1
			LIMIT 1
		");

		if (mysqli_num_rows($get_user) == 1) {
			$user = mysqli_fetch_assoc($get_user);

			if (password_verify($password, $user["password"])) {
				$_SESSION["user_id"] = $user["id"];
				$_SESSION["user_name"] = $user["name"];
				$_SESSION["user_email"] = $user["email"];
				$_SESSION["user_role"] = $user["role"];

				header("Location: ../index.php");
				exit();
			} else {
				$message = "<div class='error-message'>Incorrect password.</div>";
			}
		} else {
			$message = "<div class='error-message'>Email not found.</div>";
		}
	}
}

?>

	<!-- PAGE BANNER -->

<section class="page-banner">
	<div class="container">
		<div class="page-banner-content">
			<h1>Login</h1>
			<p><a href="../index.php">Home</a> / Login</p>
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
					<p>Don't have an account? <a href="register.php">Register Here</a></p>
				</div>
			</form>
		</div>
	</div>
</section>

<?php
include "../includes/footer.php";
?>