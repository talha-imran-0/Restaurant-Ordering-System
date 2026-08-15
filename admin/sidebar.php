<?php
require_once "../php/Functions.php";
?>

<!-- MOBILE HEADER  -->
<div class="mobile-header">
    <button id="menu-btn" type="button" aria-label="Open menu" tabindex="0">☰</button>
    <h2>Urban Bites</h2>
</div>

<!-- OVERLAY -->
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <h2>Urban Bites</h2>
    <ul>
        <li><a href="dashboard.php">Dashboard</a></li>
        <li><a href="categories.php">Categories</a></li>
        <li><a href="menu.php">Menu</a></li>
        <li><a href="orders.php">Orders</a></li>
        <li><a href="customers.php">Customers</a></li>
        <li><a href="contact_messages.php">Messages</a></li>
        <li><a href="reports.php">Reports</a></li>
        <li>
            <form method="POST" action="logout.php">
                <?php csrf_input(); ?>
                <button type="submit" class="admin-logout-btn" ><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
            </form>
        </li>
    </ul>
</div>
