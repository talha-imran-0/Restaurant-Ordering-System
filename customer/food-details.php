<?php
$page_title = "Food Details";
include "../includes/header.php";
?>

<!--=========================
    PAGE BANNER START
==========================-->

<section class="page-banner">

    <div class="container">

        <div class="page-banner-content">

            <h1>Food Details</h1>

            <p>

                <a href="../index.php">Home</a>

                /

                Food Details

            </p>

        </div>

    </div>

</section>

    <!-- FOOD DETAILS START -->

<section class="food-details section-padding">

    <div class="container">

        <div class="food-details-wrapper">

            <!-- Left Side -->

            <div class="food-image">

                <img src="../assets/images/menu/menu-1.jpg" alt="Chicken Pizza">

            </div>

            <!-- Right Side -->

            <div class="food-content">

                <span class="food-category">

                    Pizza

                </span>

                <h2>

                    Chicken Fajita Pizza

                </h2>

                <div class="food-rating">

                    ★★★★★

                    <span>(128 Reviews)</span>

                </div>

                <h3 class="food-price">

                    $13.49

                </h3>

                <p>

                    Freshly baked chicken fajita pizza topped with premium mozzarella cheese,
                    grilled chicken, onions, capsicum and our signature Urban Bites sauce.

                </p>

                <div class="quantity-area">

                    <span>Quantity</span>

                    <div class="quantity-box">

                        <button id="minus-btn">-</button>

                        <input
                            type="text"
                            id="quantity"
                            value="1"
                            readonly>

                        <button id="plus-btn">+</button>

                    </div>

                </div>

                <div class="food-buttons">

                    <a href="#" class="btn-primary">

                        Add To Cart

                    </a>

                    <a href="cart.php" class="btn-secondary">

                        Buy Now

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

<!--=========================
    PRODUCT DESCRIPTION START
==========================-->

<section class="product-description section-padding">

    <div class="container">

        <div class="section-title">

            <span>About This Food</span>

            <h2>Description</h2>

        </div>

        <div class="description-content">

            <p>

                Our Chicken Fajita Pizza is prepared with freshly baked dough,
                premium mozzarella cheese, grilled chicken, fresh onions,
                capsicum, olives and Urban Bites special pizza sauce.

            </p>

            <p>

                Every order is freshly prepared after confirmation to
                maintain the best taste, freshness and quality.

            </p>

        </div>

    </div>

</section>

<!--=========================
    PRODUCT DESCRIPTION END
==========================-->



<!--=========================
    ADDITIONAL INFORMATION START
==========================-->

<section class="additional-info section-padding">

    <div class="container">

        <div class="section-title">

            <span>Product Details</span>

            <h2>Additional Information</h2>

        </div>

        <div class="info-table">

            <div class="info-item">

                <strong>Category</strong>

                <span>Pizza</span>

            </div>

            <div class="info-item">

                <strong>Size</strong>

                <span>Large</span>

            </div>

            <div class="info-item">

                <strong>Delivery Time</strong>

                <span>30 - 40 Minutes</span>

            </div>

            <div class="info-item">

                <strong>Availability</strong>

                <span>In Stock</span>

            </div>

        </div>

    </div>

</section>

<!--=========================
    ADDITIONAL INFORMATION END
==========================-->



<!--=========================
    RELATED PRODUCTS START
==========================-->

<section class="related-products section-padding">

    <div class="container">

        <div class="section-title">

            <span>You May Also Like</span>

            <h2>Related Products</h2>

        </div>

        <div class="menu-grid">

            <!-- Product 1 -->

            <div class="menu-card">

                <div class="menu-image">

                    <img src="../assets/images/menu/menu-2.jpg" alt="Pizza">

                </div>

                <div class="menu-content">

                    <h3>Cheese Lovers Pizza</h3>

                    <p>

                        Extra cheese with premium toppings.

                    </p>

                    <div class="menu-bottom">

                        <span class="price">

                            $12.99

                        </span>

                        <a href="food-details.php" class="menu-btn">

                            View Details

                        </a>

                    </div>

                </div>

            </div>

            <!-- Product 2 -->

            <div class="menu-card">

                <div class="menu-image">

                    <img src="../assets/images/menu/menu-3.jpg" alt="Burger">

                </div>

                <div class="menu-content">

                    <h3>Zinger Burger</h3>

                    <p>

                        Crispy chicken burger with spicy sauce.

                    </p>

                    <div class="menu-bottom">

                        <span class="price">

                            $7.99

                        </span>

                        <a href="food-details.php" class="menu-btn">

                            View Details

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include "../includes/footer.php"; ?>