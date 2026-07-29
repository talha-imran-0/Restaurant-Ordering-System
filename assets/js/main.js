// ==========================
// Mobile Sidebar
// ==========================

var menuToggle = document.getElementById("menu-toggle");
var mobileSidebar = document.getElementById("mobile-sidebar");
var menuOverlay = document.getElementById("menu-overlay");
var closeMenu = document.getElementById("close-menu");

menuToggle.addEventListener("click", function () {

    mobileSidebar.classList.add("active");
    menuOverlay.classList.add("active");
    document.body.style.overflow = "hidden";

});

closeMenu.addEventListener("click", function () {

    mobileSidebar.classList.remove("active");
    menuOverlay.classList.remove("active");
    document.body.style.overflow = "auto";

});

menuOverlay.addEventListener("click", function () {

    mobileSidebar.classList.remove("active");
    menuOverlay.classList.remove("active");
    document.body.style.overflow = "auto";

});

// Close menu when a link is clicked

var sidebarLinks = document.querySelectorAll(".sidebar-menu a");

sidebarLinks.forEach(function (link) {

    link.addEventListener("click", function () {

        mobileSidebar.classList.remove("active");
        menuOverlay.classList.remove("active");
        document.body.style.overflow = "auto";

    });

});


/*=========================================
        FOOD DETAILS START
=========================================*/

var minusButton = document.getElementById("minus-btn");
var plusButton = document.getElementById("plus-btn");
var quantityInput = document.getElementById("quantity");

if (minusButton && plusButton && quantityInput) {

    minusButton.addEventListener("click", function () {

        var quantity = parseInt(quantityInput.value);

        if (quantity > 1) {

            quantity--;

            quantityInput.value = quantity;

        }

    });

    plusButton.addEventListener("click", function () {

        var quantity = parseInt(quantityInput.value);

        quantity++;

        quantityInput.value = quantity;

    });

}

var addToCartButton = document.querySelector(".btn-primary");

if (addToCartButton) {

    addToCartButton.addEventListener("click", function (event) {

        event.preventDefault();

        alert("Item added to cart successfully.");

    });

}

/*=========================================
        FOOD DETAILS END
=========================================*/