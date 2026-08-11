<?php

require_once "../includes/config.php";

// Check Admin Login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// ======================================
// Count Categories
// ======================================

$get_categories = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM categories"
);

$total_categories = mysqli_fetch_assoc($get_categories)['total'];

// ======================================
// Count Menu Items
// ======================================

$get_menu = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM menu_items"
);

$total_menu = mysqli_fetch_assoc($get_menu)['total'];

// ======================================
// Count Customers
// ======================================

$get_customers = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM users WHERE role='customer'"
);

$total_customers = mysqli_fetch_assoc($get_customers)['total'];

// ======================================
// Count Orders
// ======================================

$get_orders = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM orders"
);

$total_orders = mysqli_fetch_assoc($get_orders)['total'];

// ======================================
// Count Contact Messages
// ======================================

$get_messages = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM contact_messages"
);

$total_messages = mysqli_fetch_assoc($get_messages)['total'];

// ======================================
// Today's Orders
// ======================================

$get_today_orders = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM orders WHERE DATE(created_at)=CURDATE()"
);

$today_orders = mysqli_fetch_assoc($get_today_orders)['total'];

// ======================================
// Pending Orders
// ======================================

$get_pending_orders = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM orders WHERE order_status='New'"
);

$pending_orders = mysqli_fetch_assoc($get_pending_orders)['total'];

// ======================================
// Delivered Orders
// ======================================

$get_delivered_orders = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM orders WHERE order_status='Delivered'"
);

$delivered_orders = mysqli_fetch_assoc($get_delivered_orders)['total'];

// ======================================
// Cancelled Orders
// ======================================

$get_cancelled_orders = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM orders WHERE order_status='Cancelled'"
);

$cancelled_orders = mysqli_fetch_assoc($get_cancelled_orders)['total'];

// ======================================
// Total Revenue
// ======================================

$get_total_revenue = mysqli_query(
    $conn,
    "SELECT SUM(total) AS revenue FROM orders WHERE payment_status='Paid'"
);

$total_revenue = mysqli_fetch_assoc($get_total_revenue)['revenue'];

if ($total_revenue == "") {
    $total_revenue = 0;
}

// ======================================
// Today's Revenue
// ======================================

$get_today_revenue = mysqli_query(
    $conn,
    "SELECT SUM(total) AS revenue FROM orders WHERE payment_status='Paid' AND DATE(created_at)=CURDATE()"
);

$today_revenue = mysqli_fetch_assoc($get_today_revenue)['revenue'];

if ($today_revenue == "") {
    $today_revenue = 0;
}

// ======================================
// Recent 5 Orders
// ======================================

$get_recent_orders = mysqli_query(
    $conn,
    "SELECT orders.*, users.name 
     FROM orders 
     LEFT JOIN users ON orders.user_id = users.id 
     ORDER BY orders.id DESC 
     LIMIT 5"
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="main">

    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></h1>

    <!-- ======================================
         MAIN DASHBOARD CARDS
    ====================================== -->
    <div class="cards">
        <div class="card">
            <h3>Total Categories</h3>
            <h2><?php echo $total_categories; ?></h2>
        </div>

        <div class="card">
            <h3>Total Menu Items</h3>
            <h2><?php echo $total_menu; ?></h2>
        </div>

        <div class="card">
            <h3>Total Customers</h3>
            <h2><?php echo $total_customers; ?></h2>
        </div>

        <div class="card">
            <h3>Total Orders</h3>
            <h2><?php echo $total_orders; ?></h2>
        </div>

        <div class="card">
            <h3>Contact Messages</h3>
            <h2><?php echo $total_messages; ?></h2>
        </div>
    </div>

    <br><br>

    <!-- ======================================
         ORDER / REVENUE CARDS
    ====================================== -->
    <div class="cards">
        <div class="card">
            <h3>Today's Orders</h3>
            <h2><?php echo $today_orders; ?></h2>
        </div>

        <div class="card">
            <h3>Pending Orders</h3>
            <h2><?php echo $pending_orders; ?></h2>
        </div>

        <div class="card">
            <h3>Delivered Orders</h3>
            <h2><?php echo $delivered_orders; ?></h2>
        </div>

        <div class="card">
            <h3>Cancelled Orders</h3>
            <h2><?php echo $cancelled_orders; ?></h2>
        </div>

        <div class="card">
            <h3>Total Revenue</h3>
            <h2>$ <?php echo number_format($total_revenue, 2); ?></h2>
        </div>

        <div class="card">
            <h3>Today's Revenue</h3>
            <h2>$ <?php echo number_format($today_revenue, 2); ?></h2>
        </div>
    </div>

    <br><br>

    <!-- ======================================
         QUICK ACCESS
    ====================================== -->
    <h2>Quick Access</h2>

    <div class="cards">
        <div class="card">
            <h3>Manage Categories</h3>
            <p>Add, Edit and Delete Food Categories</p>
            <br>
            <a href="categories.php" style="background:#d62828;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;">Open</a>
        </div>

        <div class="card">
            <h3>Manage Menu</h3>
            <p>Add and Update Menu Items</p>
            <br>
            <a href="menu.php" style="background:#d62828;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;">Open</a>
        </div>

        <div class="card">
            <h3>Manage Orders</h3>
            <p>View Customer Orders</p>
            <br>
            <a href="orders.php" style="background:#d62828;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;">Open</a>
        </div>

        <div class="card">
            <h3>Customers</h3>
            <p>View Registered Customers</p>
            <br>
            <a href="customers.php" style="background:#d62828;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;">Open</a>
        </div>

        <div class="card">
            <h3>Contact Messages</h3>
            <p>Read Customer Messages</p>
            <br>
            <a href="contact_messages.php" style="background:#d62828;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;">Open</a>
        </div>

        <div class="card">
            <h3>Restaurant Settings</h3>
            <p>Manage Restaurant Information</p>
            <br>
            <a href="settings.php" style="background:#d62828;color:#fff;padding:10px 20px;text-decoration:none;border-radius:5px;">Open</a>
        </div>
    </div>

    <br><br>

    <!-- ======================================
         RECENT ORDERS
    ====================================== -->
    <h2>Recent Orders</h2>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Order No</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if (mysqli_num_rows($get_recent_orders) > 0) {
                while ($order = mysqli_fetch_assoc($get_recent_orders)) {
            ?>
                <tr>
                    <!-- ORDER NUMBER -->
                    <td><?php echo htmlspecialchars($order['order_number']); ?></td>

                    <!-- CUSTOMER -->
                    <td><?php echo htmlspecialchars($order['name']); ?></td>

                    <!-- TOTAL -->
                    <td>$ <?php echo number_format($order['total'], 2); ?></td>

                    <!-- PAYMENT STATUS -->
                    <td>
                        <?php
                        $payment_status = strtolower(trim($order['payment_status']));

                        if ($payment_status == "paid") {
                        ?>
                            <span class="status-active">Paid</span>
                        <?php
                        } elseif ($payment_status == "failed") {
                        ?>
                            <span class="status-inactive">Failed</span>
                        <?php
                        } else {
                        ?>
                            <span class="status-pending">Pending</span>
                        <?php
                        }
                        ?>
                    </td>

                    <!-- ORDER STATUS -->
                    <td>
                        <?php
                        $order_status = strtolower(trim($order['order_status']));

                        if ($order_status == "delivered") {
                        ?>
                            <span class="status-active">Delivered</span>
                        <?php
                        } elseif ($order_status == "cancelled") {
                        ?>
                            <span class="status-inactive">Cancelled</span>
                        <?php
                        } elseif ($order_status == "on_the_way") {
                        ?>
                            <span class="status-on-the-way">On The Way</span>
                        <?php
                        } elseif ($order_status == "preparing") {
                        ?>
                            <span class="status-preparing">Preparing</span>
                        <?php
                        } else {
                        ?>
                            <span class="status-pending">New</span>
                        <?php
                        }
                        ?>
                    </td>

                    <!-- DATE -->
                    <td><?php echo date("d M Y", strtotime($order['created_at'])); ?></td>
                </tr>
            <?php
                }
            } else {
            ?>
                <tr>
                    <td colspan="6">No Recent Orders Found.</td>
                </tr>
            <?php
            }
            ?>
            </tbody>
        </table>
    </div>

</div>

<script src="../assets/js/admin.js"></script>
</body>
</html>