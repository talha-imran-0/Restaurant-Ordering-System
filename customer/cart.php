<?php
$page_title = "Shopping Cart";
include "../includes/header.php";
?>

<!--=========================
        PAGE BANNER START
==========================-->

<section class="page-banner">

    <div class="container">

        <div class="page-banner-content">

            <h1>

                Shopping Cart

            </h1>

            <p>

                <a href="../index.php">Home</a>

                /

                Shopping Cart

            </p>

        </div>

    </div>

</section>

<!--=========================
        CART START
==========================-->

<section class="cart section-padding">

    <div class="container">

        <div class="cart-wrapper">

            <div class="cart-table">

                <table>

                    <thead>

                        <tr>

                            <th>Product</th>

                            <th>Price</th>

                            <th>Quantity</th>

                            <th>Total</th>

                            <th>Remove</th>

                        </tr>

                    </thead>

                    <tbody>

                        <tr>

                            <td>

                                <div class="cart-product">

                                    <img src="../assets/images/menu/menu-1.jpg" alt="Pizza">

                                    <span>

                                        Chicken Fajita Pizza

                                    </span>

                                </div>

                            </td>

                            <td>

                                $13.49

                            </td>

                            <td>

                                <div class="quantity-box">

                                    <button>-</button>

                                    <input type="text" value="1" readonly>

                                    <button>+</button>

                                </div>

                            </td>

                            <td>

                                $13.49

                            </td>

                            <td>

                                ✖

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

            <div class="cart-summary">

                <h3>

                    Cart Summary

                </h3>

                <div class="summary-item">

                    <span>Subtotal</span>

                    <span>$13.49</span>

                </div>

                <div class="summary-item">

                    <span>Delivery</span>

                    <span>$2.00</span>

                </div>

                <div class="summary-item total">

                    <span>Total</span>

                    <span>$15.49</span>

                </div>

                <a href="checkout.php" class="btn-primary">

                    Proceed To Checkout

                </a>

            </div>

        </div>

    </div>

</section>

<?php include "../includes/footer.php"; ?>
