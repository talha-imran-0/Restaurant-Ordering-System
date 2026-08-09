<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";
require_once "../php/Auth.php";

require_customer();

include "../includes/header.php";


/* CREATE CART SESSION */

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}


/* CHECK ORDER ID */

if (!isset($_GET["id"]) || empty($_GET["id"])) {
    header("Location: order_tracking.php");
    exit();
}

$order_id = (int) $_GET["id"];
$user_id = $_SESSION["user_id"];


/* GET ORDER */

$get_order = mysqli_query($conn, "
    SELECT *
    FROM orders
    WHERE id = '$order_id'
    AND user_id = '$user_id'
    LIMIT 1
");

if (mysqli_num_rows($get_order) == 0) {
    header("Location: order_tracking.php");
    exit();
}

$order = mysqli_fetch_assoc($get_order);


/* GET ORDER ITEMS */

$get_items = mysqli_query($conn, "
    SELECT
        order_items.*,
        menu_items.name,
        menu_items.image
    FROM order_items
    INNER JOIN menu_items
    ON order_items.menu_item_id = menu_items.id
    WHERE order_items.order_id = '$order_id'
");

?>

<!-- PAGE BANNER -->

<section class="page-banner">
    <div class="container">
        <div class="page-banner-content">
            <h1>Order Details</h1>
            <p>
                <a href="../index.php">Home</a> /
                <a href="order_tracking.php">My Orders</a> /
                Order Details
            </p>
        </div>
    </div>
</section>

<!-- ORDER DETAILS -->

<section class="order-details section-padding">
    <div class="container">
        <div class="order-details-wrapper">

            <div class="order-header">

                <h2>
                    Order #<?php echo htmlspecialchars($order["order_number"]); ?>
                </h2>

                <p>
                    Placed on
                    <?php echo date("d M Y - h:i A", strtotime($order["created_at"])); ?>
                </p>

            </div>

            <!-- ORDER INFORMATION -->

            <div class="order-info-grid">

                <div class="order-info-card">

                    <h3>Order Information</h3>

                    <div class="info-item">
                        <strong>Order Number</strong>
                        <span><?php echo htmlspecialchars($order["order_number"]); ?></span>
                    </div>

                    <div class="info-item">
                        <strong>Payment Method</strong>
                        <span>

                        <?php

                        if ($order["payment_method"] == "cash") {
                            echo "Cash On Delivery";
                        }
                        elseif ($order["payment_method"] == "card") {
                            echo "Credit / Debit Card";
                        }
                        else {
                            echo "-";
                        }

                        ?>

                        </span>
                    </div>

                    <div class="info-item">

                        <strong>Payment Status</strong>

                        <?php

                        $payment_status = strtolower($order["payment_status"]);

                        if ($payment_status == "paid") {

                            echo '<span class="status paid">Paid</span>';

                        } elseif ($payment_status == "pending") {

                            echo '<span class="status unpaid">Pending</span>';

                        } elseif ($payment_status == "failed") {

                            echo '<span class="status failed">Failed</span>';

                        } else {

                            echo '<span class="status">' . htmlspecialchars($payment_status) . '</span>';

                        }

                        ?>

                    </div>

                    <div class="info-item">

                        <strong>Order Status</strong>

                        <?php

                        $order_status = strtolower($order["order_status"]);

                        if ($order_status == "new") {

                            echo '<span class="status pending">New</span>';

                        } elseif ($order_status == "preparing") {

                            echo '<span class="status preparing">Preparing</span>';

                        } elseif ($order_status == "on_the_way") {

                            echo '<span class="status on-the-way">On The Way</span>';

                        } elseif ($order_status == "delivered") {

                            echo '<span class="status delivered">Delivered</span>';

                        } elseif ($order_status == "cancelled") {

                            echo '<span class="status cancelled">Cancelled</span>';

                        } else {

                            echo '<span class="status">' . htmlspecialchars($order_status) . '</span>';

                        }

                        ?>

                    </div>

                </div>


                <!-- DELIVERY INFORMATION -->

                <div class="order-info-card">

                    <h3>Delivery Information</h3>

                    <div class="info-item">
                        <strong>Address</strong>
                        <span><?php echo htmlspecialchars($order["delivery_address"]); ?></span>
                    </div>

                    <div class="info-item">
                        <strong>City</strong>
                        <span><?php echo htmlspecialchars($order["city"]); ?></span>
                    </div>

                    <div class="info-item">
                        <strong>Notes</strong>
                        <span>

                        <?php

                        if (!empty($order["order_notes"])) {
                            echo htmlspecialchars($order["order_notes"]);
                        }
                        else {
                            echo "No Notes";
                        }

                        ?>

                        </span>
                    </div>

                </div>

            </div>


            <!-- ORDER ITEMS -->

            <div class="order-items">

                <h3>Ordered Products</h3>

                <table class="order-items-table">

                    <thead>

                        <tr>

                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php while($item = mysqli_fetch_assoc($get_items)) { ?>

                        <tr>

                            <td>

                                <div class="order-product">

                                    <div class="order-product-image">

                                        <img src="../assets/uploads/menu/<?php echo $item["image"]; ?>" alt="<?php echo htmlspecialchars($item["name"]); ?>">

                                    </div>

                                    <div class="order-product-info">

                                        <h4><?php echo htmlspecialchars($item["name"]); ?></h4>

                                    </div>

                                </div>

                            </td>

                            <td>
                                <?php echo number_format($item["price"],2); ?>$
                            </td>

                            <td>
                                <?php echo $item["quantity"]; ?>
                            </td>

                            <td>
                                <?php echo number_format($item["subtotal"],2); ?>$
                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

            <!-- ORDER SUMMARY -->

            <div class="order-summary">

                <h3>Order Summary</h3>

                <div class="summary-item">
                    <span>Subtotal</span>
                    <span><?php echo number_format($order["subtotal"],2); ?>$</span>
                </div>

                <div class="summary-item">
                    <span>Discount</span>
                    <span><?php echo number_format($order["discount"],2); ?>$</span>
                </div>

                <div class="summary-item">
                    <span>Delivery Charges</span>
                    <span><?php echo number_format($order["delivery_charges"],2); ?>$</span>
                </div>

                <div class="summary-item total">
                    <span>Grand Total</span>
                    <span><?php echo number_format($order["total"],2); ?> $</span>
                </div>

            </div>


            <!-- BUTTONS -->

            <div class="order-details-buttons">

                <a href="order_tracking.php" class="btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i>&nbsp;
                    Back To Orders
                </a>

                <a href="../index.php" class="btn-primary">
                    <i class="fa-solid fa-utensils"></i>&nbsp;
                    Continue Shopping
                </a>

            </div>

        </div>
    </div>
</section>

<?php
include "../includes/footer.php";
?>