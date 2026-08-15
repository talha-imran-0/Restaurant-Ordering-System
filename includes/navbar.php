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
                <li>
                    <a href="index.php">Home</a>
                </li>
                <li>
                    <a href="index.php#featured-menu">Menu</a>
                </li>
                <li>
                    <a href="index.php#categories">Categories</a>
                </li>
                <li>
                    <a href="about.php">About</a>
                </li>
                <li>
                    <a href="contact.php">Contact</a>
                </li>

                <?php if (isset($_SESSION["admin_id"])) { ?>

                    <!-- ADMIN DESKTOP -->
                    <li class="user-dropdown">
                        <a href="#">
                            <i class="fa-solid fa-user-shield"></i>
                            <span><?php echo htmlspecialchars($_SESSION["admin_name"]); ?></span>
                            <i class="fa-solid fa-chevron-down dropdown-arrow"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a href="admin/dashboard.php">
                                    <i class="fa-solid fa-gauge"></i>
                                    Dashboard
                                </a>
                            </li>

                            <!-- ADMIN LOGOUT -->
                            <li>
                                <form method="POST" action="customer/logout.php" style="margin:0;">
                                    <?php csrf_input(); ?>
                                    <button type="submit" class="navbar-logout-btn">
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                <?php } elseif (isset($_SESSION["user_id"])) { ?>

                    <!-- CUSTOMER DESKTOP -->
                    <li class="user-dropdown">
                        <a href="#">
                            <i class="fa-solid fa-circle-user"></i>
                            <span><?php echo htmlspecialchars(explode(" ", $_SESSION["user_name"])[0]); ?></span>
                            <i class="fa-solid fa-chevron-down dropdown-arrow"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a href="customer/profile.php">
                                    <i class="fa-solid fa-user"></i>
                                    My Profile
                                </a>
                            </li>
                            <li>
                                <a href="customer/order_tracking.php">
                                    <i class="fa-solid fa-bag-shopping"></i>
                                    My Orders
                                </a>
                            </li>

                            <!-- CUSTOMER LOGOUT -->
                            <li>
                                <form method="POST" action="admin/logout.php" style="display:inline;">
                                    <?php csrf_input(); ?>
                                    <button type="submit" class="navbar-logout-btn"  name="logout">
                                        <i class="fa-solid fa-right-from-bracket"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>

                <?php } else { ?>

                    <!-- LOGIN -->
                    <li>
                        <a href="customer/login.php" class="login-btn">
                            Login
                        </a>
                    </li>

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

        <?php if (isset($_SESSION["admin_id"])) { ?>

            <!-- ADMIN MOBILE -->
            <li>
                <a href="admin/dashboard.php">
                    <i class="fa-solid fa-user-shield"></i>
                    <?php echo htmlspecialchars($_SESSION["admin_name"]); ?>
                </a>
            </li>

            <li>
                <a href="admin/dashboard.php">
                    <i class="fa-solid fa-gauge"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="admin/settings.php">
                    <i class="fa-solid fa-gear"></i>
                    Settings
                </a>
            </li>

            <!-- DIVIDER -->
            <li class="sidebar-divider"></li>

        <?php } elseif (isset($_SESSION["user_id"])) { ?>

            <!-- CUSTOMER MOBILE -->
            <li>
                <a href="customer/profile.php">
                    <i class="fa-solid fa-circle-user"></i>
                    <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
                </a>
            </li>

            <li>
                <a href="customer/order_tracking.php">
                    <i class="fa-solid fa-bag-shopping"></i>
                    My Orders
                </a>
            </li>

            <!-- DIVIDER -->
            <li class="sidebar-divider"></li>

        <?php } ?>

        <!-- MAIN MENU -->
        <li>
            <a href="index.php">
                Home
            </a>
        </li>

        <li>
            <a href="index.php#featured-menu">
                Menu
            </a>
        </li>

        <li>
            <a href="index.php#categories">
                Categories
            </a>
        </li>

        <li>
            <a href="about.php">
                About
            </a>
        </li>

        <li>
            <a href="contact.php">
                Contact
            </a>
        </li>

        <?php if (isset($_SESSION["admin_id"])) { ?>

            <!-- ADMIN LOGOUT -->
            <li class="sidebar-logout">
                <form method="POST" action="admin/logout.php">
                    <?php csrf_input(); ?>

                    <button type="submit" class="navbar-logout-btn">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Logout
                    </button>
                </form>
            </li>

        <?php } elseif (isset($_SESSION["user_id"])) { ?>

            <!-- CUSTOMER LOGOUT -->
            <li class="sidebar-logout">
                <form method="POST" action="customer/logout.php">
                    <?php csrf_input(); ?>

                    <button type="submit" class="navbar-logout-btn" name="logout">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        Logout
                    </button>
                </form>
            </li>

        <?php } else { ?>

            <!-- MOBILE LOGIN -->
            <li>
                <a href="customer/login.php" class="sidebar-login-btn">
                    Login
                </a>
            </li>

        <?php } ?>

    </ul>
</div>
</div>