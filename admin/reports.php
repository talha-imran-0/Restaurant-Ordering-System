<?php

session_start();

require_once "../includes/config.php";
require_once "../php/Auth.php";

/* CHECK ADMIN LOGIN */
require_admin();

/* TOTAL ORDERS */
$get_orders = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders");
$total_orders = mysqli_fetch_assoc($get_orders)['total'];

/* DELIVERED ORDERS */
$get_delivered = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE order_status='Delivered'");
$total_delivered = mysqli_fetch_assoc($get_delivered)['total'];

/* PENDING ORDERS */
$get_pending = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE order_status='New'");
$total_pending = mysqli_fetch_assoc($get_pending)['total'];

/* CANCELLED ORDERS */
$get_cancelled = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders WHERE order_status='Cancelled'");
$total_cancelled = mysqli_fetch_assoc($get_cancelled)['total'];

/* TOTAL REVENUE */
$get_revenue = mysqli_query($conn, "SELECT SUM(total) AS revenue FROM orders WHERE payment_status='Paid'");
$total_revenue = mysqli_fetch_assoc($get_revenue)['revenue'];

if ($total_revenue == "") {
    $total_revenue = 0;
}

/* TOTAL CUSTOMERS */
$get_customers = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='customer'");
$total_customers = mysqli_fetch_assoc($get_customers)['total'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="main">
    <h1>Reports</h1>

    <div class="cards">
        <div class="card">
            <h3>Total Orders</h3>
            <h2><?php echo $total_orders; ?></h2>
        </div>

        <div class="card">
            <h3>Delivered Orders</h3>
            <h2><?php echo $total_delivered; ?></h2>
        </div>

        <div class="card">
            <h3>Pending Orders</h3>
            <h2><?php echo $total_pending; ?></h2>
        </div>

        <div class="card">
            <h3>Cancelled Orders</h3>
            <h2><?php echo $total_cancelled; ?></h2>
        </div>

        <div class="card">
            <h3>Total Customers</h3>
            <h2><?php echo $total_customers; ?></h2>
        </div>

        <div class="card">
            <h3>Total Revenue</h3>
            <h2>$ <?php echo number_format($total_revenue, 2); ?></h2>
        </div>
    </div>

    <br><br>
    <h2>Recent Orders</h2>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Order No</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Order Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $get_recent_orders = mysqli_query($conn, "SELECT orders.*, users.name FROM orders LEFT JOIN users ON orders.user_id = users.id ORDER BY orders.id DESC LIMIT 10");

            if (mysqli_num_rows($get_recent_orders) > 0) {
                while ($order = mysqli_fetch_assoc($get_recent_orders)) {
            ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                    <td><?php echo htmlspecialchars($order['name']); ?></td>
                    <td>$ <?php echo number_format($order['total'], 2); ?></td>
                    <td><?php echo htmlspecialchars($order['payment_status']); ?></td>
                    <td><?php echo htmlspecialchars($order['order_status']); ?></td>
                    <td><?php echo date("d M Y", strtotime($order['created_at'])); ?></td>
                </tr>
            <?php
                }
            } else {
            ?>
                <tr>
                    <td colspan="6">No Orders Found.</td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>

    <br><br>
    <h2>Report Summary</h2>

    <div class="cards">
        <div class="card">
            <h3>Delivery Success Rate</h3>
            <h2><?php if ($total_orders > 0) { echo round(($total_delivered / $total_orders) * 100) . "%"; } else { echo "0%"; } ?></h2>
        </div>

        <div class="card">
            <h3>Pending Rate</h3>
            <h2><?php if ($total_orders > 0) { echo round(($total_pending / $total_orders) * 100) . "%"; } else { echo "0%"; } ?></h2>
        </div>

        <div class="card">
            <h3>Cancelled Rate</h3>
            <h2><?php if ($total_orders > 0) { echo round(($total_cancelled / $total_orders) * 100) . "%"; } else { echo "0%"; } ?></h2>
        </div>
    </div>

    <br><br>
</div>

<script src="../assets/js/admin.js"></script>
</body>
</html>
