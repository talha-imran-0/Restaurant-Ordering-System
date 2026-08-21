<?php

require_once "includes/config.php";
require_once "php/Functions.php";

include "includes/header.php";
include "includes/navbar.php";

// Get active categories from database 
$get_categories = mysqli_query($conn, "SELECT * FROM categories WHERE status = 1 ORDER BY id ASC");
// Get active menu items from database
$get_menu_items = mysqli_query($conn, "SELECT * FROM menu_items WHERE status = 1 ORDER BY id ASC LIMIT 12");

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
                <a href="customer/menu.php?category=1" class="btn"> Order Now </a>
                <a href="customer/menu.php?category=1" class="btn btn-outline"> View Menu </a>
            </div>
            <div class="hero-image">
                <img src="assets/images/hero/hero-pizza.png" alt="Hero Pizza">
            </div>
        </div>
    </div>
</section>

<!-- Hero Section Ends -->

<!-- Categories Section Start -->

<section class="categories" id="categories">
    <div class="container">
        <div class="section-heading">
            <h5>OUR CATEGORIES</h5>
            <h2>Explore Our Menu Categories</h2>
            <p>Choose your favorite food category and enjoy delicious meals prepared with fresh ingredients.</p>
        </div>
        <div class="categories-grid">

            <?php while ($category = mysqli_fetch_assoc($get_categories)) { ?>

                    <a href="customer/menu.php?category=<?php echo $category['id']; ?>" class="category-card">

                        <img
                            src="assets/uploads/categories/<?php echo htmlspecialchars($category['image']); ?>"
                            alt="<?php echo htmlspecialchars($category['name']); ?>">

                        <h3>

                            <?php echo htmlspecialchars($category['name']); ?>

                        </h3>

                    </a>
                
            <?php } ?>

        </div>
    </div>
</section>

<!-- Categories Section End  -->

<!-- Featured Menu Section Start -->

<section class="featured-menu" id="featured-menu">
    <div class="container">
        <div class="section-heading">
            <h5>FEATURED MENU</h5>
            <h2>Our Popular Dishes</h2>
            <p>Discover our most loved dishes made with fresh ingredients, rich flavors, and served with passion.</p>
        </div>

        <!-- Menu Grid -->
        <div class="menu-grid">
            <?php while ($menu = mysqli_fetch_assoc($get_menu_items)) { ?>

                <div class="menu-card">
                    <img
                        src="assets/uploads/menu/<?php echo htmlspecialchars($menu['image']); ?>"
                        alt="<?php echo htmlspecialchars($menu['name']); ?>"
                    >
                    <div class="menu-content">
                        <h3><?php echo htmlspecialchars($menu['name']); ?></h3>
                        <p><?php echo htmlspecialchars($menu['description']); ?></p>
                        <div class="menu-info">
                            <span class="price">$<?php echo number_format($menu['price'], 2); ?></span>
                            <span class="rating">★★★★★</span>
                        </div>
                        <a href="customer/food-details.php?id=<?php echo $menu['id']; ?>" class="menu-btn"> View Details </a>
                    </div>
                </div>

            <?php } ?>
        </div>

    </div>
</section>

<!-- Featured Menu Section End -->


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

<!-- Footer Section Starts -->

<footer class="footer">
    <div class="container">
        <div class="footer-content">

            <div class="footer-box">
                <img src="assets/uploads/logo/logo-main.png" alt="Urban Bites Logo" class="footer-logo">
                <p>Urban Bites serves fresh, delicious meals made with quality ingredients.Enjoy fast delivery and an unforgettable dining experience from the comfortof your home.</p>
            </div>

            <!-- Quick Links -->
            <div class="footer-box">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#featured-menu">Menu</a></li>
                    <li><a href="#categories">Categories</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="footer-box">
                <h3>Contact</h3>
                <p><i class="fa-solid fa-location-dot"></i>Gujranwala, Pakistan</p>
                <p><i class="fa-solid fa-phone"></i>+92 300 6489664</p>
                <p><i class="fa-solid fa-envelope"></i>talhaimran.only@gmail.com</p>
            </div>

            <!-- Socails  -->
            <div class="footer-box">
                <h3>Follow Us</h3>
                <div class="footer-social">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i>Facebook</a>
                    <a href="#"><i class="fa-brands fa-instagram"></i>Instagram</a>
                    <a href="#"><i class="fa-brands fa-x-twitter"></i>Twitter</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© <?php echo date("Y"); ?> Urban Bites. All Rights Reserved.</p>
        </div>
    </div>
</footer>
<!-- Footer Section Ends -->

<?php

include "includes/footer.php";

?>