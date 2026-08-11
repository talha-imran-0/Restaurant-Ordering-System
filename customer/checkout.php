<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";
require_once "../php/Auth.php";

require_customer();

$message = "";

include "../includes/header.php";


	/* PLACE ORDER */

if (isset($_POST["place_order"])) {

	$name = trim($_POST["name"]);
	$email = trim($_POST["email"]);
	$phone = trim($_POST["phone"]);
	$address = trim($_POST["address"]);
	$city = trim($_POST["city"]);
	$notes = trim($_POST["notes"]);
	$payment_method = trim($_POST["payment_method"]);

	if (
		empty($name) ||
		empty($email) ||
		empty($phone) ||
		empty($address) ||
		empty($city)
	) {
		$message = "<div class='error-message'>Please fill all required fields.</div>";
	}
	else {

		$user_id = $_SESSION["user_id"];

		$subtotal = 0;

		foreach ($_SESSION["cart"] as $item) {
			$subtotal += $item["price"] * $item["quantity"];
		}

		$delivery = 2.00;
		$discount = 0;
		$total = $subtotal + $delivery;

		$order_number = "UB" . date("YmdHis") . rand(100,999);

		$insert_order = mysqli_query($conn, "
			INSERT INTO orders
			(
				user_id,
				delivery_address,
				city,
				order_notes,
				coupon_id,
				order_number,
				subtotal,
				discount,
				delivery_charges,
				total,
				payment_method,
				payment_status,
				order_status,
				created_at,
				updated_at
			)
			VALUES
			(
				'$user_id',
				'$address',
				'$city',
				'$notes',
				NULL,
				'$order_number',
				'$subtotal',
				'$discount',
				'$delivery',
				'$total',
				'$payment_method',
				'pending',
				'new',
				NOW(),
				NOW()
			)
		");

		if ($insert_order) {

			$order_id = mysqli_insert_id($conn);
						foreach ($_SESSION["cart"] as $item) {

				$menu_item_id = $item["id"];
				$quantity = $item["quantity"];
				$price = $item["price"];
				$item_total = $price * $quantity;

				mysqli_query($conn, "
					INSERT INTO order_items
					(
						order_id,
						menu_item_id,
						quantity,
						price,
						subtotal
					)
					VALUES
					(
						'$order_id',
						'$menu_item_id',
						'$quantity',
						'$price',
						'$item_total'
					)
				");
			}


			mysqli_query($conn, "
				INSERT INTO payments
				(
					order_id,
					payment_method,
					amount,
					payment_status,
					transaction_id,
					paid_at,
					created_at
				)
				VALUES
				(
					'$order_id',
					'$payment_method',
					'$total',
					'Pending',
					'',
					NULL,
					NOW()
				)
			");


			unset($_SESSION["cart"]);

			header("Location: order-success.php?order=" . $order_number);
			exit();

		}
		else {

			$message = "<div class='error-message'>Something went wrong. Please try again.</div>";

		}

	}

}

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
				<?php echo $message; ?>
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

							<div class="checkout-product-price"><?php echo number_format($item_total,2); ?> $</div>
						</div>

						<?php
						}
						?>

						<div class="checkout-total">
							<div class="checkout-total-item">
								<span>Subtotal</span>
								<span><?php echo number_format($subtotal,2); ?> $</span>
							</div>

							<div class="checkout-total-item">
								<span>Delivery Charges</span>
								<span><?php echo number_format($delivery,2); ?> $</span>
							</div>

							<div class="checkout-total-item grand-total">
								<span>Grand Total</span>
								<span><?php echo number_format($grand_total,2); ?> $</span>
							</div>
						</div>

	<!-- PAYMENT METHOD -->

						<div class="payment-method">
							<h3>Payment Method</h3>

							<label>
								<input type="radio" name="payment_method" value="cash" checked>
								Cash On Delivery
							</label>

							<label>
								<input type="radio" name="payment_method" value="card">
								Credit / Debit Card
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