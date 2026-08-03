<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";
require_once "../php/Auth.php";

require_customer();

include "../includes/header.php";


	/* GET ORDERS */

$user_id = $_SESSION["user_id"];

$get_orders = mysqli_query($conn, "
	SELECT *
	FROM orders
	WHERE user_id = '$user_id'
	ORDER BY created_at DESC
");

?>

	<!-- PAGE BANNER -->

<section class="page-banner">
	<div class="container">
		<div class="page-banner-content">
			<h1>My Orders</h1>
			<p><a href="../index.php">Home</a> / My Orders</p>
		</div>
	</div>
</section>

	<!-- ORDER TRACKING -->

<section class="order-tracking section-padding">
	<div class="container">
		<div class="order-tracking-wrapper">

			<?php if (mysqli_num_rows($get_orders) > 0) { ?>

				<div class="table-responsive">

					<table class="order-table">

						<thead>
							<tr>
								<th>Order No</th>
								<th>Date</th>
								<th>Total</th>
								<th>Payment</th>
								<th>Payment Status</th>
								<th>Order Status</th>
								<th>Action</th>
							</tr>
						</thead>

						<tbody>

							<?php while ($order = mysqli_fetch_assoc($get_orders)) { ?>
								<tr>

									<td>
										<?php echo htmlspecialchars($order["order_number"]); ?>
									</td>

									<td>
										<?php echo date("d M Y", strtotime($order["created_at"])); ?>
									</td>

									<td>
									    <?php echo number_format($order["total"]); ?> $
									</td>

									<td>
										<?php
										if ($order["payment_method"] == "cash") {
											echo "Cash On Delivery";
										} elseif ($order["payment_method"] == "card") {
											echo "Credit / Debit Card";
										}
										?>
									</td>

									<td>

										<?php

										$status = strtolower($order["payment_status"]);

                                        if ($status == "paid") {
                                            echo '<span class="status paid">Paid</span>';
                                        } elseif ($status == "pending") {
                                            echo '<span class="status unpaid">Pending</span>';
                                        }

										?>

									</td>

									<td>

										<?php

										$status = strtolower($order["order_status"]);

                                        if ($status == "new") {
                                            echo '<span class="status pending">New</span>';
                                        } elseif ($status == "preparing") {
                                            echo '<span class="status preparing">Preparing</span>';
                                        } elseif ($status == "ready") {
                                            echo '<span class="status ready">Ready</span>';
                                        } elseif ($status == "delivered") {
                                            echo '<span class="status delivered">Delivered</span>';
                                        } elseif ($status == "cancelled") {
                                            echo '<span class="status cancelled">Cancelled</span>';
                                        }

										?>

									</td>
									<td>
										<a href="order-details.php?id=<?php echo $order["id"]; ?>" class="view-btn">
											View Details
										</a>
									</td>

								</tr>

							<?php } ?>

						</tbody>

					</table>

				</div>

			<?php } else { ?>

				<div class="empty-orders">

					<h2>No Orders Found</h2>

					<p>
						You haven't placed any order yet.
					</p>

					<a href="menu.php" class="btn-primary">
						Start Ordering
					</a>

				</div>

			<?php } ?>

		</div>
	</div>
</section>

<?php
include "../includes/footer.php";
?>