<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";
require_once "../php/Auth.php";

require_customer();

$message = "";

/* CREATE CART SESSION */
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}

/* EMPTY CART CHECK */
if (empty($_SESSION["cart"])) {
    header("Location: cart.php");
    exit();
}

/* PLACE ORDER */
if (isset($_POST["place_order"])) {
    require_csrf_token();
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $city = trim($_POST["city"] ?? "");
    $notes = trim($_POST["notes"] ?? "");
    $payment_method = trim($_POST["payment_method"] ?? "");

    /* SERVER-SIDE PAYMENT METHOD VALIDATION */
    $allowed_payment_methods = ["cash", "card"];

    if (
        empty($name) ||
        empty($email) ||
        empty($phone) ||
        empty($address) ||
        empty($city)
    ) {
        $message = "<div class='error-message'>Please fill all required fields.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='error-message'>Please enter a valid email address.</div>";
    } elseif (!preg_match("/^[a-zA-Z .'-]+$/", $name)) {
        $message = "<div class='error-message'>Please enter a valid name.</div>";
    } elseif (!preg_match("/^[0-9+\-\s()]{7,20}$/", $phone)) {
        $message = "<div class='error-message'>Please enter a valid phone number.</div>";
    } elseif (!in_array($payment_method, $allowed_payment_methods, true)) {
        $message = "<div class='error-message'>Invalid payment method.</div>";
    } else {
        /* VERIFY CART AGAIN BEFORE CREATING ORDER */
        if (empty($_SESSION["cart"])) {
            $message = "<div class='error-message'>Your cart is empty.</div>";
        } else {
            $user_id = $_SESSION["user_id"];
            $subtotal = 0;

            foreach ($_SESSION["cart"] as $item) {
                $subtotal += $item["price"] * $item["quantity"];
            }

            $delivery = 2.00;
            $discount = 0;
            $total = $subtotal + $delivery;

            $order_number = "UB" . date("YmdHis") . rand(100, 999);

            /* START DATABASE TRANSACTION */
            mysqli_begin_transaction($conn);

            try {
                /* INSERT ORDER */
                $order_stmt = mysqli_prepare(
                    $conn,
                    "INSERT INTO orders (
                        user_id, customer_name, customer_email, customer_phone,
                        delivery_address, city, order_notes, coupon_id, order_number,
                        subtotal, discount, delivery_charges, total, payment_method,
                        payment_status, order_status, created_at, updated_at
                    ) VALUES (
                        ?, ?, ?, ?, ?, ?, ?, NULL, ?, ?, ?, ?, ?, ?, 'pending', 'new', NOW(), NOW()
                    )"
                );

                if (!$order_stmt) {
                    throw new Exception("Order statement preparation failed.");
                }

                mysqli_stmt_bind_param(
                    $order_stmt,
                    "isssssssdddds",
                    $user_id,
                    $name,
                    $email,
                    $phone,
                    $address,
                    $city,
                    $notes,
                    $order_number,
                    $subtotal,
                    $discount,
                    $delivery,
                    $total,
                    $payment_method
                );

                if (!mysqli_stmt_execute($order_stmt)) {
                    throw new Exception("Order insertion failed.");
                }

                $order_id = mysqli_insert_id($conn);
                mysqli_stmt_close($order_stmt);

                /* INSERT ORDER ITEMS */
                $item_stmt = mysqli_prepare(
                    $conn,
                    "INSERT INTO order_items (
                        order_id, menu_item_id, quantity, price, subtotal
                    ) VALUES (?, ?, ?, ?, ?)"
                );

                if (!$item_stmt) {
                    throw new Exception("Order item statement preparation failed.");
                }

                foreach ($_SESSION["cart"] as $item) {
                    $menu_item_id = (int) $item["id"];
                    $quantity = (int) $item["quantity"];
                    $price = (float) $item["price"];
                    $item_total = $price * $quantity;

                    mysqli_stmt_bind_param(
                        $item_stmt,
                        "iiidd",
                        $order_id,
                        $menu_item_id,
                        $quantity,
                        $price,
                        $item_total
                    );

                    if (!mysqli_stmt_execute($item_stmt)) {
                        throw new Exception("Order item insertion failed.");
                    }
                }

                mysqli_stmt_close($item_stmt);

                /* INSERT PAYMENT */
                $payment_stmt = mysqli_prepare(
                    $conn,
                    "INSERT INTO payments (
                        order_id, payment_method, amount, payment_status,
                        transaction_id, paid_at, created_at
                    ) VALUES (?, ?, ?, 'Pending', '', NULL, NOW())"
                );

                if (!$payment_stmt) {
                    throw new Exception("Payment statement preparation failed.");
                }

                mysqli_stmt_bind_param(
                    $payment_stmt,
                    "isd",
                    $order_id,
                    $payment_method,
                    $total
                );

                if (!mysqli_stmt_execute($payment_stmt)) {
                    throw new Exception("Payment insertion failed.");
                }

                mysqli_stmt_close($payment_stmt);

                /* COMMIT TRANSACTION */
                mysqli_commit($conn);
                unset($_SESSION["cart"]);

                header("Location: order-success.php?order=" . urlencode($order_number));
                exit();
            } catch (Exception $e) {
                /* ROLLBACK IF ANY QUERY FAILS */
                mysqli_rollback($conn);
                $message = "<div class='error-message'>Something went wrong. Please try again.</div>";
            }
        }
    }
}

/* CALCULATE TOTALS */
$subtotal = 0;
foreach ($_SESSION["cart"] as $item) {
    $subtotal += $item["price"] * $item["quantity"];
}

$delivery = 2.00;
$grand_total = $subtotal + $delivery;

include "../includes/header.php";

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
                    <?php csrf_input(); ?>

                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" placeholder="Enter your full name" value="<?php echo isset($_POST["name"]) ? htmlspecialchars($_POST["name"]) : ""; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" placeholder="Enter your email" value="<?php echo isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : ""; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" placeholder="03XXXXXXXXX" value="<?php echo isset($_POST["phone"]) ? htmlspecialchars($_POST["phone"]) : ""; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Delivery Address</label>
                        <textarea name="address" rows="5" placeholder="Enter your complete address" required><?php echo isset($_POST["address"]) ? htmlspecialchars($_POST["address"]) : ""; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" placeholder="Enter your city" value="<?php echo isset($_POST["city"]) ? htmlspecialchars($_POST["city"]) : ""; ?>" required>
                    </div>

                    <div class="form-group">
                        <label>Order Notes (Optional)</label>
                        <textarea name="notes" rows="4" placeholder="Any special instructions?"><?php echo isset($_POST["notes"]) ? htmlspecialchars($_POST["notes"]) : ""; ?></textarea>
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
                                    <img src="../assets/uploads/menu/<?php echo htmlspecialchars($item["image"]); ?>" alt="<?php echo htmlspecialchars($item["name"]); ?>">
                                </div>

                                <div class="checkout-product-info">
                                    <h4><?php echo htmlspecialchars($item["name"]); ?></h4>
                                    <p>Quantity : <?php echo (int) $item["quantity"]; ?></p>
                                </div>

                                <div class="checkout-product-price">
                                    <?php echo number_format($item_total, 2); ?> $
                                </div>
                            </div>
                        <?php
                        }
                        ?>

                        <div class="checkout-total">
                            <div class="checkout-total-item">
                                <span>Subtotal</span>
                                <span><?php echo number_format($subtotal, 2); ?> $</span>
                            </div>

                            <div class="checkout-total-item">
                                <span>Delivery Charges</span>
                                <span><?php echo number_format($delivery, 2); ?> $</span>
                            </div>

                            <div class="checkout-total-item grand-total">
                                <span>Grand Total</span>
                                <span><?php echo number_format($grand_total, 2); ?> $</span>
                            </div>
                        </div>

                        <!-- PAYMENT METHOD -->
                        <div class="payment-method">
                            <h3>Payment Method</h3>

                            <label>
                                <input type="radio" name="payment_method" value="cash" <?php echo (!isset($_POST["payment_method"]) || $_POST["payment_method"] === "cash") ? "checked" : ""; ?>>
                                Cash On Delivery
                            </label>

                            <label>
                                <input type="radio" name="payment_method" value="card" <?php echo (isset($_POST["payment_method"]) && $_POST["payment_method"] === "card") ? "checked" : ""; ?>>
                                Credit / Debit Card
                            </label>
                        </div>

                        <div class="terms-box">
                            <label>
                                <input type="checkbox" required>
                                I agree to the Terms &amp; Conditions
                            </label>
                        </div>

                        <button type="submit" name="place_order" class="btn-primary">
                            Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php
include "../includes/footer.php";
?>