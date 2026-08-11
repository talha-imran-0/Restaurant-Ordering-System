<?php

require_once "../includes/config.php";

// ==========================
// Check Admin Login
// ==========================

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// ==========================
// Add Category
// ==========================

if (isset($_POST['add_category'])) {
    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $status = (int)$_POST['status'];

    $image = "";

    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . $_FILES['image']['name'];

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../assets/uploads/categories/" . $image
        );
    }

    mysqli_query(
        $conn,
        "INSERT INTO categories (name,description,image,status) VALUES ('$name','$description','$image','$status')"
    );

    header("Location: categories.php");
    exit();
}

// ==========================
// Delete Category
// ==========================

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    mysqli_query(
        $conn,
        "DELETE FROM categories WHERE id='$id'"
    );

    header("Location: categories.php");
    exit();
}

// ==========================
// Edit Category
// ==========================

if (isset($_POST['update_category'])) {
    $id = (int)$_POST['category_id'];

    $name = mysqli_real_escape_string($conn, trim($_POST['name']));
    $description = mysqli_real_escape_string($conn, trim($_POST['description']));
    $status = (int)$_POST['status'];

    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . $_FILES['image']['name'];

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "../assets/uploads/categories/" . $image
        );

        mysqli_query(
            $conn,
            "UPDATE categories SET name='$name', description='$description', image='$image', status='$status' WHERE id='$id'"
        );
    } else {
        mysqli_query(
            $conn,
            "UPDATE categories SET name='$name', description='$description', status='$status' WHERE id='$id'"
        );
    }

    header("Location: categories.php");
    exit();
}

// ==========================
// Get Category For Edit
// ==========================

$edit_category = null;

if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];

    $result = mysqli_query(
        $conn,
        "SELECT * FROM categories WHERE id='$id' LIMIT 1"
    );

    if (mysqli_num_rows($result) > 0) {
        $edit_category = mysqli_fetch_assoc($result);
    }
}

// ==========================
// Get Categories
// ==========================

$get_categories = mysqli_query(
    $conn,
    "SELECT * FROM categories ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>

<?php include "sidebar.php"; ?>

<div class="main">

    <h1>Manage Categories</h1>

    <div class="form-box">
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="category_id" value="<?php echo isset($edit_category['id']) ? $edit_category['id'] : ''; ?>">

            <input type="text" name="name" placeholder="Category Name" value="<?php echo isset($edit_category['name']) ? htmlspecialchars($edit_category['name']) : ''; ?>" required>

            <textarea name="description" placeholder="Category Description" required><?php echo isset($edit_category['description']) ? htmlspecialchars($edit_category['description']) : ''; ?></textarea>

            <input type="file" name="image" <?php if(!$edit_category){ ?>required<?php } ?>>

            <select name="status">
                <option value="1" <?php if(isset($edit_category) && $edit_category['status']==1){ echo "selected"; } ?>>Active</option>
                <option value="0" <?php if(isset($edit_category) && $edit_category['status']==0){ echo "selected"; } ?>>Inactive</option>
            </select>

            <?php if($edit_category){ ?>
                <button type="submit" name="update_category">Update Category</button>
                <a href="categories.php" class="cancel">Cancel</a>
            <?php } else { ?>
                <button type="submit" name="add_category">Add Category</button>
            <?php } ?>
        </form>
    </div>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if(mysqli_num_rows($get_categories) > 0){
                while($category = mysqli_fetch_assoc($get_categories)){
            ?>
                <tr>
                    <td><?php echo $category['id']; ?></td>
                    <td>
                        <?php
                        if(!empty($category['image'])){
                        ?>
                            <img src="../assets/uploads/categories/<?php echo htmlspecialchars($category['image']); ?>" class="category-image" alt="Category">
                        <?php
                        }else{
                            echo "No Image";
                        }
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                    <td><?php echo htmlspecialchars($category['description']); ?></td>
                    <td>
                        <?php
                        if($category['status']==1){
                            echo "Active";
                        }else{
                            echo "Inactive";
                        }
                        ?>
                    </td>
                    <td>
                        <a href="categories.php?edit=<?php echo $category['id']; ?>" class="action-btn edit">Edit</a>
                        <a href="categories.php?delete=<?php echo $category['id']; ?>" class="action-btn delete" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                    </td>
                </tr>
            <?php
                }
            }else{
            ?>
                <tr>
                    <td colspan="6">No Categories Found.</td>
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