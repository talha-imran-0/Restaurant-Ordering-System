<?php

require_once "includes/config.php";
require_once "php/Functions.php";

include "includes/header.php";

$message = "";

/* SEND MESSAGE */

if (isset($_POST["send_message"])) {

	$name = trim($_POST["name"]);
	$email = trim($_POST["email"]);
	$subject = trim($_POST["subject"]);
	$user_message = trim($_POST["message"]);

	if (
		empty($name) ||
		empty($email) ||
		empty($subject) ||
		empty($user_message)
	) {

		$message = "<div class='error-message'>Please fill all required fields.</div>";

	}
	else {

		$insert_message = mysqli_query($conn, "
			INSERT INTO contact_messages
			(
				name,
				email,
				subject,
				message
			)
			VALUES
			(
				'$name',
				'$email',
				'$subject',
				'$user_message'
			)
		");

		if ($insert_message) {

			$message = "<div class='success-message'>Your message has been sent successfully.</div>";

		}
		else {

			$message = "<div class='error-message'>Something went wrong. Please try again.</div>";

		}

	}

}

?>

<!-- PAGE BANNER -->

<section class="page-banner">
	<div class="container">
		<div class="page-banner-content">
			<h1>Contact Us</h1>
			<p><a href="index.php">Home</a> / Contact</p>
		</div>
	</div>
</section>

<!-- CONTACT -->

<section class="contact section-padding">

	<div class="container">

		<div class="section-title">
			<span>Get In Touch</span>
			<h2>Contact Urban Bites</h2>
		</div>

		<div class="contact-wrapper">

			<!-- LEFT SIDE -->

			<div class="contact-info">

				<div class="contact-card">
					<i class="fa-solid fa-location-dot"></i>

					<h3>Address</h3>

					<p>
						Gill Road,<br>
						Gujranwala, Pakistan
					</p>
				</div>

				<div class="contact-card">
					<i class="fa-solid fa-phone"></i>

					<h3>Phone</h3>

					<p>
						+92 300 1234567
					</p>
				</div>

				<div class="contact-card">
					<i class="fa-solid fa-envelope"></i>

					<h3>Email</h3>

					<p>
						info@urbanbites.com
					</p>
				</div>

				<div class="contact-card">
					<i class="fa-solid fa-clock"></i>

					<h3>Opening Hours</h3>

					<p>
						Monday - Sunday<br>
						10:00 AM - 11:00 PM
					</p>
				</div>

			</div>

            			<!-- RIGHT SIDE -->

			<div class="contact-form">

				<h3>Send Us a Message</h3>
                <?php echo $message; ?>

				<form action="#" method="POST">

					<div class="form-group">
						<label>Full Name</label>
						<input type="text" name="name" placeholder="Enter your full name" required>
					</div>

					<div class="form-group">
						<label>Email Address</label>
						<input type="email" name="email" placeholder="Enter your email address" required>
					</div>

					<div class="form-group">
						<label>Subject</label>
						<input type="text" name="subject" placeholder="Enter subject" required>
					</div>

					<div class="form-group">
						<label>Message</label>
						<textarea
							name="message"
							rows="6"
							placeholder="Write your message..."
							required></textarea>
					</div>

					<button type="submit" name="send_message" class="btn-primary">
                        Send Message
                    </button>

				</form>

			</div>

		</div>

	</div>

</section>


<!-- GOOGLE MAP -->

<?php
include "includes/footer.php";
?>