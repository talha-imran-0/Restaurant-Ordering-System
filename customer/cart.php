<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";

include "../includes/header.php";

/* CREATE CART SESSION */
if (!isset($_SESSION["cart"])) {
	$_SESSION["cart"] = [];
}

/* CART ACTIONS */
if (
	isset($_POST["remove"]) ||
	isset($_POST["increase"]) ||
	isset($_POST["decrease"])
) {
	require_csrf_token();

	/* REMOVE PRODUCT */
	if (isset($_POST["remove"])) {
		$remove_id = (int) $_POST["remove"];

		if (isset($_SESSION["cart"][$remove_id])) {
			unset($_SESSION["cart"][$remove_id]);
		}
	}

	/* INCREASE QUANTITY */
	if (isset($_POST["increase"])) {
		$id = (int) $_POST["increase"];

		if (isset($_SESSION["cart"][$id])) {
			if ($_SESSION["cart"][$id]["quantity"] < 10) {
				$_SESSION["cart"][$id]["quantity"]++;
			}
		}
	}

	/* DECREASE QUANTITY */
	if (isset($_POST["decrease"])) {
		$id = (int) $_POST["decrease"];

		if (isset($_SESSION["cart"][$id])) {
			$_SESSION["cart"][$id]["quantity"]--;

			if ($_SESSION["cart"][$id]["quantity"] <= 0) {
				unset($_SESSION["cart"][$id]);
			}
		}
	}

	header("Location: cart.php");
	exit();
}

$subtotal = 0;
$delivery = 2.00;

?>

<!-- PAGE BANNER -->
<section class="page-banner">
	<div class="container">
		<div class="page-banner-content">
			<h1>Shopping Cart</h1>
			<p>
				<a href="../index.php">Home</a> / Shopping Cart
			</p>
		</div>
	</div>
</section>

<!-- CART -->
<section class="cart section-padding">
	<div class="container">
		<div class="cart-wrapper">
			<div class="cart-table">
				<table>
					<thead>
						<tr>
							<th>Product</th>
							<th>Price</th>
							<th>Quantity</th>
							<th>Total</th>
							<th>Remove</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (!empty($_SESSION["cart"])) {
							foreach ($_SESSION["cart"] as $item) {
								$total = $item["price"] * $item["quantity"];
								$subtotal += $total;
						?>
						<tr>
							<td>
								<div class="cart-product">
									<div class="cart-product-image">
										<img src="../assets/uploads/menu/<?php echo htmlspecialchars($item["image"]); ?>" alt="<?php echo htmlspecialchars($item["name"]); ?>">
									</div>
									<div class="cart-product-info">
										<h4><?php echo htmlspecialchars($item["name"]); ?></h4>
										<p>$<?php echo number_format($item["price"], 2); ?></p>
									</div>
								</div>
							</td>
							<td>$<?php echo number_format($item["price"], 2); ?></td>
							<td>
								<div class="quantity-box">
									<!-- DECREASE -->
									<form method="POST" style="display:inline;">
										<?php csrf_input(); ?>
										<button type="submit" name="decrease" value="<?php echo (int) $item["id"]; ?>" class="qty-btn" >−</button>
									</form>

									<input type="text" value="<?php echo (int) $item["quantity"]; ?>" readonly>

									<!-- INCREASE -->
									<form method="POST" style="display:inline;">
										<?php csrf_input(); ?>
										<button type="submit" name="increase" value="<?php echo (int) $item["id"]; ?>" class="qty-btn" >+</button>
									</form>
								</div>
							</td>
							<td>
								<strong>$<?php echo number_format($total, 2); ?></strong>
							</td>
							<td>
								<!-- REMOVE -->
								<form method="POST" style="display:inline;">
									<?php csrf_input(); ?>
									<button type="submit" name="remove" value="<?php echo (int) $item["id"]; ?>" class="remove-btn" >
										<i class="fa-solid fa-trash"></i>
									</button>
								</form>
							</td>
						</tr>
						<?php
							}
						} else {
						?>
						<tr>
							<td colspan="5">
								<div class="empty-cart">
									<i class="fa-solid fa-cart-shopping"></i>
									<h3>Your Cart Is Empty</h3>
									<p>Looks like you haven't added any delicious food yet.</p>
									<a href="../index.php#featured-menu" class="btn-primary" >Browse Menu</a>
								</div>
							</td>
						</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>

			<!-- CART SUMMARY -->
			<div class="cart-summary">
				<h3>Order Summary</h3>
				<?php $total = $subtotal + $delivery; ?>

				<div class="summary-item">
					<span>Subtotal</span>
					<span>$<?php echo number_format($subtotal, 2); ?></span>
				</div>

				<div class="summary-item">
					<span>Delivery Charges</span>
					<span>$<?php echo number_format($delivery, 2); ?></span>
				</div>

				<div class="summary-item total">
					<span>Grand Total</span>
					<span>$<?php echo number_format($total, 2); ?></span>
				</div>

				<?php if (!empty($_SESSION["cart"])) { ?>
					<a href="checkout.php" class="btn-primary" >Proceed To Checkout</a>
				<?php } else { ?>
					<a href="../index.php#featured-menu" class="btn-secondary" >Continue Shopping</a>
				<?php } ?>
			</div>
		</div>
	</div>
</section>

<?php
include "../includes/footer.php";
?>