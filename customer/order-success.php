<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";
require_once "../php/Auth.php";

require_customer();

include "../includes/header.php";


	/* CHECK ORDER */

if (!isset($_GET["order"]) || empty($_GET["order"])) {
	header("Location: ../index.php");
	exit();
}

$order_number = trim($_GET["order"]);

?>

	<!-- PAGE BANNER -->

<section class="page-banner">
	<div class="container">
		<div class="page-banner-content">
			<h1>Order Success</h1>
			<p><a href="../index.php">Home</a> / Order Success</p>
		</div>
	</div>
</section>

	<!-- ORDER SUCCESS -->

<section class="order-success section-padding">
	<div class="container">
		<div class="order-success-wrapper">

			<div class="success-icon">
				<i class="fa-solid fa-circle-check"></i>
			</div>

			<h2>Thank You For Your Order!</h2>

			<p>
				Your order has been placed successfully.
			</p>

			<div class="order-number">
				<strong>Order Number :</strong>
				<span><?php echo htmlspecialchars($order_number); ?></span>
			</div>

			<p>
				We have received your order and our team will start preparing it shortly.
			</p>

			<div class="success-buttons">
				<a href="order_tracking.php" class="btn-primary">Track Order</a>
				<a href="../index.php" class="btn-secondary">Continue Shopping</a>
			</div>

		</div>
	</div>
</section>

<?php
include "../includes/footer.php";
?>