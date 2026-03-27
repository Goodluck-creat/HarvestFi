<?php
// ===== CXV FOOTER CONFIG =====
$cxv_site_name = "Harvestfi";
$cxv_year = date("Y");
$cxv_email = "info@harvestfi.com.ng";
$cxv_phone = "+2349031795616";
$cxv_address = "Awka Anambra State";
?>

<!-- ===== CXV FOOTER ROOT ===== -->
<div id="cxv_fmf_footer_root">

    <!-- ===== MAIN FOOTER ===== -->
    <div class="cxv_fmf_footer_main container-fluid bg-primary text-white mt-5">
        <div class="container">
            <div class="row gx-5">

                <!-- LEFT SIDE -->
                <div class="col-lg-8 col-md-6">
                    <div class="row gx-5">

                        <!-- CONTACT -->
                        <div class="col-lg-4 col-md-12 pt-5 mb-5">
                            <h4 class="text-white mb-4">Get In Touch</h4>

                            <div class="d-flex mb-2">
                                <i class="bi bi-geo-alt me-2"></i>
                                <p class="mb-0"><?php echo $cxv_address; ?></p>
                            </div>

                            <div class="d-flex mb-2">
                                <i class="bi bi-envelope-open me-2"></i>
                                <p class="mb-0"><?php echo $cxv_email; ?></p>
                            </div>

                            <div class="d-flex mb-2">
                                <i class="bi bi-telephone me-2"></i>
                                <p class="mb-0"><?php echo $cxv_phone; ?></p>
                            </div>

                            <div class="d-flex mt-4">
                                <a class="btn btn-secondary btn-square rounded-circle me-2" href="#"><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-secondary btn-square rounded-circle me-2" href="#"><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-secondary btn-square rounded-circle me-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                                <a class="btn btn-secondary btn-square rounded-circle" href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>

                        <!-- QUICK LINKS -->
                        <div class="col-lg-4 col-md-12 pt-lg-5 mb-5">
                            <h4 class="text-white mb-4">Quick Links</h4>

                            <div class="d-flex flex-column">
                                <a class="text-white mb-2" href="index.php"><i class="bi bi-arrow-right me-2"></i>Home</a>
                                <a class="text-white mb-2" href="about.php"><i class="bi bi-arrow-right me-2"></i>About</a>
                                <a class="text-white mb-2" href="service.php"><i class="bi bi-arrow-right me-2"></i>Services</a>
                                <a class="text-white mb-2" href="team.php"><i class="bi bi-arrow-right me-2"></i>Team</a>
                                <a class="text-white mb-2" href="blog.php"><i class="bi bi-arrow-right me-2"></i>Blog</a>
                                <a class="text-white" href="contact.php"><i class="bi bi-arrow-right me-2"></i>Contact</a>
                            </div>
                        </div>

                        <!-- POPULAR LINKS -->
                        <div class="col-lg-4 col-md-12 pt-lg-5 mb-5">
                            <h4 class="text-white mb-4">Popular Links</h4>

                            <div class="d-flex flex-column">
                                <a class="text-white mb-2" href="index.php"><i class="bi bi-arrow-right me-2"></i>Home</a>
                                <a class="text-white mb-2" href="about.php"><i class="bi bi-arrow-right me-2"></i>About</a>
                                <a class="text-white mb-2" href="service.php"><i class="bi bi-arrow-right me-2"></i>Services</a>
                                <a class="text-white mb-2" href="team.php"><i class="bi bi-arrow-right me-2"></i>Team</a>
                                <a class="text-white mb-2" href="blog.php"><i class="bi bi-arrow-right me-2"></i>Blog</a>
                                <a class="text-white" href="contact.php"><i class="bi bi-arrow-right me-2"></i>Contact</a>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- NEWSLETTER -->
                <div class="col-lg-4 col-md-6 mt-lg-n5">
                    <div class="cxv_fmf_news_box d-flex flex-column align-items-center justify-content-center text-center h-100 bg-secondary p-5">
                        <h4 class="text-white">Newsletter</h4>
                        <h6 class="text-white">Subscribe Our Newsletter</h6>
                        <p>Stay updated with our latest content</p>

                        <form method="post" action="#">
                            <div class="input-group">
                                <input type="email" name="email" class="form-control border-white p-3" placeholder="Your Email" required>
                                <button class="btn btn-primary">Sign Up</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- ===== BOTTOM BAR ===== -->
    <div class="cxv_fmf_footer_bottom container-fluid bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">
                &copy; <?php echo $cxv_year; ?> 
                <span class="text-secondary fw-bold"><?php echo $cxv_site_name; ?></span>. 
                All Rights Reserved.
            </p>
        </div>
    </div>

</div>
<!-- ===== END FOOTER ===== -->

<!-- ===== CXV FOOTER STYLES ===== -->
<style>
#cxv_fmf_footer_root .cxv_fmf_footer_main {
    position: relative;
}

#cxv_fmf_footer_root .cxv_fmf_news_box {
    border-radius: 10px;
}

#cxv_fmf_footer_root a {
    text-decoration: none;
    transition: 0.3s ease;
}

#cxv_fmf_footer_root a:hover {
    color: #ffc107 !important;
    padding-left: 5px;
}
</style>