{{-- <!-- Remove the container if you want to extend the Footer to full width. -->
<div class="container-fluid p-0">
    <!-- Footer -->
    <footer class="text-center text-lg-start text-dark" style="background-color: #ECEFF1">
        <!-- Section: Social media -->
        <section class="d-flex justify-content-between p-4 text-white" style="background-color: #21D192">
            <!-- Left -->
            <div class="me-5">
                <span>Get connected with us on social networks:</span>
            </div>
            <!-- Left -->

            <!-- Right -->
            <div class="d-flex gap-4">
                <a href="" class="text-white me-4">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="" class="text-white me-4">
                    <i class="fab fa-twitter"></i>
                </a>
                <a href="" class="text-white me-4">
                    <i class="fab fa-google"></i>
                </a>
                <a href="" class="text-white me-4">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="" class="text-white me-4">
                    <i class="fab fa-linkedin"></i>
                </a>
                <a href="" class="text-white me-4">
                    <i class="fab fa-github"></i>
                </a>
            </div>
            <!-- Right -->
        </section>
        <!-- Section: Social media -->

        <!-- Section: Links  -->
        <section class="">
            <div class="container text-center text-md-start mt-5">
                <!-- Grid row -->
                <div class="row mt-3">
                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
                        <!-- Content -->
                        <h6 class="text-uppercase fw-bold">Company name</h6>
                        <hr class="mb-4 mt-0 d-inline-block mx-auto"
                            style="width: 60px; background-color: #7c4dff; height: 2px" />
                        <p>
                            Here you can use rows and columns to organize your footer
                            content. Lorem ipsum dolor sit amet, consectetur adipisicing
                            elit.
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-2 col-lg-2 col-xl-2 mx-auto mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold">Products</h6>
                        <hr class="mb-4 mt-0 d-inline-block mx-auto"
                            style="width: 60px; background-color: #7c4dff; height: 2px" />
                        <p>
                            <a href="#!" class="text-dark">MDBootstrap</a>
                        </p>
                        <p>
                            <a href="#!" class="text-dark">MDWordPress</a>
                        </p>
                        <p>
                            <a href="#!" class="text-dark">BrandFlow</a>
                        </p>
                        <p>
                            <a href="#!" class="text-dark">Bootstrap Angular</a>
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold">Useful links</h6>
                        <hr class="mb-4 mt-0 d-inline-block mx-auto"
                            style="width: 60px; background-color: #7c4dff; height: 2px" />
                        <p>
                            <a href="#!" class="text-dark">Your Account</a>
                        </p>
                        <p>
                            <a href="#!" class="text-dark">Become an Affiliate</a>
                        </p>
                        <p>
                            <a href="#!" class="text-dark">Shipping Rates</a>
                        </p>
                        <p>
                            <a href="#!" class="text-dark">Help</a>
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                        <!-- Links -->
                        <h6 class="text-uppercase fw-bold">Contact</h6>
                        <hr class="mb-4 mt-0 d-inline-block mx-auto"
                            style="width: 60px; background-color: #7c4dff; height: 2px" />
                        <p><i class="fas fa-home mr-3"></i> New York, NY 10012, US</p>
                        <p><i class="fas fa-envelope mr-3"></i> info@example.com</p>
                        <p><i class="fas fa-phone mr-3"></i> + 01 234 567 88</p>
                        <p><i class="fas fa-print mr-3"></i> + 01 234 567 89</p>
                    </div>
                    <!-- Grid column -->
                </div>
                <!-- Grid row -->
            </div>
        </section>
        <!-- Section: Links  -->

        <!-- Copyright -->
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2)">
            © 2020 Copyright:
            <a class="text-dark" href="https://mdbootstrap.com/">MDBootstrap.com</a>
        </div>
        <!-- Copyright -->
    </footer>
    <!-- Footer -->
</div>
<!-- End of .container --> --}}

<style>
    .footer {
        background-color: #e5e5e5;
        padding: 20px 0;
        font-family: 'Poppins', sans-serif;
    }

    .footer-container {
        max-width: 1200px;
        margin: auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        align-items: center;
    }

    .footer-left h2 {
        margin: 0;
        font-size: 24px;
    }

    .logo-bold {
        font-weight: 800;
        color: #009444;
    }

    .logo-orange {
        color: #f58220;
        font-weight: 600;
    }

    .footer-copy {
        margin-top: 5px;
        font-size: 14px;
        color: #555;
    }

    .footer-right {
        text-align: right;
    }

    .footer-links {
        margin-bottom: 10px;
        font-size: 14px;
    }

    .footer-links a {
        color: #333;
        text-decoration: none;
        margin: 0 5px;
    }

    .footer-social a {
        margin: 0 5px;
        font-size: 18px;
        color: #333;
        text-decoration: none;
    }

    .call-btn {
        background-color: #f58220;
        color: #fff;
        padding: 6px 12px;
        border-radius: 4px;
        font-weight: 600;
        text-decoration: none;
    }

    .call-btn i {
        margin-right: 5px;
    }

    @media (max-width: 768px) {
        .footer-container {
            flex-direction: column;
            text-align: center;
        }

        .footer-right {
            text-align: center;
            margin-top: 10px;
        }
    }
</style>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-left">
            <h2><span class="logo-bold">Buy</span><span class="logo-orange">Me</span></h2>
            <p class="footer-copy">© 2025 Buyme.lk. All Rights Reserved.</p>
        </div>
        <div class="footer-right">
            <div class="footer-links">
                <a href="#">Stay Safe</a> |
                <a href="#">FAQ</a> |
                <a href="#">Anti-Scam</a> |
                <a href="#">Terms</a> |
                <a href="#">Privacy</a> |
                <a href="#">Blog</a> |
                <a href="#">Contact Us</a>
            </div>
            <div class="footer-social">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="tel:0112356356" class="call-btn">
                    <i class="fab fa-whatsapp"></i> 0112 356 356
                </a>
            </div>
        </div>
    </div>
</footer>
