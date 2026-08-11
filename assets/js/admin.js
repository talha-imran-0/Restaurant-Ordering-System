document.addEventListener("DOMContentLoaded", function () {
    var menuBtn = document.getElementById("menu-btn");
    var sidebar = document.getElementById("sidebar");
    var overlay = document.getElementById("sidebar-overlay");

    function closeSidebar() {
        if (sidebar) {
            sidebar.classList.remove("active");
        }
        if (overlay) {
            overlay.classList.remove("active");
        }
        document.body.classList.remove("sidebar-open");
    }

    function openSidebar() {
        if (sidebar) {
            sidebar.classList.add("active");
        }
        if (overlay) {
            overlay.classList.add("active");
        }
        document.body.classList.add("sidebar-open");
    }

    if (menuBtn) {
        menuBtn.addEventListener("click", function () {
            if (sidebar && sidebar.classList.contains("active")) {
                closeSidebar();
            } else {
                openSidebar();
            }
        });
    } else {
        // Fallback: delegate click events in case the button isn't present when script runs
        document.addEventListener("click", function (e) {
            if (e.target && e.target.closest && e.target.closest("#menu-btn")) {
                if (sidebar && sidebar.classList.contains("active")) {
                    closeSidebar();
                } else {
                    openSidebar();
                }
            }
        });
    }

    if (overlay) {
        overlay.addEventListener("click", closeSidebar);
    }

    document.querySelectorAll(".sidebar a").forEach(function (link) {
        link.addEventListener("click", function () {
            if (window.innerWidth <= 992) {
                closeSidebar();
            }
        });
    });

    window.addEventListener("resize", function () {
        if (window.innerWidth > 992) {
            closeSidebar();
        }
    });
});