<?php

use App\Config;
use Core\View;

?>
<!-- Footer Section Starts -->
<footer class="footer">
    <!-- Footer Top Area Starts -->
    <div class="container top-footer">
        <div class="row">
            <!-- Footer Widget Starts -->
            <div class="col-xs-6 col-sm-4 col-md-2">
                <h4>DW</h4>
                <div class="menu">
                    <ul>
                        <li><a href="/welcome">Home</a></li>
                        <li><a href="/welcome#about">About</a></li>
                        <li><a href="/welcome#copyrightLaw">Copyright Law</a></li>
                        <li><a href="/work/verify">Verify Work</a></li>
                    </ul>
                </div>
            </div>
            <!-- Footer Widget Ends -->
            <!-- Footer Widget Starts -->
            <div class="col-xs-6 col-sm-4 col-md-2">
                <h4>Support</h4>
                <div class="menu">
                    <ul>
                        <li><a href="/contact">Contact</a></li>
                        <li><a href="/faq">FAQ</a></li>
                        <!--                        <li><a href="terms-of-services.html">Terms of Services</a></li>-->
                        <li><a href="/user/register">Register</a></li>
                        <li><a href="/user/login">Login</a></li>
                    </ul>
                </div>
            </div>
            <!-- Footer Widget Ends -->

            <!-- Footer Widget Starts -->
            <div class="col-xs-6 col-sm-4 col-md-2">
                <h4>Others</h4>
                <div class="menu">
                    <ul>
                        <li><a href="/privacy">Privacy</a></li>
                        <li><a href="/toc">Terms and Conditions</a></li>
                    </ul>
                </div>
            </div>
            <!-- Footer Widget Ends -->

        </div>
        <!-- Footer Bottom Area Starts -->
        <div class="bottom-footer">
            <div class="row">
                <div class="col-xs-12">
                    <!-- Copyright Text Starts -->
                    <p class="text-center">&copy; @<?php View::securePrint(date("Y")); ?> <?php View::securePrint($_ENV["COPYRIGHT_FOOTER_COMPANY"]); ?> All Rights Reserved </p>
                    <!-- Copyright Text Ends -->
                </div>
            </div>
        </div>
        <!-- Footer Bottom Area Ends -->
    </div>
    <!-- Footer Top Area Ends -->

</footer>
<!-- Footer Section Ends -->

<!-- Back To Top Starts -->
<div id="back-top-wrapper">
    <p id="back-top">
        <a href="#top"><span></span></a>
    </p>
</div>
<!-- Back To Top Ends -->
</div>
<!-- Wrapper Ends -->
</body>

</html>
