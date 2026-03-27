<?php
// ===== CXV CONFIG =====
$cxv_site_name = "HarvestFi";
$cxv_phone = "+2349031795616";

// detect current page
$cxv_current = basename($_SERVER['PHP_SELF']);
?>

<!-- ===== CXV HEADER ROOT ===== -->
<div id="cxv_fmf_header_root">

    <!-- ===== TOPBAR ===== -->
    <div class="cxv_fmf_topbar container-fluid px-5 d-none d-lg-block">
        <div class="row gx-5 py-3 align-items-center">

            <!-- Phone -->
            <div class="col-lg-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-phone-vibrate fs-1 text-primary me-2"></i>
                    <h2 class="mb-0"><?php echo $cxv_phone; ?></h2>
                </div>
            </div>

            <!-- Logo -->
            <div class="col-lg-6 text-center">
                <a href="" class="navbar-brand ms-4 ms-lg-0">
    <h1 class="m-0 display-4 text-primary">
        <img src="img/logo.png" alt="HarvestFi Logo" style="height: 60px; width: auto;">
    </h1>
</a>
            </div>

            <!-- Social -->
            <div class="col-lg-3">
                <div class="d-flex justify-content-end">
                    <a class="btn btn-primary btn-square rounded-circle me-2" href="#"><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-primary btn-square rounded-circle me-2" href="#"><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-primary btn-square rounded-circle me-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a class="btn btn-primary btn-square rounded-circle" href="#"><i class="fab fa-instagram"></i></a>
                </div>
            </div>

        </div>
    </div>

    <!-- ===== NAVBAR ===== -->
    <nav class="cxv_fmf_navbar cxv_fmf_navbar_scroll navbar navbar-expand-lg bg-primary navbar-dark py-3 px-3 px-lg-5">

        <!-- Mobile Logo -->
        <a href="index.php" class="navbar-brand d-lg-none">
            <h1 class="m-0 display-4 text-secondary">
                <span class="text-white">Harvest</span>Fi
            </h1>
        </a>

        <!-- Toggle -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#cxv_fmf_nav_collapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
        <div class="collapse navbar-collapse" id="cxv_fmf_nav_collapse">
            <div class="navbar-nav mx-auto">

                <a href="index.php" class="nav-link <?php echo ($cxv_current == 'index.php') ? 'active' : ''; ?>">Home</a>

                <a href="about.php" class="nav-link <?php echo ($cxv_current == 'about.php') ? 'active' : ''; ?>">About</a>

                <a href="service.php" class="nav-link">Service</a>
                <a href="product.php" class="nav-link">Product</a>
                <a href="farmer/login.php" class="nav-link">Login</a>
                 <a href="farmer/register.php" class="nav-link">Register</a>


               

                <a href="contact.php" class="nav-link <?php echo ($cxv_current == 'contact.php') ? 'active' : ''; ?>">Contact</a>

            </div>
        </div>

    </nav>

</div>
<!-- ===== END HEADER ===== -->

<!-- ===== CXV HEADER STYLES ===== -->
<style>
#cxv_fmf_header_root .cxv_fmf_navbar_scroll {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    transition: transform 0.4s ease, box-shadow 0.3s ease;
    z-index: 9999;
}

#cxv_fmf_header_root .cxv_fmf_navbar_hidden {
    transform: translateY(-100%);
}

#cxv_fmf_header_root .cxv_fmf_navbar_visible {
    transform: translateY(0);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

/* prevent content jump */
body {
    padding-top: 90px;
}
</style>

<!-- ===== CXV SCROLL SCRIPT ===== -->
<script>
(function() {
    let lastScrollTop = 0;
    const navbar = document.querySelector(".cxv_fmf_navbar_scroll");

    window.addEventListener("scroll", function() {
        let currentScroll = window.pageYOffset || document.documentElement.scrollTop;

        if (currentScroll > lastScrollTop) {
            // scrolling down
            navbar.classList.add("cxv_fmf_navbar_hidden");
            navbar.classList.remove("cxv_fmf_navbar_visible");
        } else {
            // scrolling up
            navbar.classList.remove("cxv_fmf_navbar_hidden");
            navbar.classList.add("cxv_fmf_navbar_visible");
        }

        lastScrollTop = currentScroll <= 0 ? 0 : currentScroll;
    });
})();
</script>
