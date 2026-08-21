<?php

session_start();

require_once "../includes/config.php";
require_once "../php/Auth.php";

/* CHECK ADMIN LOGIN */
require_admin();

/* UPDATE ORDER STATUS */
if (isset($_POST['update_order'])) {
    $order_id = (int)$_POST['order_id'];
    $order_status = mysqli_real_escape_string($conn, $_POST['order_status']);
    $payment_status = mysqli_real_escape_string($conn, $_POST['payment_status']);

    mysqli_query($conn, "UPDATE orders SET order_status='$order_status', payment_status='$payment_status' WHERE id='$order_id'");

    header("Location: orders.php");
    exit();
}

/* GET ORDERS */
$get_orders = mysqli_query($conn, "SELECT orders.*, users.name FROM orders LEFT JOIN users ON orders.user_id = users.id ORDER BY orders.id DESC");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="main">
    <h1>Manage Orders</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Order No</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Order</th>
                <th>Date</th>
                <th>Update</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (mysqli_num_rows($get_orders) > 0) {
            while ($order = mysqli_fetch_assoc($get_orders)) {
                /* UNIQUE FORM ID FOR EVERY ORDER */
                $form_id = "order_form_" . $order['id'];
        ?>
            <tr>
                <!-- ID -->
                <td><?php echo $order['id']; ?></td>

                <!-- ORDER NUMBER -->
                <td><?php echo htmlspecialchars($order['order_number']); ?></td>

                <!-- CUSTOMER -->
                <td><?php echo htmlspecialchars($order['name'] ?? 'Unknown'); ?></td>

                <!-- TOTAL -->
                <td>$<?php echo number_format($order['total'], 2); ?></td>

                <!-- PAYMENT -->
                <td>
                    <!-- HIDDEN FORM -->
                    <form id="<?php echo $form_id; ?>" method="POST">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <input type="hidden" name="update_order" value="1">
                    </form>

                    <select class="admin-order-select" name="payment_status" form="<?php echo $form_id; ?>">
                        <option value="pending" <?php if ($order['payment_status'] === 'pending') { echo 'selected'; } ?>>Pending</option>
                        <option value="paid" <?php if ($order['payment_status'] === 'paid') { echo 'selected'; } ?>>Paid</option>
                        <option value="failed" <?php if ($order['payment_status'] === 'failed') { echo 'selected'; } ?>>Failed</option>
                    </select>
                </td>

                <!-- ORDER STATUS -->
                <td>
                    <select class="admin-order-select" name="order_status" form="<?php echo $form_id; ?>">
                        <option value="new" <?php if ($order['order_status'] === 'new') { echo 'selected'; } ?>>New</option>
                        <option value="preparing" <?php if ($order['order_status'] === 'preparing') { echo 'selected'; } ?>>Preparing</option>
                        <option value="on_the_way" <?php if ($order['order_status'] === 'on_the_way') { echo 'selected'; } ?>>On The Way</option>
                        <option value="delivered" <?php if ($order['order_status'] === 'delivered') { echo 'selected'; } ?>>Delivered</option>
                        <option value="cancelled" <?php if ($order['order_status'] === 'cancelled') { echo 'selected'; } ?>>Cancelled</option>
                    </select>
                </td>

                <!-- DATE -->
                <td><?php echo date("d M Y", strtotime($order['created_at'])); ?></td>

                <!-- UPDATE -->
                <td><button type="submit" class="admin-save-btn" name="update_order" form="<?php echo $form_id; ?>">Save</button></td>
            </tr>
        <?php
            }
        } else {
        ?>
            <tr>
                <td colspan="8">No Orders Found.</td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>
</div>

<script src="../assets/js/admin.js"></script>
</body>
</html>
