<?php

require_once "includes/config.php";
require_once "php/Functions.php";

include "includes/header.php";
include "includes/navbar.php";

?>


<!-- Hero Section Starts -->

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <h4>WELCOME TO URBAN BITES</h4>
                <h1>
                    Fresh & Delicious <br>
                    <span>Pizza</span> Delivered <br>
                    To Your Door
                </h1>
                <p> Craving something delicious? Enjoy hot pizzas, juicy burgers, creamy pasta and BBQ made with fresh ingredients and delivered fast to your home.</p>               
                <a href="customer/menu.php" class="btn"> Order Now </a>
                <a href="customer/menu.php" class="btn btn-outline"> View Menu </a>
            </div>
            <div class="hero-image">
                <img src="assets/images/hero/hero-pizza.png" alt="Hero Pizza">
            </div>
        </div>
    </div>
</section>

<!-- Hero Section Ends -->

<!-- Categories Section Start -->

<section class="categories">
    <div class="container">
        <div class="section-heading">
            <h5>OUR CATEGORIES</h5>
            <h2>Explore Our Menu Categories</h2>
            <p>Choose your favorite food category and enjoy delicious meals prepared with fresh ingredients.</p>
        </div>
        <div class="categories-grid">

            <!-- Pizza -->
            <a href="#" class="category-card">
                <img src="assets/images/categories/pizza.jpg" alt="Pizza">
                <h3>Pizza</h3>
            </a>

            <!-- Burger  -->
            <a href="#" class="category-card">
                <img src="assets/images/categories/burger.jpg" alt="Burger">
                <h3>Burger</h3>
            </a>

            <!-- Pasta -->
            <a href="#" class="category-card">
                <img src="assets/images/categories/pasta.jpg" alt="Pasta">
                <h3>Pasta</h3>
            </a>

            <!-- BBQ -->
            <a href="#" class="category-card">
                <img src="assets/images/categories/bbq.jpg" alt="BBQ">
                <h3>BBQ</h3>
            </a>

            <!-- Drinks -->
            <a href="#" class="category-card">
                <img src="assets/images/categories/drinks.jpg" alt="Drinks">
                <h3>Drinks</h3>
            </a>

            <!-- Dessert -->
            <a href="#" class="category-card">
                <img src="assets/images/categories/dessert.jpg" alt="Dessert">
                <h3>Dessert</h3>
            </a>
        </div>
    </div>
</section>

<!-- Categories Section End  -->

<!-- Featured Menu Section Start -->

<section class="featured-menu">
    <div class="container">
        <div class="section-heading">
            <h5>FEATURED MENU</h5>
            <h2>Our Popular Dishes</h2>
            <p>Discover our most loved dishes made with fresh ingredients, rich flavors, and served with passion.</p>
        </div>

        <!-- Menu Grid -->
        <div class="menu-grid">

            <!-- Menu Item 1 -->
            <div class="menu-card">
                <img src="assets/images/menu/menu-1.jpg" alt="Pizza Supreme">
                <div class="menu-content">
                    <h3>Chocolate Cake</h3>
                    <p>Soft chocolate sponge topped with rich chocolate cream.</p>
                    <div class="menu-info">
                        <span class="price">$12.99</span>
                        <span class="rating">★★★★★</span>
                    </div>
                    <a href="#" class="menu-btn">Add To Cart</a>
                </div>
            </div>

            <!-- Menu Item 2 -->
            <div class="menu-card">
                <img src="assets/images/menu/menu-2.jpg" alt="Cheese Burger">
                <div class="menu-content">
                    <h3>Cheese Pasta</h3>
                    <p>Rich cheese sauce pasta with fresh vegetables.</p>
                    <div class="menu-info">
                        <span class="price">$13.49</span>
                        <span class="rating">★★★★★</span>
                    </div>
                    <a href="#" class="menu-btn">Add To Cart</a>
                </div>
            </div>

            <!-- Menu Item 3 -->
            <div class="menu-card">
                <img src="assets/images/menu/menu-3.jpg" alt="Creamy Pasta">
                <div class="menu-content">
                    <h3>Chocolate Shake</h3>
                    <p>Rich creamy chocolate shake topped with whipped cream.</p>
                    <div class="menu-info">
                        <span class="price">$10.99</span>
                        <span class="rating">★★★★★</span>
                    </div>
                    <a href="#" class="menu-btn">Add To Cart</a>
                </div>
            </div>

            <!-- Menu Item 4 -->
            <div class="menu-card">
                <img src="assets/images/menu/menu-4.jpg" alt="BBQ Wings">
                <div class="menu-content">
                    <h3>Chicken Pizza</h3>
                    <p>Fresh oven-baked pizza topped with spicy grilled chicken.</p>
                    <div class="menu-info">
                        <span class="price">$13.49</span>
                        <span class="rating">★★★★★</span>
                    </div>
                    <a href="#" class="menu-btn">Add To Cart</a>
                </div>
            </div>

            <!-- Menu Item 5 -->
            <div class="menu-card">
                <img src="assets/images/menu/menu-5.jpg" alt="Chicken Sandwich">
                <div class="menu-content">
                    <h3>Chicken Burger</h3>
                    <p> Juicy grilled chicken patty with melted cheese and fresh salad.</p>
                    <div class="menu-info">
                        <span class="price">$8.99</span>
                        <span class="rating">★★★★★</span>
                    </div>
                    <a href="#" class="menu-btn">Add To Cart</a>
                </div>
            </div>

            <!-- Menu Item 6 -->
            <div class="menu-card">
                <img src="assets/images/menu/menu-6.jpg" alt="French Fries">
                <div class="menu-content">
                    <h3>Grilled Steak</h3>
                    <p>Tender grilled steak served with fresh vegetables.</p>
                    <div class="menu-info">
                        <span class="price">$18.99</span>
                        <span class="rating">★★★★★</span>
                    </div>
                    <a href="#" class="menu-btn">Add To Cart</a>
                </div>
            </div>

            <!-- Menu Item 7 -->
            <div class="menu-card">
                <img src="assets/images/menu/menu-7.jpg" alt="Chicken Pizza">
                <div class="menu-content">
                    <h3>Signature Cupcake</h3>
                    <p>Chocolate cupcake with whipped cream topping.</p>
                    <div class="menu-info">
                        <span class="price">$4.99</span>
                        <span class="rating">★★★★★</span>
                    </div>
                    <a href="#" class="menu-btn">Add To Cart</a>
                </div>
            </div>

            <!-- Menu Item 8 -->
            <div class="menu-card">
                <img src="assets/images/menu/menu-8.jpg" alt="Beef Steak">
                <div class="menu-content">
                    <h3>Shrimp Spaghetti</h3>
                    <p>Delicious shrimp pasta with a rich tomato-based sauce.</p>
                    <div class="menu-info">
                        <span class="price">$14.99</span>
                        <span class="rating">★★★★★</span>
                    </div>
                    <a href="#" class="menu-btn">Add To Cart</a>
                </div>
            </div>

            <!-- Menu Item 9 -->
            <div class="menu-card">
                <img src="assets/images/menu/menu-9.jpg" alt="Cold Drink">
                <div class="menu-content">
                    <h3>Cheese Pizza</h3>
                    <p>Classic pizza topped with melted mozzarella cheese.</p>
                    <div class="menu-info">
                        <span class="price">$14.99</span>
                        <span class="rating">★★★★★</span>
                    </div>
                    <a href="#" class="menu-btn">Add To Cart</a>
                </div>
            </div>

            <!-- Menu Item 10 -->
            <div class="menu-card">
                <img src="assets/images/menu/menu-10.jpg" alt="Chocolate Cake">
                <div class="menu-content">
                    <h3>Meg Burger & Fries</h3>
                    <p> Juicy beef burger with crispy fries.</p>
                    <div class="menu-info">
                        <span class="price">$12.99</span>
                        <span class="rating">★★★★★</span>
                    </div>
                    <a href="#" class="menu-btn">Add To Cart</a>
                </div>
            </div>

            <!-- Menu Item 11 -->
            <div class="menu-card">
                <img src="assets/images/menu/menu-11.jpg" alt="Ice Cream">
                <div class="menu-content">
                    <h3>Fresh Drinks</h3>
                    <p>Refreshing beverages to quench your thirst.</p>
                    <div class="menu-info">
                        <span class="price">$5.49</span>
                        <span class="rating">★★★★★</span>
                    </div>
                    <a href="#" class="menu-btn">Add To Cart</a>
                </div>
            </div>

            <!-- Menu Item 12 -->
            <div class="menu-card">
                <img src="assets/images/menu/menu-12.jpg" alt="Special Combo">
                <div class="menu-content">
                    <h3>Special Grilled Tikka</h3>
                    <p> Juicy grilled chicken tikka with crispy fries.</p>
                    <div class="menu-info">
                        <span class="price">$12.99</span>
                        <span class="rating">★★★★★</span>
                    </div>
                    <a href="#" class="menu-btn">Add To Cart</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Menu Section End -->

<!-- Offer Banner Section Start -->

<section class="offer-banner">
    <div class="container">
        <div class="offer-content">
            <h5>LIMITED TIME OFFER</h5>
            <h2>Get 30% OFF On Your Favorite Burger</h2>
            <p>Don't miss this delicious deal! Order now and enjoy fresh,hot burgers with an exclusive discount for a limited time.</p>
            <a href="#" class="offer-btn">Order Now</a>
        </div>
    </div>
</section>

<!-- Offer Banner Section End -->

<!-- Why Choose Us Section Start -->

<section class="why-choose-us">
    <div class="container">
        <div class="section-heading">
            <h5>WHY CHOOSE US</h5>
            <h2>Why People Love Urban Bites</h2>
            <p>We are committed to serving fresh, delicious food with excellent customer service and fast delivery.</p>
        </div>
        <div class="choose-grid">
            <!-- Card 1 -->
            <div class="choose-card">
                <div class="choose-icon">🍔</div>
                <h3>Fresh Ingredients</h3>
                <p>Every meal is prepared using fresh and high-quality ingredients.</p>
            </div>

            <!-- Card 2 -->
            <div class="choose-card">
                <div class="choose-icon">🚚</div>
                <h3>Fast Delivery</h3>
                <p>We deliver your favorite meals quickly while they are still hot.</p>
            </div>

            <!-- Card 3 -->
            <div class="choose-card">
                <div class="choose-icon">⭐</div>
                <h3>Best Quality</h3>
                <p>Our chefs prepare every dish with care to ensure the best taste.</p>
            </div>

            <!-- Card 4 -->
            <div class="choose-card">
                <div class="choose-icon">💳</div>
                <h3>Secure Payment</h3>
                <p>Safe and secure payment methods for a smooth ordering experience.</p>
            </div>

        </div>
    </div>
</section>

<!-- Why Choose Us Section End -->

<!-- Testimonials Section Starts-->

<section class="testimonials">
    <div class="container">
        <div class="section-title">
            <span>TESTIMONIALS</span>
            <h2>What Our Customers Say</h2>
        </div>
        <div class="testimonial-grid">

            <!-- Customer 1 -->
            <div class="testimonial-card">
                <img src="assets/images/testimonials/customer-1.jpg" alt="Customer">
                <h3>Ali Khan</h3>
                <div class="stars">⭐⭐⭐⭐⭐</div>
                <p>The pizza was incredibly delicious and delivered hot.Urban Bites has become my favorite place to order food.</p>
            </div>

            <!-- Customer 2 -->
            <div class="testimonial-card">
                <img src="assets/images/testimonials/customer-2.jpg" alt="Customer">
                <h3>Sarah Ahmed</h3>
                <div class="stars">⭐⭐⭐⭐⭐</div>
                <p>Amazing customer service and excellent food quality.Everything arrived fresh and perfectly packed.</p>
            </div>

            <!-- Customer 3 -->
            <div class="testimonial-card">
                <img src="assets/images/testimonials/customer-3.jpg" alt="Customer">
                <h3>Usman Malik</h3>
                <div class="stars">⭐⭐⭐⭐⭐</div>
                <p>Fast delivery, great taste and affordable prices.I highly recommend Urban Bites to everyone.</p>
            </div>

        </div>
    </div>
</section> 
<!-- Testimonials Section Ends -->
<?php

include "includes/footer.php";

?>