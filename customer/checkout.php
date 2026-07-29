<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";

include "../includes/header.php";


	/* CREATE CART SESSION */

if (!isset($_SESSION["cart"])) {
	$_SESSION["cart"] = [];
}


	/* EMPTY CART CHECK */

if (empty($_SESSION["cart"])) {
	header("Location: cart.php");
	exit();
}


	/* CALCULATE TOTALS */

$subtotal = 0;

foreach ($_SESSION["cart"] as $item) {
	$subtotal += $item["price"] * $item["quantity"];
}

$delivery = 2.00;
$grand_total = $subtotal + $delivery;

?>

	<!-- PAGE BANNER -->

<section class="page-banner">
	<div class="container">
		<div class="page-banner-content">
			<h1>Checkout</h1>
			<p><a href="../index.php">Home</a> / Checkout</p>
		</div>
	</div>
</section>

	<!-- CHECKOUT -->

<section class="checkout section-padding">
	<div class="container">
		<div class="checkout-wrapper">

	<!-- BILLING DETAILS -->

			<div class="checkout-form">
				<h2>Billing Details</h2>

				<form method="POST">
					<div class="form-group">
						<label>Full Name</label>
						<input type="text" name="name" placeholder="Enter your full name" required>
					</div>

					<div class="form-group">
						<label>Email Address</label>
						<input type="email" name="email" placeholder="Enter your email" required>
					</div>

					<div class="form-group">
						<label>Phone Number</label>
						<input type="text" name="phone" placeholder="03XXXXXXXXX" required>
					</div>

					<div class="form-group">
						<label>Delivery Address</label>
						<textarea name="address" rows="5" placeholder="Enter your complete address" required></textarea>
					</div>

					<div class="form-group">
						<label>City</label>
						<input type="text" name="city" placeholder="Enter your city" required>
					</div>

					<div class="form-group">
						<label>Order Notes (Optional)</label>
						<textarea name="notes" rows="4" placeholder="Any special instructions?"></textarea>
					</div>

	<!-- ORDER SUMMARY -->

					<div class="checkout-summary">
						<h2>Your Order</h2>

						<?php
						foreach ($_SESSION["cart"] as $item) {
							$item_total = $item["price"] * $item["quantity"];
						?>

						<div class="checkout-product">
							<div class="checkout-product-image">
								<img src="../assets/uploads/menu/<?php echo $item["image"]; ?>" alt="<?php echo htmlspecialchars($item["name"]); ?>">
							</div>

							<div class="checkout-product-info">
								<h4><?php echo htmlspecialchars($item["name"]); ?></h4>
								<p>Quantity : <?php echo $item["quantity"]; ?></p>
							</div>

							<div class="checkout-product-price">$<?php echo number_format($item_total, 2); ?></div>
						</div>

						<?php
						}
						?>

						<div class="checkout-total">
							<div class="checkout-total-item">
								<span>Subtotal</span>
								<span>$<?php echo number_format($subtotal, 2); ?></span>
							</div>

							<div class="checkout-total-item">
								<span>Delivery Charges</span>
								<span>$<?php echo number_format($delivery, 2); ?></span>
							</div>

							<div class="checkout-total-item grand-total">
								<span>Grand Total</span>
								<span>$<?php echo number_format($grand_total, 2); ?></span>
							</div>
						</div>

	<!-- PAYMENT METHOD -->

						<div class="payment-method">
							<h3>Payment Method</h3>

							<label>
								<input type="radio" name="payment_method" value="Cash On Delivery" checked> Cash On Delivery
							</label>

							<label>
								<input type="radio" name="payment_method" value="Credit Card"> Credit / Debit Card
							</label>
						</div>

						<div class="terms-box">
							<label>
								<input type="checkbox" required> I agree to the Terms & Conditions
							</label>
						</div>

						<button type="submit" name="place_order" class="btn-primary">Place Order</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

<?php
include "../includes/footer.php";
?>