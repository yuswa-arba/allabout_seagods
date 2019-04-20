<?php
include("config/configuration.php");
include("config/template_cart.php");
include("config/shipping/action_raja_ongkir.php");
include("config/shipping/province_city.php");
session_start();
ob_start();

$titlebar = "Register Guest";
$menu = "";

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.rajaongkir.com/starter/city?id=39&province=5",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "key: 7423ce43385bd0d2340dfe6c6d55b256"
    ),
));

// Set parameters
$parameters = [
//    'id' => 39,
    'province' => 4
];

// Get city
$get_city = get_city($parameters);

//foreach ($get_province->rajaongkir->results as $province) {
//    if ($province->province_id == 2) {
//        echo $province->province_id . ' ';
//    }
//}
//unset($_SESSION['guest']);

echo "<pre>";
print_r($_SESSION['guest']);
echo "</pre>";

$content = '
    <div id="Content" class="p-t-0">
        <div class="content_wrapper clearfix">
            <div class="" style="margin: 0 auto; width: 70%">
                <div class="section mcb-section p-t-0 p-b-20  full-width">
                    <div class="section_wrapper mcb-section-inner one m-l-0 m-r-0">
                      <div class="wrap mcb-wrap one valign-top clearfix">
                                                
                        <div class="column three-fourth full-width bg-white p-b-30">                            
                            <div class="wrap mcb-wrap full-width">
                                
                                <div class="full-width padding-30 wrap mcb-wrap">
                                    <p class="fs-20 fw-600 text-black">Checkout as Guest (Via Transfer)</p>
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
                                    
                                    
                                    <div class="full-width wrap mcb-wrap">
                                        
                                        <div class="width-60 wrap mcb-wrap p-r-25">
                                        
                                            <form action="" method="post" role="form" enctype="multipart/form-data">
                                                <div class="wrap mcb-wrap full-width m-b-10">
                                                    <div class="full-width pull-left m-r-10">
                                                        <label class="fs-13 fw-500 text-black">Transaction Number <span class="text-red">*</span></label>
                                                        <input type="text" class="full-width m-b-0" name="transaction_number" value="" required readonly="readonly">
                                                    </div>
                                                </div>
                                                <div class="wrap mcb-wrap full-width m-b-10">
                                                    <div class="wrap mcb-wrap width-50 pull-left p-r-10">
                                                        <label class="fs-13 fw-500 text-black">First Name <span class="text-red">*</span></label>
                                                        <input type="text" class="full-width m-b-0" placeholder="ex: John Doe" name="first_name" value="" required>
                                                    </div>
                                                    <div class="wrap mcb-wrap width-50 pull-left">
                                                        <label class="fs-13 fw-500 text-black">Last Name <span class="text-red">*</span></label>
                                                        <input type="text" class="full-width m-b-0" placeholder="ex: Alexander" name="last_name" value="" required>
                                                    </div>
                                                </div>
                                                <div class="wrap mcb-wrap full-width m-b-10">
                                                    <div class="wrap mcb-wrap width-50 pull-left p-r-10">
                                                        <label class="fs-13 fw-500 text-black">No Telephone <span class="text-red">*</span></label>
                                                        <input type="text" class="full-width m-b-0" placeholder="ex: 08129832xxxx" name="phone_no" value="" required>
                                                    </div>
                                                    <div class="wrap mcb-wrap width-50 pull-left">
                                                        <label class="fs-13 fw-500 text-black">Postal Code <span class="text-red">*</span></label>
                                                        <input type="number" class="full-width m-b-0" placeholder="ex: 90895" name="zip_code" value="" required>
                                                    </div>
                                                </div>
                                                <div class="wrap mcb-wrap full-width m-b-10">
                                                    <div class="wrap mcb-wrap width-50 pull-left p-r-10">
                                                        <label class="fs-13 fw-500 text-black">Province <span class="text-red">*</span></label>
                                                        <select name="province" class="m-b-0 full-width" id="province">
                                                            <option value="Bali">Bali</option>
                                                            <option value="NTT">NTT</option>
                                                            <option value="Jawa Timur">Jawa Timur</option>
                                                        </select>
                                                    </div>
                                                    <div class="wrap mcb-wrap width-50 pull-left">
                                                        <label class="fs-13 fw-500 text-black">City <span class="text-red">*</span></label>
                                                        <select name="city" class="m-b-0 full-width" id="city">
                                                            <option value="Denpasar">Denpasar</option>
                                                            <option value="Bangli">Bangli</option>
                                                            <option value="Gianyar">Gianyar</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="wrap mcb-wrap full-width m-b-10">
                                                    <div class="wrap mcb-wrap full-width pull-left">
                                                        <label class="fs-13 fw-500 text-black">Address <span class="text-red">*</span></label>
                                                        <textarea name="address" rows="4" class="full-width m-b-0" placeholder="Ex : By Pass I Gusti Ngurah Rai no. 376, Sanur - Denpasar 80228, Bali - Indonesia" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="wrap mcb-wrap full-width m-b-10">
                                                    <div class="wrap mcb-wrap width-50 pull-left p-r-10">
                                                        <label class="fs-13 fw-500 text-black">E-mail Address <span class="text-red">*</span></label>
                                                        <input type="email" class="full-width m-b-0" placeholder="ex: jhonDoe@seagods.com" name="email" value="" required>
                                                    </div>
                                                    <div class="wrap mcb-wrap width-50 pull-left">
                                                        <label class="fs-13 fw-500 text-black">Account Number <span class="text-red">*</span></label>
                                                        <input type="number" class="full-width m-b-0" placeholder="ex: 90895589483598395" name="account_number" value="" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="wrap mcb-wrap full-width m-b-10">
                                                    <div class="wrap mcb-wrap width-50 pull-left p-r-10">
                                                        <label class="fs-13 fw-500 text-black">From Bank <span class="text-red">*</span></label>
                                                        <input type="text" class="full-width m-b-0" placeholder="ex: BRI or BCA or BNI" name="from_bank" value="" required>
                                                    </div>
                                                    <div class="wrap mcb-wrap width-50 pull-left">
                                                        <label class="fs-13 fw-500 text-black">Transfer to Bank <span class="text-red">*</span></label>
                                                        <select name="id_bank" class="m-b-0 full-width" id="id_bank" required>
                                                            <option hidden>Bank</option>
                                                            <option value="BCA">BCA</option>
                                                            <option value="Mandiri">Mandiri</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="wrap mcb-wrap full-width m-b-20">
                                                    <div class="wrap mcb-wrap width-50 pull-left m-r-10">
                                                        <label class="fs-13 fw-500 text-black">Upload Proof of Transfer <span class="text-red">*</span></label>
                                                        <input type="file" class="full-width m-b-0" name="photo" required>
                                                    </div>
                                                </div>
                                                
                                                <div class="full-width">
                                                    <button type="submit" id="loading-alway" class="btn-blue-light pull-left" name="checkout">Checkout as Guest</button>
                                                </div>
                                            </form>
                                            
                                        </div>
                                        
                                        
                                        <div class="width-40 wrap mcb-wrap b-l-dashed-grey p-l-25">
                                        
                                            <div class="wrap mcb-wrap full-width p-l-15 p-t-35 p-r-15 border-grey">
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
                                                        
                                                <div class="full-width clearfix p-b-5 m-b-30">
                                                    <label class="fs-14 fw-500 m-b-0 pull-left">Total</label>
                                                    <p class="fs-16 text-black fw-600 pull-right m-b-0">
                                                        <span class="woocommerce-Price-amount"><span class="woocommerce-Price-currencySymbol">$</span> 155</span>
                                                    </p>
                                                </div>
                                                
                                                
                                                <div class="full-width wrap mcb-wrap  clearfix border-grey padding-15 m-b-10">
                                                    <p class="fs-15 text-black text-center m-b-10 fw-600">You can transfer to us via</p>
                                                    
                                                    <p class="m-b-0">
                                                        <img src="images/mandiri.jpg" width="40%">
                                                    </p>
                                                    <p class="fs-18 fw-500 text-black b-b-grey p-b-10">
                                                        145-0010-897-318
                                                    </p>
                                                    
                                                    <p class="m-b-0">
                                                        <img src="images/bca.jpg" width="32%">
                                                    </p>
                                                    <p class="fs-18 fw-500 text-black">
                                                        146-668-4848
                                                    </p>
                                                    
                                                </div>
                                                                                    
                                                <div class="full-width">
                                                    <p class="fs-12 text-red fw-700 m-b-0">Noted *</p>
                                                    <p class="fs-14 text-black">
                                                        It is expected to save proof of transfer, to be uploaded as proof of payment
                                                    </p>
                                                    
                                                    <p class="fs-13 text-black m-b-0">
                                                        Contact us for more info
                                                    </p>
                                                    <p class="fs-16 text-black fw-600 m-b-40">
                                                        +62 361 27 11 99
                                                    </p>
                                                    
                                                </div>
                                             </div>    
                                            
                                        </div>
                                        <button type="button" id="test">Test</button>
                                        
                                        
                                    </div>
                                    
                                    
                                </div>
                                
                            </div>
                                                                                    
                        </div>
                        
                        <script>                    
                            $("#loading-alway").click(function() {
                              $("body").loading({
                                message: "Loading..."
                              });
                              
                              setInterval(function() {
                                $("body").loading("stop"); // untuk berenti
                              }, 3000);
                            });
                        </script>
                        
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';


$plugin = '
    <link rel="stylesheet" href="plugins/loading/loading.css">
    <script src="plugins/loading/js/jquery-1.11.0.min.js"></script>
    <script src="plugins/loading/loading.js"></script> 
    <script>
        function get_province() {
            jQuery({
                type: "GET",
                url: "https://api.rajaongkir.com/starter/province",
                data: "",
                header: {key: "7423ce43385bd0d2340dfe6c6d55b256"},
                dataType: "json",
                success: function(data) {
                    console.log(data);
                }
            });
        }
        get_province();
    </script>
';

$template = admin_template($content, $titlebar, $titlepage = "", $user = "", $menu, $plugin);

echo $template;

?>