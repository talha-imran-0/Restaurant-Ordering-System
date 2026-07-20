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

<?php

include "includes/footer.php";

?>