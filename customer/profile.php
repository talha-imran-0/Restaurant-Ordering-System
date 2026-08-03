<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";
require_once "../php/Auth.php";

require_customer();

include "../includes/header.php";


	/* GET USER */

$user_id = $_SESSION["user_id"];

$get_user = mysqli_query($conn, "
	SELECT *
	FROM users
	WHERE id = '$user_id'
	LIMIT 1
");

$user = mysqli_fetch_assoc($get_user);

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
				<h2>Welcome, <?php echo htmlspecialchars($user["name"]); ?></h2>
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
					<p><?php echo ucfirst($user["role"]); ?></p>
				</div>

				<div class="profile-item">
					<label>Member Since</label>
					<p><?php echo date("d M Y", strtotime($user["created_at"])); ?></p>
				</div>

			</div>

			<div class="profile-buttons">
				<a href="update-profile.php" class="btn-primary">Update Profile</a>
				<a href="change-password.php" class="btn-change-password btn-secondary">Change Password</a>
				<a href="order_tracking.php" class="btn-primary">My Orders</a>
			</div>

		</div>
	</div>
</section>

<?php
include "../includes/footer.php";
?>
