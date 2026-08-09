<?php

require_once "../includes/config.php";

// Check Admin Login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// =========================
// Delete Customer + All Related Data
// =========================

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    // Get the customer's email before deleting the user.
    // Contact messages are linked by email in this project.
    $get_customer = mysqli_query(
        $conn,
        "SELECT email FROM users WHERE id='$id' AND role='customer' LIMIT 1"
    );

    if ($get_customer && mysqli_num_rows($get_customer) > 0) {
        $customer = mysqli_fetch_assoc($get_customer);
        $customer_email = mysqli_real_escape_string($conn, $customer['email']);

        // Start transaction so the deletion is all-or-nothing.
        mysqli_begin_transaction($conn);

        try {
            // Delete order items belonging to this customer's orders.
            if (!mysqli_query(
                $conn,
                "DELETE FROM order_items
                 WHERE order_id IN (
                     SELECT id FROM orders WHERE user_id='$id'
                 )"
            )) {
                throw new Exception("Could not delete order items.");
            }

            // Delete payments belonging to this customer's orders.
            if (!mysqli_query(
                $conn,
                "DELETE FROM payments
                 WHERE order_id IN (
                     SELECT id FROM orders WHERE user_id='$id'
                 )"
            )) {
                throw new Exception("Could not delete payments.");
            }

            // Delete the customer's orders.
            if (!mysqli_query(
                $conn,
                "DELETE FROM orders WHERE user_id='$id'"
            )) {
                throw new Exception("Could not delete orders.");
            }

            // Delete cart items belonging to the customer's cart(s).
            if (!mysqli_query(
                $conn,
                "DELETE FROM cart_items
                 WHERE cart_id IN (
                     SELECT id FROM carts WHERE user_id='$id'
                 )"
            )) {
                throw new Exception("Could not delete cart items.");
            }

            // Delete the customer's cart(s).
            if (!mysqli_query(
                $conn,
                "DELETE FROM carts WHERE user_id='$id'"
            )) {
                throw new Exception("Could not delete carts.");
            }

            // Contact messages are stored with name/email rather than user_id,
            // so remove messages that belong to this customer's email.
            if (!mysqli_query(
                $conn,
                "DELETE FROM contact_messages WHERE email='$customer_email'"
            )) {
                throw new Exception("Could not delete contact messages.");
            }

            // Finally delete the customer account.
            if (!mysqli_query(
                $conn,
                "DELETE FROM users WHERE id='$id' AND role='customer'"
            )) {
                throw new Exception("Could not delete customer.");
            }

            mysqli_commit($conn);
        } catch (Exception $e) {
            mysqli_rollback($conn);
            die("Customer could not be deleted. Please check the database relationships.");
        }
    }

    header("Location: customers.php");
    exit();
}


// =========================
// Update Status
// =========================

if (isset($_POST['update_status'])) {
    $id = (int)$_POST['user_id'];
    $status = (int)$_POST['status'];

    mysqli_query(
        $conn,
        "UPDATE users SET status='$status' WHERE id='$id'"
    );

    header("Location: customers.php");
    exit();
}

// =========================
// Search
// =========================

$search = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string(
        $conn,
        trim($_GET['search'])
    );
}

$query = "
    SELECT *
    FROM users
    WHERE role='customer'
    AND
    (
        name LIKE '%$search%'
        OR email LIKE '%$search%'
        OR phone LIKE '%$search%'
    )
    ORDER BY id DESC
";

$get_customers = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="main">

    <h1>Manage Customers</h1>

    <div class="search-box">
        <form method="GET">
            <input type="text" name="search" placeholder="Search Customer..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if (mysqli_num_rows($get_customers) > 0) {
                while ($customer = mysqli_fetch_assoc($get_customers)) {
            ?>
                <tr>
                    <td><?php echo $customer['id']; ?></td>
                    <td><?php echo htmlspecialchars($customer['name']); ?></td>
                    <td><?php echo htmlspecialchars($customer['email']); ?></td>
                    <td><?php echo htmlspecialchars($customer['phone']); ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="user_id" value="<?php echo $customer['id']; ?>">
                            <select name="status" class="admin-status-select">
                                <option value="1" <?php if($customer['status']==1) echo "selected"; ?>>Active</option>
                                <option value="0" <?php if($customer['status']==0) echo "selected"; ?>>Inactive</option>
                            </select>
                            <button type="submit" name="update_status" class="admin-customer-save">Save</button>
                        </form>
                    </td>
                    <td><?php echo date("d M Y", strtotime($customer['created_at'])); ?></td>
                    <td>
                        <a href="customers.php?delete=<?php echo $customer['id']; ?>" class="action delete" onclick="return confirm('Delete this customer?')">Delete</a>
                    </td>
                </tr>
            <?php
                }
            } else {
            ?>
                <tr>
                    <td colspan="7">No Customers Found.</td>
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