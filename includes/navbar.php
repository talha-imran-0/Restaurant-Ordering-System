<nav class="navbar">
    <div class="container">
        <div class="navbar-content">

            <!-- Logo -->
            <a href="index.php" class="logo">
                <img src="assets/uploads/logo/logo-main.png" alt="Urban Bites Logo">
            </a>

            <!-- Hamburger -->
            <div class="menu-toggle" id="menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <!-- Desktop Menu -->
            <ul class="nav-menu">
                <li><a href="index.php">Home</a></li>
                <li><a href="index.php#featured-menu">Menu</a></li>
                <li><a href="index.php#categories">Categories</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>

                <?php if (isset($_SESSION["user_id"])) { ?>

                    <li class="user-dropdown">
                        <a href="#">
                            👤 <?php echo htmlspecialchars(explode(" ", $_SESSION["user_name"])[0]); ?>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a href="customer/profile.php">Profile</a></li>
                            <li><a href="customer/order_tracking.php">My Orders</a></li>
                            <li><a href="customer/logout.php">Logout</a></li>
                        </ul>
                    </li>

                <?php } else { ?>

                    <li><a href="customer/login.php" class="login-btn">Login</a></li>

                <?php } ?>

            </ul>

        </div>
    </div>
</nav>

<div class="menu-overlay" id="menu-overlay"></div>

<!-- Mobile Sidebar -->
<div class="mobile-sidebar" id="mobile-sidebar">
    <div class="sidebar-header">
        <img src="assets/uploads/logo/logo-main.png" alt="Urban Bites Logo">
        <span id="close-menu">&times;</span>
    </div>

    <ul class="sidebar-menu">
        <li><a href="index.php">Home</a></li>
        <li><a href="index.php#featured-menu">Menu</a></li>
        <li><a href="index.php#categories">Categories</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>

        <?php if (isset($_SESSION["user_id"])) { ?>

            <li><a href="customer/profile.php"><?php echo htmlspecialchars($_SESSION["user_name"]); ?></a></li>
            <li><a href="customer/order_tracking.php">My Orders</a></li>
            <li><a href="customer/logout.php" class="sidebar-login-btn">Logout</a></li>

        <?php } else { ?>

            <li><a href="customer/login.php" class="sidebar-login-btn">Login</a></li>

        <?php } ?>

    </ul>
</div>