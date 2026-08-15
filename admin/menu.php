<?php

session_start();

require_once "../includes/config.php";
require_once "../php/Auth.php";

/* CHECK ADMIN LOGIN */
require_admin();

/* ADD MENU ITEM */
if (isset($_POST['add_menu'])) {
    $category_id = (int)$_POST['category_id'];
    $name        = mysqli_real_escape_string($conn, trim($_POST['name']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $price       = mysqli_real_escape_string($conn, trim($_POST['price']));
    $status      = (int)$_POST['status'];
    $image       = "";

    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . $_FILES['image']['name'];

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../assets/uploads/menu/" . $image
        );
    }

    mysqli_query(
        $conn,
        "INSERT INTO menu_items (category_id, name, description, price, image, status) 
         VALUES ('$category_id', '$name', '$description', '$price', '$image', '$status')"
    );

    header("Location: menu.php");
    exit();
}

/* DELETE MENU ITEM */
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    mysqli_query(
        $conn,
        "DELETE FROM menu_items WHERE id = '$id'"
    );

    header("Location: menu.php");
    exit();
}

/* GET MENU ITEM FOR EDIT */
$edit_menu = null;

if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];

    $result = mysqli_query(
        $conn,
        "SELECT * FROM menu_items WHERE id = '$id' LIMIT 1"
    );

    if (mysqli_num_rows($result) > 0) {
        $edit_menu = mysqli_fetch_assoc($result);
    }
}

/* UPDATE MENU ITEM */
if (isset($_POST['update_menu'])) {
    $id          = (int)$_POST['menu_id'];
    $category_id = (int)$_POST['category_id'];
    $name        = mysqli_real_escape_string($conn, trim($_POST['name']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $price       = mysqli_real_escape_string($conn, trim($_POST['price']));
    $status      = (int)$_POST['status'];

    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . $_FILES['image']['name'];

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../assets/uploads/menu/" . $image
        );

        mysqli_query(
            $conn,
            "UPDATE menu_items 
             SET category_id = '$category_id', name = '$name', description = '$description', price = '$price', image = '$image', status = '$status' 
             WHERE id = '$id'"
        );
    } else {
        mysqli_query(
            $conn,
            "UPDATE menu_items 
             SET category_id = '$category_id', name = '$name', description = '$description', price = '$price', status = '$status' 
             WHERE id = '$id'"
        );
    }

    header("Location: menu.php");
    exit();
}

/* GET CATEGORIES */
$get_category_list = mysqli_query(
    $conn,
    "SELECT * FROM categories WHERE status = 1 ORDER BY name ASC"
);

/* GET MENU ITEMS */
$get_menu = mysqli_query(
    $conn,
    "SELECT menu_items.*, categories.name AS category_name 
     FROM menu_items 
     LEFT JOIN categories ON menu_items.category_id = categories.id 
     ORDER BY menu_items.id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Menu</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="main">

    <h1>Manage Menu</h1>

    <div class="form-box">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="menu_id" value="<?php echo isset($edit_menu['id']) ? $edit_menu['id'] : ''; ?>">

            <select name="category_id" required>
                <option value="">Select Category</option>
                <?php
                mysqli_data_seek($get_category_list, 0);
                while ($category = mysqli_fetch_assoc($get_category_list)) {
                ?>
                    <option value="<?php echo $category['id']; ?>" <?php if (isset($edit_menu) && $edit_menu['category_id'] == $category['id']) { echo "selected"; } ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php
                }
                ?>
            </select>

            <input type="text" name="name" placeholder="Food Name" value="<?php echo isset($edit_menu['name']) ? htmlspecialchars($edit_menu['name']) : ''; ?>" required>

            <textarea name="description" placeholder="Food Description" required><?php echo isset($edit_menu['description']) ? htmlspecialchars($edit_menu['description']) : ''; ?></textarea>

            <input type="number" step="0.01" name="price" placeholder="Price" value="<?php echo isset($edit_menu['price']) ? $edit_menu['price'] : ''; ?>" required>

            <input type="file" name="image" <?php if (!$edit_menu) { ?>required<?php } ?>>

            <?php if (isset($edit_menu) && !empty($edit_menu['image'])) { ?>
                <br>
                <img src="../assets/uploads/menu/<?php echo htmlspecialchars($edit_menu['image']); ?>" class="menu-image" alt="Current Image">
                <br><br>
            <?php } ?>

            <select name="status">
                <option value="1" <?php if (isset($edit_menu) && $edit_menu['status'] == 1) { echo "selected"; } ?>>Active</option>
                <option value="0" <?php if (isset($edit_menu) && $edit_menu['status'] == 0) { echo "selected"; } ?>>Inactive</option>
            </select>

            <?php if ($edit_menu) { ?>
                <button type="submit" name="update_menu">Update Menu Item</button>
                <a href="menu.php" class="cancel">Cancel</a>
            <?php } else { ?>
                <button type="submit" name="add_menu">Add Menu Item</button>
            <?php } ?>
        </form>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Category</th>
                    <th>Food Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($get_menu) > 0) {
                    while ($menu = mysqli_fetch_assoc($get_menu)) {
                ?>
                    <tr>
                        <td><?php echo $menu['id']; ?></td>
                        <td>
                            <?php if (!empty($menu['image'])) { ?>
                                <img src="../assets/uploads/menu/<?php echo htmlspecialchars($menu['image']); ?>" class="menu-image" alt="Food Image">
                            <?php } else {
                                echo "No Image";
                            } ?>
                        </td>
                        <td><?php echo htmlspecialchars($menu['category_name']); ?></td>
                        <td><?php echo htmlspecialchars($menu['name']); ?></td>
                        <td><?php echo htmlspecialchars($menu['description']); ?></td>
                        <td>$<?php echo number_format($menu['price'], 2); ?></td>
                        <td>
                            <?php
                            if ($menu['status'] == 1) {
                                echo "Active";
                            } else {
                                echo "Inactive";
                            }
                            ?>
                        </td>
                        <td>
                            <a href="menu.php?edit=<?php echo $menu['id']; ?>" class="action-btn edit">Edit</a>
                            <a href="menu.php?delete=<?php echo $menu['id']; ?>" class="action-btn delete" onclick="return confirm('Delete this menu item?')">Delete</a>
                        </td>
                    </tr>
                <?php
                    }
                } else {
                ?>
                    <tr>
                        <td colspan="8">No Menu Items Found.</td>
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