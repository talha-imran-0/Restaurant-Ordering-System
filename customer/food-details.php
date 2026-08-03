<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";

include "../includes/header.php";


	/* CREATE CART SESSION */

if (!isset($_SESSION["cart"])) {
	$_SESSION["cart"] = [];
}


	/* CHECK PRODUCT ID */

if (!isset($_GET["id"]) || empty($_GET["id"])) {
	header("Location: ../index.php");
	exit();
}

$product_id = (int) $_GET["id"];


	/* GET PRODUCT */

$get_product = mysqli_query($conn, "
	SELECT menu_items.*, categories.name AS category_name
	FROM menu_items
	INNER JOIN categories ON menu_items.category_id = categories.id
	WHERE menu_items.id = '$product_id' AND menu_items.status = 1
	LIMIT 1
");

if (mysqli_num_rows($get_product) == 0) {
	header("Location: ../index.php");
	exit();
}

$product = mysqli_fetch_assoc($get_product);


	/* ADD TO CART */

if (isset($_POST["add_to_cart"])) {
	$quantity = (int) $_POST["quantity"];

	if ($quantity < 1) {
		$quantity = 1;
	}

	if (isset($_SESSION["cart"][$product_id])) {
		$_SESSION["cart"][$product_id]["quantity"] += $quantity;
	}
	else {
		$_SESSION["cart"][$product_id] = [
			"id"       => $product["id"],
			"name"     => $product["name"],
			"price"    => $product["price"],
			"image"    => $product["image"],
			"quantity" => $quantity
		];
	}

	header("Location: cart.php");
	exit();
}


	/* BUY NOW */

if (isset($_POST["buy_now"])) {

    $quantity = (int) $_POST["quantity"];

    if ($quantity < 1) {
        $quantity = 1;
    }

    // Sirf isi product ko cart me rakho
    $_SESSION["cart"] = [];

    $_SESSION["cart"][$product_id] = [
        "id"       => $product["id"],
        "name"     => $product["name"],
        "price"    => $product["price"],
        "image"    => $product["image"],
        "quantity" => $quantity
    ];

    header("Location: checkout.php");
    exit();
}

	/* RELATED PRODUCTS */

$get_related = mysqli_query($conn, "
	SELECT *
	FROM menu_items
	WHERE category_id = '" . $product["category_id"] . "'
	AND id != '$product_id'
	AND status = 1
	LIMIT 4
");

?>

	<!-- PAGE BANNER -->

<section class="page-banner">
	<div class="container">
		<div class="page-banner-content">
			<h1><?php echo htmlspecialchars($product["name"]); ?></h1>
			<p><a href="../index.php">Home</a> / <?php echo htmlspecialchars($product["name"]); ?></p>
		</div>
	</div>
</section>

	<!-- FOOD DETAILS -->

<section class="food-details section-padding">
	<div class="container">
		<div class="food-details-wrapper">
			<div class="food-image">
				<img src="../assets/uploads/menu/<?php echo $product["image"]; ?>" alt="<?php echo htmlspecialchars($product["name"]); ?>">
			</div>

			<div class="food-content">
				<span class="food-category"><?php echo htmlspecialchars($product["category_name"]); ?></span>
				<h2><?php echo htmlspecialchars($product["name"]); ?></h2>

				<div class="food-rating">
					★★★★★
					<span>(120 Reviews)</span>
				</div>

				<h3 class="food-price">Rs. <?php echo number_format($product["price"], 2); ?></h3>
				<p><?php echo htmlspecialchars($product["description"]); ?></p>

				<form method="POST">
					<div class="quantity-area">
						<span>Quantity</span>

						<div class="quantity-box">
							<button type="button" id="minus-btn">-</button>
							<input type="text" id="quantity" value="1" readonly>
							<input type="hidden" name="quantity" id="cart_quantity" value="1">
							<button type="button" id="plus-btn">+</button>
						</div>
					</div>

					<div class="food-buttons">
						<button type="submit" name="add_to_cart" class="btn-primary">
							<i class="fa-solid fa-cart-plus"></i>&nbsp; Add To Cart
						</button>

						<button type="submit" name="buy_now" class="btn-secondary">
							<i class="fa-solid fa-bag-shopping"></i>&nbsp; Buy Now
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>

	<!-- PRODUCT DESCRIPTION -->

<section class="product-description section-padding">
	<div class="container">
		<div class="section-title">
			<span>About This Food</span>
			<h2>Description</h2>
		</div>

		<div class="description-content">
			<p><?php echo nl2br(htmlspecialchars($product["description"])); ?></p>
			<p>Every order is freshly prepared using premium quality ingredients to deliver the best taste and freshness.</p>
		</div>
	</div>
</section>

	<!-- ADDITIONAL INFORMATION -->

<section class="additional-info section-padding">
	<div class="container">
		<div class="section-title">
			<span>Product Details</span>
			<h2>Additional Information</h2>
		</div>

		<div class="info-table">
			<div class="info-item">
				<strong>Category</strong>
				<span><?php echo htmlspecialchars($product["category_name"]); ?></span>
			</div>

			<div class="info-item">
				<strong>Price</strong>
				<span>Rs. <?php echo number_format($product["price"], 2); ?></span>
			</div>

			<div class="info-item">
				<strong>Delivery Time</strong>
				<span>30 - 40 Minutes</span>
			</div>

			<div class="info-item">
				<strong>Availability</strong>
				<span>In Stock</span>
			</div>
		</div>
	</div>
</section>

	<!-- RELATED PRODUCTS -->

<section class="related-products section-padding">
	<div class="container">
		<div class="section-title">
			<span>You May Also Like</span>
			<h2>Related Products</h2>
		</div>

		<div class="menu-grid">
			<?php
			if (mysqli_num_rows($get_related) > 0) {
				while ($related = mysqli_fetch_assoc($get_related)) {
			?>

			<div class="menu-card">
				<img src="../assets/uploads/menu/<?php echo $related["image"]; ?>" alt="<?php echo htmlspecialchars($related["name"]); ?>">

				<div class="menu-content">
					<h3><?php echo htmlspecialchars($related["name"]); ?></h3>
					<p><?php echo htmlspecialchars($related["description"]); ?></p>

					<div class="menu-info">
						<span class="price">Rs. <?php echo number_format($related["price"], 2); ?></span>
						<span class="rating">★★★★★</span>
					</div>

					<a href="food-details.php?id=<?php echo $related["id"]; ?>" class="menu-btn">View Details</a>
				</div>
			</div>

			<?php
				}
			}
			else {
			?>

			<div class="no-products">
				<h3>No Related Products Found</h3>
				<p>No related food items are available.</p>
			</div>

			<?php
			}
			?>
		</div>
	</div>
</section>

<script>
	const quantityInput = document.getElementById("quantity");
	const hiddenQuantity = document.getElementById("cart_quantity");

	document.getElementById("plus-btn").addEventListener("click", function () {
		let qty = parseInt(quantityInput.value);
		qty++;
		quantityInput.value = qty;
		hiddenQuantity.value = qty;
	});

	document.getElementById("minus-btn").addEventListener("click", function () {
		let qty = parseInt(quantityInput.value);
		if (qty > 1) {
			qty--;
			quantityInput.value = qty;
			hiddenQuantity.value = qty;
		}
	});
</script>

<?php
include "../includes/footer.php";
?>