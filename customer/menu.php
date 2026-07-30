<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";

include "../includes/header.php";

// Check Category Parameter
if (!isset($_GET["category"]) || empty($_GET["category"])) {
    header("Location: ../index.php");
    exit();
}

$category_id = (int) $_GET["category"];

// Prepared Statement for Category
$stmt_cat = mysqli_prepare($conn, "SELECT * FROM categories WHERE id = ? AND status = 1");
mysqli_stmt_bind_param($stmt_cat, "i", $category_id);
mysqli_stmt_execute($stmt_cat);
$get_category = mysqli_stmt_get_result($stmt_cat);

if (mysqli_num_rows($get_category) === 0) {
    header("Location: ../index.php");
    exit();
}

$category = mysqli_fetch_assoc($get_category);

// Prepared Statement for Products
$stmt_prod = mysqli_prepare($conn, "SELECT * FROM menu_items WHERE category_id = ? AND status = 1 ORDER BY id DESC");
mysqli_stmt_bind_param($stmt_prod, "i", $category_id);
mysqli_stmt_execute($stmt_prod);
$get_products = mysqli_stmt_get_result($stmt_prod);

?>

<!-- PAGE BANNER START -->
<section class="page-banner">
    <div class="container">
        <div class="page-banner-content">
            <h1><?php echo htmlspecialchars($category["name"]); ?></h1>
            <div class="breadcrumb">
                <a href="../index.php">Home</a>
                <span> / </span>
                <span><?php echo htmlspecialchars($category["name"]); ?></span>
            </div>
        </div>
    </div>
</section>
<!-- PAGE BANNER END -->

<!-- CATEGORY PRODUCTS START -->
<section class="featured-menu">
    <div class="container">
        
        <div class="section-heading">
            <h5><?php echo strtoupper(htmlspecialchars($category["name"])); ?></h5>
            <h2>Delicious <?php echo htmlspecialchars($category["name"]); ?> Items</h2>
            <p>Browse all delicious <?php echo strtolower(htmlspecialchars($category["name"])); ?> available in Urban Bites.</p>
        </div>

        <div class="menu-grid">
            <?php if (mysqli_num_rows($get_products) > 0): ?>
                <?php while ($product = mysqli_fetch_assoc($get_products)): ?>
                    <div class="menu-card">
                        <img src="../assets/uploads/menu/<?php echo htmlspecialchars($product["image"]); ?>" alt="<?php echo htmlspecialchars($product["name"]); ?>">
                        <div class="menu-content">
                            <h3><?php echo htmlspecialchars($product["name"]); ?></h3>
                            <p><?php echo htmlspecialchars($product["description"]); ?></p>
                            
                            <div class="menu-info">
                                <span class="price">$<?php echo number_format($product["price"], 2); ?></span>
                                <span class="rating">★★★★★</span>
                            </div>

                            <a href="food-details.php?id=<?php echo (int)$product["id"]; ?>" class="menu-btn">View Details</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-products">
                    <h3>No Products Found</h3>
                    <p>Sorry, no products are available in this category at the moment.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>
<!-- CATEGORY PRODUCTS END -->

<?php include "../includes/footer.php"; ?>