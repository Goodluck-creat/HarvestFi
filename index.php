<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>FarmFresh - Organic Farm Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Roboto:wght@500;700&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
  <?php include "header.php";?>
    <!-- Navbar End -->


    <!-- Carousel Start -->
    <div class="container-fluid p-0">
        <div id="header-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img class="w-100" src="img/carousel-1.jpg" alt="Image">
                    <div class="carousel-caption top-0 bottom-0 start-0 end-0 d-flex flex-column align-items-center justify-content-center">
                        <div class="text-start p-5" style="max-width: 900px;">
                            <h3 class="text-white">Organic Vegetables</h3>
                            <h1 class="display-1 text-white mb-md-4">Organic Vegetables For Healthy Life</h1>
                            <a href="" class="btn btn-primary py-md-3 px-md-5 me-3">Explore</a>
                            <a href="" class="btn btn-secondary py-md-3 px-md-5">Contact</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img class="w-100" src="img/carousel-2.jpg" alt="Image">
                    <div class="carousel-caption top-0 bottom-0 start-0 end-0 d-flex flex-column align-items-center justify-content-center">
                        <div class="text-start p-5" style="max-width: 900px;">
                            <h3 class="text-white">Organic Fruits</h3>
                            <h1 class="display-1 text-white mb-md-4">Organic Fruits For Better Health</h1>
                            <a href="" class="btn btn-primary py-md-3 px-md-5 me-3">Explore</a>
                            <a href="" class="btn btn-secondary py-md-3 px-md-5">Contact</a>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#header-carousel"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </div>
    <!-- Carousel End -->


    <!-- Banner Start -->
    <div class="container-fluid banner mb-5">
    <div class="container">
        <div class="row gx-0">

            <!-- FARMER SIDE -->
            <div class="col-md-6">
                <div class="bg-primary d-flex flex-column justify-content-center p-5" style="height: 300px;">
                    <h3 class="text-white mb-3">Access Farm Funding</h3>
                    <p class="text-white">
                        Get input loans to grow your crops without stress. With HarvestFi, repayment happens automatically when your produce is sold.
                    </p>
                    <a class="text-white fw-bold" href="farmer/register.php">
                        Get Started<i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

            <!-- BUYER SIDE -->
            <div class="col-md-6">
                <div class="bg-secondary d-flex flex-column justify-content-center p-5" style="height: 300px;">
                    <h3 class="text-white mb-3">Buy Fresh Farm Produce</h3>
                    <p class="text-white">
                        Purchase verified farm produce directly from farmers. Secure payments powered by Interswitch ensure safe and transparent transactions.
                    </p>
                    <a class="text-white fw-bold" href="buyer/marketplace.php">
                        Shop Now<i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
    <!-- Banner Start -->


    <!-- About Start -->
   <div class="container-fluid about pt-5">
    <div class="container">
        <div class="row gx-5">

            <!-- IMAGE SIDE -->
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="d-flex h-100 border border-5 border-primary border-bottom-0 pt-4">
                    <img class="img-fluid mt-auto mx-auto" src="img/about.png" alt="HarvestFi Platform">
                </div>
            </div>

            <!-- TEXT SIDE -->
            <div class="col-lg-6 pb-5">
                <div class="mb-3 pb-2">
                    <h6 class="text-primary text-uppercase">About HarvestFi</h6>
                    <h1 class="display-5">Empowering Farmers Through Smart Financing</h1>
                </div>

                <p class="mb-4">
                    HarvestFi is a fintech-powered agricultural platform that helps farmers access funding and repay loans seamlessly through their harvest sales. By connecting farmers directly to buyers and integrating secure payments, we eliminate traditional barriers to agricultural financing.
                </p>

                <div class="row gx-5 gy-4">

                    <!-- FEATURE 1 -->
                    <div class="col-sm-6">
                        <i class="fa fa-coins display-1 text-secondary"></i>
                        <h4>Flexible Farm Funding</h4>
                        <p class="mb-0">
                            Farmers receive input loans and repay only when they earn, reducing financial pressure and increasing productivity.
                        </p>
                    </div>

                    <!-- FEATURE 2 -->
                    <div class="col-sm-6">
                        <i class="fa fa-exchange-alt display-1 text-secondary"></i>
                        <h4>Secure Payments</h4>
                        <p class="mb-0">
                            Buyers pay through a trusted system, ensuring transparent transactions and automatic loan repayment.
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
    <!-- About End -->


    
    

    <!-- Services Start -->
   <div class="container-fluid py-5">
    <div class="container">
        <div class="row g-5">

            <!-- HEADER -->
            <div class="col-lg-4 col-md-6">
                <div class="mb-3">
                    <h6 class="text-primary text-uppercase">Our Services</h6>
                    <h1 class="display-5">Smart Agricultural Financing</h1>
                </div>
                <p class="mb-4">
                    HarvestFi provides farmers with access to funding, connects them to buyers, and ensures seamless repayment through secure and automated financial systems.
                </p>
                <a href="#" class="btn btn-primary py-md-3 px-md-5">Get Started</a>
            </div>

            <!-- SERVICE 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="service-item bg-light text-center p-5">
                    <i class="fa fa-coins display-1 text-primary mb-3"></i>
                    <h4>Farm Input Loans</h4>
                    <p class="mb-0">
                        Access flexible funding for seeds, fertilizers, and farm inputs with repayment tied to your harvest cycle.
                    </p>
                </div>
            </div>

            <!-- SERVICE 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="service-item bg-light text-center p-5">
                    <i class="fa fa-store display-1 text-primary mb-3"></i>
                    <h4>Produce Marketplace</h4>
                    <p class="mb-0">
                        List and sell your farm produce directly to verified buyers through a structured and transparent marketplace.
                    </p>
                </div>
            </div>

            <!-- SERVICE 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="service-item bg-light text-center p-5">
                    <i class="fa fa-exchange-alt display-1 text-primary mb-3"></i>
                    <h4>Automated Repayment</h4>
                    <p class="mb-0">
                        Loans are repaid automatically when buyers pay, removing the stress of manual repayments.
                    </p>
                </div>
            </div>

            <!-- SERVICE 4 -->
            <div class="col-lg-4 col-md-6">
                <div class="service-item bg-light text-center p-5">
                    <i class="fa fa-credit-card display-1 text-primary mb-3"></i>
                    <h4>Secure Payments</h4>
                    <p class="mb-0">
                        Buyers make secure payments through integrated payment systems, ensuring transparency and trust.
                    </p>
                </div>
            </div>

            <!-- SERVICE 5 -->
            <div class="col-lg-4 col-md-6">
                <div class="service-item bg-light text-center p-5">
                    <i class="fa fa-chart-line display-1 text-primary mb-3"></i>
                    <h4>Farmer Credit Scoring</h4>
                    <p class="mb-0">
                        Build a digital financial identity based on repayment history and performance to unlock better funding.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
    <!-- Services End -->


    <!-- Features Start -->
   <div class="container-fluid bg-primary feature py-5 pb-lg-0 my-5">
    <div class="container py-5 pb-lg-0">
        <div class="mx-auto text-center mb-3 pb-2" style="max-width: 500px;">
            <h6 class="text-uppercase text-secondary">Features</h6>
            <h1 class="display-5 text-white">Why Choose HarvestFi</h1>
        </div>

        <div class="row g-5">

            <!-- LEFT FEATURES -->
            <div class="col-lg-3">
                <div class="text-white mb-5">
                    <div class="bg-secondary rounded-pill d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fa fa-coins fs-4 text-white"></i>
                    </div>
                    <h4 class="text-white">Flexible Financing</h4>
                    <p class="mb-0">
                        Access farm funding with repayment tied to your harvest, not fixed monthly schedules.
                    </p>
                </div>

                <div class="text-white">
                    <div class="bg-secondary rounded-pill d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fa fa-chart-line fs-4 text-white"></i>
                    </div>
                    <h4 class="text-white">Credit Building</h4>
                    <p class="mb-0">
                        Build a digital financial identity through consistent repayments and successful harvest cycles.
                    </p>
                </div>
            </div>

            <!-- CENTER CONTENT -->
            <div class="col-lg-6">
                <div class="d-block bg-white h-100 text-center p-5 pb-lg-0">
                    <p>
                        HarvestFi combines financing, marketplace access, and secure payment systems into one platform. 
                        By integrating payments and automating loan repayment, we reduce risk while empowering farmers 
                        to grow sustainably and profitably.
                    </p>
                    <img class="img-fluid" src="img/feature.png" alt="HarvestFi Platform">
                </div>
            </div>

            <!-- RIGHT FEATURES -->
            <div class="col-lg-3">
                <div class="text-white mb-5">
                    <div class="bg-secondary rounded-pill d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fa fa-exchange-alt fs-4 text-white"></i>
                    </div>
                    <h4 class="text-white">Automated Repayment</h4>
                    <p class="mb-0">
                        Loans are automatically settled when buyers make payments, eliminating repayment stress.
                    </p>
                </div>

                <div class="text-white">
                    <div class="bg-secondary rounded-pill d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                        <i class="fa fa-shield-alt fs-4 text-white"></i>
                    </div>
                    <h4 class="text-white">Secure Transactions</h4>
                    <p class="mb-0">
                        All payments are processed securely, ensuring transparency and trust for both farmers and buyers.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
    <!-- Features Start -->


    <!-- Products Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="mx-auto text-center mb-5" style="max-width: 500px;">
                <h6 class="text-primary text-uppercase">Products</h6>
                <h1 class="display-5">Our Fresh & Organic Products</h1>
            </div>
            <div class="owl-carousel product-carousel px-5">
                <div class="pb-5">
                    <div class="product-item position-relative bg-white d-flex flex-column text-center">
                        <img class="img-fluid mb-4" src="img/product-1.png" alt="">
                        <h6 class="mb-3">Organic Vegetable</h6>
                        <h5 class="text-primary mb-0">$19.00</h5>
                        <div class="btn-action d-flex justify-content-center">
                            <a class="btn bg-primary py-2 px-3" href=""><i class="bi bi-cart text-white"></i></a>
                            <a class="btn bg-secondary py-2 px-3" href=""><i class="bi bi-eye text-white"></i></a>
                        </div>
                    </div>
                </div>
                <div class="pb-5">
                    <div class="product-item position-relative bg-white d-flex flex-column text-center">
                        <img class="img-fluid mb-4" src="img/product-2.png" alt="">
                        <h6 class="mb-3">Organic Vegetable</h6>
                        <h5 class="text-primary mb-0">$19.00</h5>
                        <div class="btn-action d-flex justify-content-center">
                            <a class="btn bg-primary py-2 px-3" href=""><i class="bi bi-cart text-white"></i></a>
                            <a class="btn bg-secondary py-2 px-3" href=""><i class="bi bi-eye text-white"></i></a>
                        </div>
                    </div>
                </div>
                <div class="pb-5">
                    <div class="product-item position-relative bg-white d-flex flex-column text-center">
                        <img class="img-fluid mb-4" src="img/product-1.png" alt="">
                        <h6 class="mb-3">Organic Vegetable</h6>
                        <h5 class="text-primary mb-0">$19.00</h5>
                        <div class="btn-action d-flex justify-content-center">
                            <a class="btn bg-primary py-2 px-3" href=""><i class="bi bi-cart text-white"></i></a>
                            <a class="btn bg-secondary py-2 px-3" href=""><i class="bi bi-eye text-white"></i></a>
                        </div>
                    </div>
                </div>
                <div class="pb-5">
                    <div class="product-item position-relative bg-white d-flex flex-column text-center">
                        <img class="img-fluid mb-4" src="img/product-2.png" alt="">
                        <h6 class="mb-3">Organic Vegetable</h6>
                        <h5 class="text-primary mb-0">$19.00</h5>
                        <div class="btn-action d-flex justify-content-center">
                            <a class="btn bg-primary py-2 px-3" href=""><i class="bi bi-cart text-white"></i></a>
                            <a class="btn bg-secondary py-2 px-3" href=""><i class="bi bi-eye text-white"></i></a>
                        </div>
                    </div>
                </div>
                <div class="pb-5">
                    <div class="product-item position-relative bg-white d-flex flex-column text-center">
                        <img class="img-fluid mb-4" src="img/product-1.png" alt="">
                        <h6 class="mb-3">Organic Vegetable</h6>
                        <h5 class="text-primary mb-0">$19.00</h5>
                        <div class="btn-action d-flex justify-content-center">
                            <a class="btn bg-primary py-2 px-3" href=""><i class="bi bi-cart text-white"></i></a>
                            <a class="btn bg-secondary py-2 px-3" href=""><i class="bi bi-eye text-white"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Products End -->


    <!-- Testimonial Start -->
    <!--<div class="container-fluid bg-testimonial py-5 my-5">-->
    <!--    <div class="container py-5">-->
    <!--        <div class="row justify-content-center">-->
    <!--            <div class="col-lg-7">-->
    <!--                <div class="owl-carousel testimonial-carousel p-5">-->
    <!--                    <div class="testimonial-item text-center text-white">-->
    <!--                        <img class="img-fluid mx-auto p-2 border border-5 border-secondary rounded-circle mb-4" src="img/testimonial-2.jpg" alt="">-->
    <!--                        <p class="fs-5">Dolores sed duo clita justo dolor et stet lorem kasd dolore lorem ipsum. At lorem lorem magna ut et, nonumy labore diam erat. Erat dolor rebum sit ipsum.</p>-->
    <!--                        <hr class="mx-auto w-25">-->
    <!--                        <h4 class="text-white mb-0">Client Name</h4>-->
    <!--                    </div>-->
    <!--                    <div class="testimonial-item text-center text-white">-->
    <!--                        <img class="img-fluid mx-auto p-2 border border-5 border-secondary rounded-circle mb-4" src="img/testimonial-2.jpg" alt="">-->
    <!--                        <p class="fs-5">Dolores sed duo clita justo dolor et stet lorem kasd dolore lorem ipsum. At lorem lorem magna ut et, nonumy labore diam erat. Erat dolor rebum sit ipsum.</p>-->
    <!--                        <hr class="mx-auto w-25">-->
    <!--                        <h4 class="text-white mb-0">Client Name</h4>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->
    <!-- Testimonial End -->


    <!-- Team Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="mx-auto text-center mb-5" style="max-width: 500px;">
                <h6 class="text-primary text-uppercase">The Team</h6>
                <h1 class="display-5">We Are Professional Organic Farmers</h1>
            </div>
            <div class="row g-5">
                <div class="col-lg-4 col-md-6">
                    <div class="row g-0">
                        <div class="col-10">
                            <div class="position-relative">
                                <img class="img-fluid w-100" src="img/team-1.jpg" alt="">
                                <div class="position-absolute start-0 bottom-0 w-100 py-3 px-4" style="background: rgba(52, 173, 84, .85);">
                                    <h4 class="text-white">Farmer Name</h4>
                                    <span class="text-white">Designation</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="h-100 d-flex flex-column align-items-center justify-content-around bg-secondary py-5">
                                <a class="btn btn-square rounded-circle bg-white" href="#"><i class="fab fa-twitter text-secondary"></i></a>
                                <a class="btn btn-square rounded-circle bg-white" href="#"><i class="fab fa-facebook-f text-secondary"></i></a>
                                <a class="btn btn-square rounded-circle bg-white" href="#"><i class="fab fa-linkedin-in text-secondary"></i></a>
                                <a class="btn btn-square rounded-circle bg-white" href="#"><i class="fab fa-instagram text-secondary"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="row g-0">
                        <div class="col-10">
                            <div class="position-relative">
                                <img class="img-fluid w-100" src="img/team-2.jpg" alt="">
                                <div class="position-absolute start-0 bottom-0 w-100 py-3 px-4" style="background: rgba(52, 173, 84, .85);">
                                    <h4 class="text-white">Farmer Name</h4>
                                    <span class="text-white">Designation</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="h-100 d-flex flex-column align-items-center justify-content-around bg-secondary py-5">
                                <a class="btn btn-square rounded-circle bg-white" href="#"><i class="fab fa-twitter text-secondary"></i></a>
                                <a class="btn btn-square rounded-circle bg-white" href="#"><i class="fab fa-facebook-f text-secondary"></i></a>
                                <a class="btn btn-square rounded-circle bg-white" href="#"><i class="fab fa-linkedin-in text-secondary"></i></a>
                                <a class="btn btn-square rounded-circle bg-white" href="#"><i class="fab fa-instagram text-secondary"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="row g-0">
                        <div class="col-10">
                            <div class="position-relative">
                                <img class="img-fluid w-100" src="img/team-3.jpg" alt="">
                                <div class="position-absolute start-0 bottom-0 w-100 py-3 px-4" style="background: rgba(52, 173, 84, .85);">
                                    <h4 class="text-white">Farmer Name</h4>
                                    <span class="text-white">Designation</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="h-100 d-flex flex-column align-items-center justify-content-around bg-secondary py-5">
                                <a class="btn btn-square rounded-circle bg-white" href="#"><i class="fab fa-twitter text-secondary"></i></a>
                                <a class="btn btn-square rounded-circle bg-white" href="#"><i class="fab fa-facebook-f text-secondary"></i></a>
                                <a class="btn btn-square rounded-circle bg-white" href="#"><i class="fab fa-linkedin-in text-secondary"></i></a>
                                <a class="btn btn-square rounded-circle bg-white" href="#"><i class="fab fa-instagram text-secondary"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Team End -->


   
    

    <!-- Footer Start -->
    <?php include "footer.php";?>
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-secondary py-3 fs-4 back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>