<?php

require_once "../includes/config.php";

// ===============================
// Check Admin Login
// ===============================

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// ===============================
// Success Message
// ===============================

$message = "";

// ===============================
// Add Coupon
// ===============================

if (isset($_POST['add_coupon'])) {
    $code = strtoupper(
        mysqli_real_escape_string(
            $conn,
            trim($_POST['code'])
        )
    );

    $discount = (float) $_POST['discount'];
    $status = (int) $_POST['status'];
    $expiry_date = mysqli_real_escape_string(
        $conn,
        $_POST['expiry_date']
    );

    $check_coupon = mysqli_query(
        $conn,
        "SELECT id FROM coupons WHERE code='$code' LIMIT 1"
    );

    if (mysqli_num_rows($check_coupon) > 0) {
        $message = "Coupon Code Already Exists.";
    } else {
        mysqli_query(
            $conn,
            "INSERT INTO coupons (code, discount, status, expiry_date) VALUES ('$code', '$discount', '$status', '$expiry_date')"
        );

        header("Location: coupons.php?success=1");
        exit();
    }
}

// ===============================
// Delete Coupon
// ===============================

if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];

    mysqli_query(
        $conn,
        "DELETE FROM coupons WHERE id='$id'"
    );

    header("Location: coupons.php?deleted=1");
    exit();
}

// ===============================
// Get Coupon For Edit
// ===============================

$edit_coupon = null;

if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];

    $result = mysqli_query(
        $conn,
        "SELECT * FROM coupons WHERE id='$id' LIMIT 1"
    );

    if (mysqli_num_rows($result) > 0) {
        $edit_coupon = mysqli_fetch_assoc($result);
    }
}

// ===============================
// Update Coupon
// ===============================

if (isset($_POST['update_coupon'])) {
    $coupon_id = (int) $_POST['coupon_id'];

    $code = strtoupper(
        mysqli_real_escape_string(
            $conn,
            trim($_POST['code'])
        )
    );

    $discount = (float) $_POST['discount'];
    $status = (int) $_POST['status'];
    $expiry_date = mysqli_real_escape_string(
        $conn,
        $_POST['expiry_date']
    );

    $check_coupon = mysqli_query(
        $conn,
        "SELECT id FROM coupons WHERE code='$code' AND id!='$coupon_id' LIMIT 1"
    );

    if (mysqli_num_rows($check_coupon) > 0) {
        $message = "Coupon Code Already Exists.";
    } else {
        mysqli_query(
            $conn,
            "UPDATE coupons SET code='$code', discount='$discount', status='$status', expiry_date='$expiry_date' WHERE id='$coupon_id'"
        );

        header("Location: coupons.php?updated=1");
        exit();
    }
}

// ===============================
// Search Coupon
// ===============================

$search = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string(
        $conn,
        trim($_GET['search'])
    );
}

$get_coupons = mysqli_query(
    $conn,
    "SELECT * FROM coupons WHERE code LIKE '%$search%' ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Coupons</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="main">

    <h1>Manage Coupons</h1>

    <?php if(isset($_GET['success'])){ ?>
        <div class="success">
            Coupon Added Successfully.
        </div>
    <?php } ?>

    <?php if(isset($_GET['updated'])){ ?>
        <div class="success">
            Coupon Updated Successfully.
        </div>
    <?php } ?>

    <?php if(isset($_GET['deleted'])){ ?>
        <div class="success">
            Coupon Deleted Successfully.
        </div>
    <?php } ?>

    <?php if(!empty($message)){ ?>
        <div class="error">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <div class="search-box">
        <form method="GET">
            <input type="text" name="search" placeholder="Search Coupon Code..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="form-box">
        <form method="POST">
            <input type="hidden" name="coupon_id" value="<?php echo isset($edit_coupon['id']) ? $edit_coupon['id'] : ''; ?>">

            <input type="text" name="code" placeholder="Coupon Code" value="<?php echo isset($edit_coupon['code']) ? htmlspecialchars($edit_coupon['code']) : ''; ?>" required>

            <input type="number" step="0.01" min="1" max="100" name="discount" placeholder="Discount (%)" value="<?php echo isset($edit_coupon['discount']) ? $edit_coupon['discount'] : ''; ?>" required>

            <input type="date" name="expiry_date" value="<?php echo isset($edit_coupon['expiry_date']) ? $edit_coupon['expiry_date'] : ''; ?>" required>

            <select name="status">
                <option value="1" <?php if(isset($edit_coupon) && $edit_coupon['status']==1){ echo "selected"; } ?>>Active</option>
                <option value="0" <?php if(isset($edit_coupon) && $edit_coupon['status']==0){ echo "selected"; } ?>>Inactive</option>
            </select>

            <?php if($edit_coupon){ ?>
                <button type="submit" name="update_coupon">Update Coupon</button>
                <a href="coupons.php" class="cancel">Cancel</a>
            <?php }else{ ?>
                <button type="submit" name="add_coupon">Add Coupon</button>
            <?php } ?>
        </form>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Coupon Code</th>
                <th>Discount</th>
                <th>Status</th>
                <th>Expiry Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if(mysqli_num_rows($get_coupons)>0){
            while($coupon=mysqli_fetch_assoc($get_coupons)){
        ?>
            <tr>
                <td><?php echo $coupon['id']; ?></td>
                <td><?php echo htmlspecialchars($coupon['code']); ?></td>
                <td><?php echo $coupon['discount']; ?>%</td>
                <td>
                    <?php
                    if($coupon['status']==1){
                        echo "Active";
                    }else{
                        echo "Inactive";
                    }
                    ?>
                </td>
                <td><?php echo date("d M Y", strtotime($coupon['expiry_date'])); ?></td>
                <td>
                    <a href="coupons.php?edit=<?php echo $coupon['id']; ?>" class="action-btn edit">Edit</a>
                    <a href="coupons.php?delete=<?php echo $coupon['id']; ?>" class="action-btn delete" onclick="return confirm('Delete this coupon?')">Delete</a>
                </td>
            </tr>
        <?php
            }
        }else{
        ?>
            <tr>
                <td colspan="6">No Coupons Found.</td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>

</div>

</body>
</html>