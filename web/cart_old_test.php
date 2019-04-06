<?php
include("config/configuration.php");
include("config/template_cart.php");

$titlebar = "Cart";
$menu = "";


$content = '
    <div id="Content" class="p-t-0">
        <div class="content_wrapper clearfix">
            <div class="" style="margin: 0 auto; width: 90%">
                <div class="section mcb-section p-t-0 p-b-20  full-width">
                    <div class="section_wrapper mcb-section-inner one m-l-0 m-r-0">
                      <div class="wrap mcb-wrap one valign-top clearfix">
                            <div class="column three-fourth full-width m-b-0">
                                <h4>Cart</h4>
                            </div>';
$a = 0;
if ($a == 0) {
    $content .= '<div class="column three-fourth" style="width: 69%">
                 
                   <div class="wrap mcb-wrap one valign-top clearfix bg-white m-b-10 border-grey box-shadow-hover">
                      <div class="column one-fifth the_content_wrapper m-b-5 m-t-5">
                        <image src="../admin/images/product/2015-07-09%2016.21.53.jpg"/>
                      </div>
                      <div class="column one-second the_content_wrapper m-t-35 p-l-0 b-r-grey m-b-10 p-r-5" style="width: 45%">
                        <h4 class="title m-t-0 m-b-5 break-word fs-17">Neoprene Rashguard</h4>
                        <p class="fs-18 bold m-b-5 fs-13">
                            <span class="woocommerce-Price-amount text-blue">
                            <span class="woocommerce-Price-currencySymbol">$</span>
                                40
                            </span>
                        </p>
                        
                        <form>
                            <div class="quantity">
                                <label class="text-grey fw-500 fs-13">Quantity</label>
                                <select name="selectItem" class="m-b-0" id="">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            </div>
                        </form>
                      </div>
                      <div class="column one-fifth the_content_wrapper m-t-35 p-l-0" style="width: 29%">
                        <p class="fs-13 fw-500 m-b-0 text-grey">Amount</p>
                        <p class="fs-14 fw-600 text-black break-word">
                            <span class="woocommerce-Price-amount"><span class="woocommerce-Price-currencySymbol">$</span> 40</span>
                        </p>
                        <div class="full-width">
                            <label class="pull-left fw-500 m-r-5 text-black fs-13 bold cursor-pointer" style="display: unset;">
                                <input type="checkbox" name="checkbox" value="value"> Choose
                            </label> <span class="pull-left">/</span>
                            <a href="#" class="pull-left text-red m-l-5 fs-12">Remove</a>
                        </div>
                      </div>
                   </div>
                   
                   <div class="wrap mcb-wrap one valign-top clearfix bg-white m-b-10 border-grey box-shadow-hover">
                      <div class="column one-fifth the_content_wrapper m-b-5 m-t-5">
                        <image src="../admin/images/product/150/thumb_0__MAR4253.jpg"/>
                      </div>
                      <div class="column one-second the_content_wrapper m-t-35 p-l-0 b-r-grey m-b-10 p-r-5" style="width: 45%">
                        <h4 class="title m-t-0 m-b-5 break-word fs-17">Shorty Long Sleeves 3mm</h4>
                        <p class="fs-18 bold m-b-5 fs-13">
                            <span class="woocommerce-Price-amount text-blue">
                            <span class="woocommerce-Price-currencySymbol">$</span>
                                95
                            </span>
                        </p>
                        
                        <form>
                            <div class="quantity">
                                <label class="text-grey fw-500 fs-13">Quantity</label>
                                <select name="selectItem" class="m-b-0" id="">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            </div>
                        </form>
                      </div>
                      <div class="column one-fifth the_content_wrapper m-t-35 p-l-0" style="width: 29%">
                        <p class="fs-13 fw-500 m-b-0 text-grey">Amount</p>
                        <p class="fs-14 fw-600 text-black break-word">
                            <span class="woocommerce-Price-amount"><span class="woocommerce-Price-currencySymbol">$</span> 95</span>
                        </p>
                        <div class="full-width">
                            <label class="pull-left fw-500 m-r-5 text-black fs-13 bold cursor-pointer" style="display: unset;">
                                <input type="checkbox" name="checkbox" value="value"> Choose
                            </label> <span class="pull-left">/</span>
                            <a href="#" class="pull-left text-red m-l-5 fs-12">Remove</a>
                        </div>
                      </div>
                   </div>
                 
                 </div>
                 
                 <div class="column one-fourth bg-white m-l-0 m-r-0 p-l-15 p-t-35 p-r-15 border-grey" style="width: 25%">
                    <div class="full-width clearfix b-b-grey p-b-10 m-b-10">
                        <label class="fs-11 fw-500 m-b-0 pull-left">Total Item</label>
                        <p class="fs-14 text-black fw-600 pull-right m-b-0">2</p>
                    </div>
                    <div class="full-width clearfix b-b-grey p-b-10 m-b-10">
                        <label class="fs-11 fw-500 m-b-0 pull-left">Total Weight <span class="text-black">(Kg)</span></label>
                        <p class="fs-14 text-black fw-600 pull-right m-b-0">
                            <span class="woocommerce-Price-amount">300</span>
                        </p>
                    </div>
                    
                    <div class="full-width clearfix b-b-grey p-b-10 m-b-10">
                        <p class="fs-14 fw-600 text-black m-b-0">Shipping Address</p>
                        <label class="fs-11 fw-500 m-b-0">Province <span class="text-black fw-600">*</span></label>
                        <p class="fs-13 text-black fw-600 m-b-0">
                            <select name="selectItem" class="m-b-0 full-width" id="">
                                <option value="Bali">Bali</option>
                                <option value="NTT">NTT</option>
                                <option value="Jawa Timur">Jawa Timur</option>
                            </select>
                        </p>
                        
                        <label class="fs-11 fw-500 m-b-0">City <span class="text-black fw-600">*</span></label>
                        <p class="fs-13 text-black fw-600">
                            <select name="selectItem" class="m-b-0 full-width" id="">
                                <option value="Denpasar">Denpasar</option>
                                <option value="Bangli">Bangli</option>
                                <option value="Gianyar">Gianyar</option>
                            </select>
                        </p>
                                
                        <label class="fs-11 fw-500 m-b-0 pull-left">Shipping Costs</label>
                        <p class="fs-14 text-black fw-600 pull-right m-b-0">
                            <span class="woocommerce-Price-amount"><span class="woocommerce-Price-currencySymbol">$</span> 20</span>
                        </p>
                    </div>
                            
                    <div class="full-width clearfix b-b-grey p-b-10 m-b-10">
                        <label class="fs-11 fw-500 m-b-0 pull-left">Subtotal</label>
                        <p class="fs-14 text-black fw-600 pull-right m-b-0">
                            <span class="woocommerce-Price-amount"><span class="woocommerce-Price-currencySymbol">$</span> 135</span>
                        </p>
                    </div>
                            
                    <div class="full-width clearfix p-b-5 m-b-20">
                        <label class="fs-14 fw-500 m-b-0 pull-left">Total</label>
                        <p class="fs-16 text-black fw-600 pull-right m-b-0">
                            <span class="woocommerce-Price-amount"><span class="woocommerce-Price-currencySymbol">$</span> 155</span>
                        </p>
                    </div>
                            
                    <div class="full-width">
                        <button class="btn full-width m-r-0 bold">Check Out</button>
                    </div>
                                                        
                    <div class="full-width">
                        <p class="fs-12 text-red fw-700 m-b-0">Noted *</p>
                        <p class="fs-14">
                            The shipping fee will be determined according to the shipping address and the weight of the item you choose
                        </p>
                    </div>
                 </div>';
} else {

    $content .= '<div class="column three-fourth wrap mcb-wrap one width-70 p-l-10">
                    <div class="wrap mcb-wrap one valign-top clearfix bg-white m-b-10 border-grey padding-30">
                        <p class="fs-20 fw-600 text-black m-b-0">Your cart is empty!</p>
                        <p class="fs-4">Please Go to Page Collection and add new items</p>
                        <a href="list_product.php?id_cats=9" class="btn-blue-light bg-black bg-black-hover">Go to Collection</a>
                    </div>
                </div>';
}
$content .= '
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