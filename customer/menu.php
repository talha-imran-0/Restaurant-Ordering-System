<?php

require_once "../includes/config.php";
require_once "../php/Functions.php";

include "../includes/header.php";

// Check Category
if (!isset($_GET["category"]) || empty($_GET["category"])) {
    header("Location: ../index.php");
    exit();
}

$category_id = (int) $_GET["category"];

// Get Category
$get_category = mysqli_query($conn, "
SELECT *
FROM categories
WHERE id = '$category_id'
AND status = 1
");

if (mysqli_num_rows($get_category) == 0) {
    header("Location: ../index.php");
    exit();
}

$category = mysqli_fetch_assoc($get_category);

// Get Products
$get_products = mysqli_query($conn, "
SELECT *
FROM menu_items
WHERE category_id = '$category_id'
AND status = 1
ORDER BY id DESC
");

?>

<!-- =========================
     PAGE BANNER START
========================== -->

<section class="page-banner">

    <div class="container">

        <div class="page-banner-content">

            <h1>
                <?php echo htmlspecialchars($category["name"]); ?>
            </h1>

            <div class="breadcrumb">

                <a href="../index.php">
                    Home
                </a>

                <span> / </span>

                <span>
                    <?php echo htmlspecialchars($category["name"]); ?>
                </span>

            </div>

        </div>

    </div>

</section>

<!-- =========================
     PAGE BANNER END
========================== -->


<!-- =========================
     CATEGORY PRODUCTS START
========================== -->

<section class="featured-menu">

    <div class="container">

        <div class="section-heading">

            <h5>

                <?php echo strtoupper(htmlspecialchars($category["name"])); ?>

            </h5>

            <h2>

                Delicious
                <?php echo htmlspecialchars($category["name"]); ?>
                Items

            </h2>

            <p>

                Browse all delicious
                <?php echo strtolower(htmlspecialchars($category["name"])); ?>
                available in Urban Bites.

            </p>

        </div>

        <div class="menu-grid">

<?php

if (mysqli_num_rows($get_products) > 0) {

    while ($product = mysqli_fetch_assoc($get_products)) {

?>

            <div class="menu-card">

                <img
                    src="../assets/uploads/menu/<?php echo htmlspecialchars($product["image"]); ?>"
                    alt="<?php echo htmlspecialchars($product["name"]); ?>">

                <div class="menu-content">

                    <h3>

                        <?php echo htmlspecialchars($product["name"]); ?>

                    </h3>

                    <p>

                        <?php echo htmlspecialchars($product["description"]); ?>

                    </p>

                    <div class="menu-info">

                        <span class="price">

                            $<?php echo number_format($product["price"], 2); ?>

                        </span>

                        <span class="rating">

                            ★★★★★

                        </span>

                    </div>

                    <a
                        href="food-details.php?id=<?php echo $product["id"]; ?>"
                        class="menu-btn">

                        View Details

                    </a>

                </div>

            </div>

<?php

    }

} else {

?>

            <div class="no-products">

                <h3>

                    No Products Found

                </h3>

                <p>

                    Sorry, no products are available in this category at the moment.

                </p>

            </div>

<?php

}

?>

        </div>

    </div>

</section>

<!-- =========================
     CATEGORY PRODUCTS END
========================== -->

<?php

include "../includes/footer.php";

?>