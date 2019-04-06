<?php
include("config/configuration.php");
include("config/template_cart.php");

$titlebar = "Register Guest";
$menu = "";


$content = '
    <div id="Content" class="p-t-0">
        <div class="content_wrapper clearfix">
            <div class="" style="margin: 0 auto; width: 70%">
                <div class="section mcb-section p-t-0 p-b-20  full-width">
                    <div class="section_wrapper mcb-section-inner one m-l-0 m-r-0">
                      <div class="wrap mcb-wrap one valign-top clearfix">
                                                
                        <div class="column three-fourth full-width m-b-0">
                          <h3 class="fw-500">Register Guest (Checkout Order Items)</h3>
                        </div>
                                                
                        <div class="column three-fourth full-width bg-white p-b-30">                            
                            <div class="wrap mcb-wrap full-width">
                                
                                <div class="full-width padding-30 wrap mcb-wrap">
                                    <p class="fs-20 fw-600 text-blue-light">Checkout as Guest</p>
                                    <div class="width-90 m-b-25">
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
                                    
                                    
                                    <div class="wrap mcb-wrap width-80 m-b-10">
                                        <div class="width-45 pull-left m-r-10">
                                            <label class="fs-13 fw-500 text-black">Full Name <span class="text-red">*</span></label>
                                            <input type="text" class="full-width m-b-0" placeholder="Jhon Doe">
                                        </div>
                                        <div class="width-45 pull-left">
                                            <label class="fs-13 fw-500 text-black">No Telphone <span class="text-red">*</span></label>
                                            <input type="number" class="full-width m-b-0" placeholder="085888xxxxxx">
                                        </div>
                                    </div>
                                    <div class="wrap mcb-wrap width-80 m-b-10">
                                        <div class="width-45 pull-left m-r-10">
                                            <label class="fs-13 fw-500 text-black">E-mail Address <span class="text-red">*</span></label>
                                            <input type="email" class="full-width m-b-0" placeholder="ex: jhonDoe@seagods.com">
                                        </div>
                                        <div class="width-45 pull-left">
                                            <label class="fs-13 fw-500 text-black">Postal Code <span class="text-red">*</span></label>
                                            <input type="number" class="full-width m-b-0" placeholder="90895">
                                        </div>
                                    </div>
                                    <div class="wrap mcb-wrap width-80 m-b-10">
                                        <div class="width-45 pull-left m-r-10">
                                            <label class="fs-13 fw-500 text-black">Province <span class="text-red">*</span></label>
                                            <select name="selectItem" class="m-b-0 full-width" id="">
                                                <option value="Bali">Bali</option>
                                                <option value="NTT">NTT</option>
                                                <option value="Jawa Timur">Jawa Timur</option>
                                            </select>
                                        </div>
                                        <div class="width-45 pull-left">
                                            <label class="fs-13 fw-500 text-black">City <span class="text-red">*</span></label>
                                            <select name="selectItem" class="m-b-0 full-width" id="">
                                                <option value="Denpasar">Denpasar</option>
                                                <option value="Bangli">Bangli</option>
                                                <option value="Gianyar">Gianyar</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="wrap mcb-wrap width-80 m-b-10">
                                        <div class="width-90 pull-left">
                                            <label class="fs-13 fw-500 text-black">Address <span class="text-red">*</span></label>
                                            <textarea name="" id="" rows="4" class="full-width m-b-0" placeholder="Ex : By Pass I Gusti Ngurah Rai no. 376, Sanur - Denpasar 80228, Bali - Indonesia"></textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="wrap mcb-wrap width-80 m-b-10">
                                        <div class="width-45 pull-left m-r-10">
                                            <label class="fs-13 fw-500 text-black">Account Number <span class="text-red">*</span></label>
                                            <input type="number" class="full-width m-b-0" placeholder="90895589483598395">
                                        </div>
                                        <div class="width-45 pull-left">
                                            <label class="fs-13 fw-500 text-black">Select Type Bank <span class="text-red">*</span></label>
                                            <select name="selectItem" class="m-b-0 full-width" id="">
                                                <option value="BCA">BCA</option>
                                                <option value="Mandiri">Mandiri</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="wrap mcb-wrap width-80 m-b-20">
                                        <div class="width-45 pull-left m-r-10">
                                            <label class="fs-13 fw-500 text-black">Upload Proof of Transfer <span class="text-red">*</span></label>
                                            <input type="file" class="full-width m-b-0" placeholder="90895589483598395">
                                        </div>
                                    </div>
                                    
                                    <div class="full-width">
                                        <a href="register.php" class="btn-blue-light">Checkout as Guest</a>
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