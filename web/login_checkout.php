<?php
include("config/configuration.php");
include("config/template_cart.php");

$titlebar = "Login Checkout";
$menu = "";

$content = '
    <div id="Content" class="p-t-0">
        <div class="content_wrapper clearfix">
            <div class="" style="margin: 0 auto; width: 90%">
                <div class="section mcb-section p-t-0 p-b-20  full-width">
                    <div class="section_wrapper mcb-section-inner one m-l-0 m-r-0">
                      <div class="wrap mcb-wrap one valign-top clearfix">
                                                
                        <div class="column three-fourth full-width m-b-0 text-center">
                          <h3 class="fw-500">Please Checkout as User Login or Register (Member or Guest)</h3>
                        </div>
                                                
                        <div class="column three-fourth full-width bg-white p-t-30 p-b-30">
                                                    
                            <div class="wrap mcb-wrap width-30">
                                <div class="full-width padding-30 wrap mcb-wrap">
                                    <p class="fs-20 fw-600 text-black">Sign In</p>
                                    <p class="fs-12 text-black m-b-10 fw-500">
                                        - If it\'s already registered, please open the login page, via the button below
                                    </p>
                                    <div class="full-width">
                                        <a href="login.php?action=' . $_GET['action'] . '" class="btn-blue-light bg-black bg-black-hover">Login</a>
                                    </div>
                                 </div>
                            </div>
                            
                            <div class="wrap mcb-wrap width-35 b-l-dashed-grey">
                                <div class="full-width padding-30 wrap mcb-wrap">
                                    <p class="fs-20 fw-600 text-blue-light">Register as Member</p>
                                    <p class="fs-12 text-black m-b-0 fw-500">
                                        - If this is your first using this service, please create an account to access your recommendation requests
                                    </p>
                                    <p class="fs-12 text-black m-b-10 fw-500">
                                        - Review of your information
                                    </p>
                                    <div class="full-width">
                                        <a href="register.php?action=' . $_GET['action'] . '" class="btn-blue-light">Go to Registration</a>
                                    </div>
                                </div>                              
                            </div>
                            
                            <div class="wrap mcb-wrap width-35 b-l-dashed-grey">
                                <div class="full-width padding-30 wrap mcb-wrap">
                                    <p class="fs-20 fw-600 text-blue-light">Checkout as Guest</p>
                                    <div class="width-90 m-b-10">
                                        <p class="fs-12 text-black m-b-0 fw-500">
                                            - It is expected to fill out this form correctly and accordingly, to facilitate the admin in processing the items you ordered
                                        </p>
                                        <p class="fs-12 text-black m-b-0 fw-500">
                                            - Don\'t worry about our services, our services can be trusted
                                        </p>
                                        <p class="fs-12 text-black m-b-0 fw-500">
                                            - Before completing this form, it is expected to transfer in advance according to the specified price and are expected to collect the proof of transfer for upload
                                        </p>
                                        <p class="fs-12 text-black m-b-0 fw-500">
                                            - If you have finished registering, we will send information to your email
                                        </p>
                                        <p class="fs-12 text-black m-b-0 fw-500">
                                            - Terms and Conditions apply
                                        </p>
                                    </div>
                                    
                                    <div class="full-width">
                                        <a href="guest_checkout.php?action=' . $_GET['action'] . '" class="btn-blue-light">Checkout as Guest</a>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        
                        
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';

$plugin = '';

$template = admin_template($content, $titlebar, $titlepage = "", $user = "", $menu, $plugin);

echo $template;

?>