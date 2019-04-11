<?php
include("config/configuration.php");
include("config/template_cart.php");
include("config/currency_types.php");
session_start();
ob_start();

// Set price custom item
function get_price($name)
{
    $query_setting_price = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = '$name' LIMIT 0,1");
    $row_setting_price = mysql_fetch_array($query_setting_price);
    return $row_setting_price['value'];
}

// Default currency
$currency_code = CURRENCY_USD_CODE;

// If session is exists
if (isset($_SESSION['currency_code'])) {
    $currency_code = $_SESSION['currency_code'];
}

// Set currency
$currency = get_currency($currency_code);

// Set nominal curs from USD to IDR
$USDtoIDR = get_price('currency-value-usd-to-idr');

$titlebar = "Guest Checkout";
$menu = "";

// Save to session
$guest = ['guest'];
if (isset($_SESSION['guest'])) {
    $guest = $_SESSION['guest'];
}

// Set transaction number
$transaction_number = generate_transaction_number();

// Set row cart
$row_cart = [];

// Set value nominal default
$total_amount = 0;
$total_shipping = 0;
$total_amount_shipping = 0;
$weight = 0;
$total_quantity = 0;

// Set nominal transaction
if (isset($_SESSION['cart_item']) && !empty($_SESSION['cart_item'])) {

    // Set cart item
    foreach ($_SESSION['cart_item'] as $key => $cart_item) {

        if ($cart_item['is_custom_cart']) {

            // Set from collection
            $row_item = $cart_item['collection'];

        } else {

            // Set from item
            $item_query = mysql_query("SELECT * FROM `item` WHERE `id_item` = '" . $cart_item["id_item"] . "' LIMIT 0,1;");
            $row_item = mysql_fetch_array($item_query);

        }

        // Set total amount
        $total_amount += $cart_item['amount'];

        // Set total quantity
        $total_quantity += $cart_item['quantity'];

        // Set wight
        $weight += (!$cart_item['is_custom_cart'] ? ($cart_item['quantity'] * $row_item['weight']) : ($cart_item['quantity'] * get_price('default-weight-custom-item')));

        $row_cart['items'][] = [
            'title' => (!$cart_item['is_custom_cart'] ? $row_item['title'] : 'Custom Wetsuit'),
            'qty' => $cart_item['quantity'],
            'price' => (!$cart_item['is_custom_cart'] ? $row_item['price'] : get_price('price-custom-item')),
            'amount' => $cart_item['amount']
        ];
    }

    $row_city['ongkos_kirim'] = 0;

    // Check city
    if (isset($_POST['checkout'])) {

        // Set city
        $id_city = mysql_real_escape_string(trim($_POST['city']));

        // Set city
        $city_query = mysql_query("SELECT * FROM `kota` WHERE `idKota` = '$id_city' LIMIT 0,1;");
        $row_city = mysql_fetch_array($city_query);

    } else {

        // Check city
        if (isset($guest['id_city'])) {

            // Set city
            $id_city = mysql_real_escape_string(trim($guest['id_city']));

            // Set city
            $city_query = mysql_query("SELECT * FROM `kota` WHERE `idKota` = '$id_city' LIMIT 0,1;");
            $row_city = mysql_fetch_array($city_query);

        }
    }

    // Set weight
    $weight_round = ((round($weight) < 1) ? 1 : round($weight));

    // Set shipping
    $shipping = (isset($row_city['ongkos_kirim']) ? (($currency_code == CURRENCY_USD_CODE) ? $row_city['ongkos_kirim'] : ($row_city['ongkos_kirim'] * $USDtoIDR)) : 0);
    $total_shipping = ($weight_round * (float)$shipping);

    // Set total amount with currency
    $total_amount = (($currency_code == CURRENCY_USD_CODE) ? $total_amount : ($total_amount * $USDtoIDR));

    // Set total amount shipping
    $total_amount_shipping = ($total_amount + $total_shipping);

    // Set default city
    $row_cart['total_qty'] = $total_quantity;
    $row_cart['subtotal'] = $total_amount;
    $row_cart['weight'] = $weight;
    $row_cart['price_shipping'] = $row_city['ongkos_kirim'];
    $row_cart['shipping'] = ($weight_round * $row_city['ongkos_kirim']);
    $row_cart['total'] = $total_amount_shipping;

} else {
    echo "<script>
        alert('Cart does not exists.');
        window.history.back(-1);
    </script>";
    exit();
}

if (isset($_POST['checkout'])) {

    // Set value request
    $transaction_number = isset($_POST['transaction_number']) ? mysql_real_escape_string(trim($_POST['transaction_number'])) : '';
    $first_name = isset($_POST['first_name']) ? mysql_real_escape_string(trim($_POST['first_name'])) : '';
    $last_name = isset($_POST['last_name']) ? mysql_real_escape_string(trim($_POST['last_name'])) : '';
    $phone_no = isset($_POST['phone_no']) ? mysql_real_escape_string(trim($_POST['phone_no'])) : '';
    $zip_code = isset($_POST['zip_code']) ? mysql_real_escape_string(trim($_POST['zip_code'])) : '';
    $province = isset($_POST['province']) ? mysql_real_escape_string(trim($_POST['province'])) : '';
    $city = isset($_POST['city']) ? mysql_real_escape_string(trim($_POST['city'])) : '';
    $address = isset($_POST['address']) ? mysql_real_escape_string(trim($_POST['address'])) : '';
    $email = isset($_POST['email']) ? mysql_real_escape_string(trim($_POST['email'])) : '';
    $account_number = isset($_POST['account_number']) ? mysql_real_escape_string(trim($_POST['account_number'])) : '';
    $from_bank = isset($_POST['from_bank']) ? mysql_real_escape_string(trim($_POST['from_bank'])) : '';
    $id_bank = isset($_POST['id_bank']) ? mysql_real_escape_string(trim($_POST['id_bank'])) : '';
    $photoNameUpload = '';


    if (!empty($transaction_number) && !empty($first_name) && !empty($last_name) && !empty($phone_no) && !empty($zip_code) && !empty($province)
        && !empty($city) && !empty($address) && !empty($email) && !empty($account_number) && !empty($from_bank) && !empty($id_bank)
    ) {

        // Begin transaction
        begin_transaction();

        // Save session guest
        $_SESSION['guest']['first_name'] = $first_name;
        $_SESSION['guest']['last_name'] = $last_name;
        $_SESSION['guest']['account_number'] = $account_number;
        $_SESSION['guest']['address'] = $address;
        $_SESSION['guest']['id_province'] = $province;
        $_SESSION['guest']['id_city'] = $city;
        $_SESSION['guest']['zip_code'] = $zip_code;
        $_SESSION['guest']['email'] = $email;
        $_SESSION['guest']['phone_no'] = $phone_no;
        $_SESSION['guest']['from_bank'] = $from_bank;
        $_SESSION['guest']['id_bank'] = $id_bank;

        // Set photo
        if ($_FILES['photo']['tmp_name'] != "") {

            $target_path = "images/evidenceTransfer/"; // Declaring Path for uploaded images.

            // Set memory limit in php.ini
            ini_set('memory_limit', '120M');

            // Loop to get individual element from the array
            $validextensions = array("jpeg", "jpg", "png", "PNG"); // Extensions which are allowed.
            $RandomNumber = date('ymdhis') . rand(0, 9999999999); // for create name file upload
            $imageName = $RandomNumber . "-" . (str_replace(' ', '_', $_FILES['photo']['name']));

            $ext = explode('.', $_FILES['photo']['name']); // Explode file name from dot(.)
            $file_extension = end($ext); // Store extensions in the variable.

            if (($_FILES["photo"]["size"] < 5000000) // Approx. 5Mb files can be uploaded.
                && in_array($file_extension, $validextensions)
            ) {
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_path . $imageName)) {
                    $photoNameUpload = $imageName;
                } else {
                    echo "<script language='JavaScript'>
                        alert('File Was Not Moved.');
                        window.history.go(-1);
				    </script>";
                    exit();
                }
            } else {
                echo "<script language='JavaScript'>
                    alert('Unable to set size extension.');
                    window.history.go(-1);
			    </script>";
                exit();
            }

        } else {
            echo "<script language='JavaScript'>
		        alert('Your photo is empty!!');
		        window.history.go(-1);
		    </script>";
            exit();
        }

        // Insert guest
        $insert_guest_query = "INSERT INTO `guest` (`first_name`, `last_name`, `account_number`, `address`, `id_country`, `id_province`, `id_city`, `zip_code`, `email`, `phone_no`, `date_add`, `date_upd`, `level`)
            VALUES('$first_name', '$last_name', '$account_number', '$address', 'ID', '$province', '$city', '$zip_code', '$email', '$phone_no', NOW(), NOW(), '0');";

        // Error
        if (!mysql_query($insert_guest_query)) {
            roll_back();
            echo "<script>
                alert('Unable to save guest');
                window.history.back(-1);
            </script>";
            exit();
        }

        // Get guest
        $guest_query = mysql_query("SELECT * FROM `guest` WHERE `email` = '$email' AND `phone_no` = '$phone_no' AND `account_number` = '$account_number' ORDER BY `id` DESC LIMIT 0,1;");
        $row_guest = mysql_fetch_array($guest_query);

        // Insert to transaction
        $insert_transaction_query = "INSERT INTO `transaction` (`kode_transaction`, `is_guest`,`id_guest`, `status`, `konfirm`, `payment_method`, `total`, `date_add`, `date_upd`)
            VALUES('$transaction_number', '1', '" . $row_guest["id"] . "', 'process', 'not confirmated', 'Bank Transfer', '$total_amount_shipping', NOW(), NOW())";

        // Error
        if (!mysql_query($insert_transaction_query)) {
            roll_back();
            echo "<script>
                alert('Unable to save transaction.');
                window.history.back(-1);
            </script>";
            exit();
        }

        // Select transaction
        $transaction_query = mysql_query("SELECT * FROM `transaction` WHERE `kode_transaction` = '$transaction_number' AND ISNULL(id_member)
            AND `status` = 'process' AND `konfirm` = 'not confirmated' AND `payment_method` = 'Bank Transfer' ORDER BY `id_transaction` DESC LIMIT 0,1;");
        $row_transaction = mysql_fetch_array($transaction_query);

        // Insert bank transfer
        $insert_bank_transfer_query = "INSERT INTO `bank_transfer` (`id_transaction`, `id_bank`, `is_guest`, `id_guest`, `from_bank`, `account_number`, `amount`, `photo`, `date_add`, `date_upd`)
            VALUES('" . $row_transaction["id_transaction"] . "', '$id_bank', '1', '" . $row_guest["id"] . "', '$from_bank', '$account_number', '$total_amount_shipping', '$photoNameUpload', NOW(), NOW());";

        // Error
        if (!mysql_query($insert_bank_transfer_query)) {
            roll_back();
            echo "<script>
                alert('Unable to save bank transfer');
                window.history.back(-1);
            </script>";
            exit();
        }

        // Insert to cart
        foreach ($_SESSION['cart_item'] as $key => $cart_item) {

            // check custom cart
            if ($cart_item['is_custom_cart'] || $cart_item['is_custom_cart'] == true) {

                // Set cart collection value
                $cart_collection = $cart_item['collection'];

                // Insert collection
                $insert_collection_query = "INSERT INTO `custom_collection` (`code`, `is_guest`, `id_guest`, `gender`, `wet_suit_type`, `arm_zipper`, `ankle_zipper`, `image`, `price`, `status`, `date_add`, `date_upd`, `level`)
                    VALUES('" . $cart_collection["code"] . "', '1', '" . $row_guest["id"] . "', '" . $cart_collection["gender"] . "', '" . $cart_collection["wet_suit_type"] . "', '" . $cart_collection["arm_zipper"] . "', '" . $cart_collection["ankle_zipper"] . "', '" . $cart_collection["image"] . "', '" . $cart_collection["price"] . "', '" . $cart_collection["status"] . "', NOW(), NOW(), '0');";

                // Error
                if (!mysql_query($insert_collection_query)) {
                    roll_back();
                    echo "<script>
                        alert('Unable to save custom collection');
                        window.history.back(-1);
                    </script>";
                    exit();
                }

                // Get custom collection
                $custom_collection_query = mysql_query("SELECT * FROM `custom_collection` WHERE `code` = '" . $cart_collection["code"] . "' AND ISNULL(id_member) AND `level` = '0' ORDER BY `id_custom_collection` DESC LIMIT 0,1;");
                $row_custom_collection = mysql_fetch_array($custom_collection_query);

                // Set collection measure
                $collection_measure = $cart_item['measure'];

                // Insert custom measure
                $insert_measure_query = "INSERT INTO `custom_measure` (`id_custom_collection`, `total_body_height`, `head`, `neck`, `bust_chest`, `waist`, `stomach`, `abdomen`, `hip`, `shoulder`, `shoulder_elbow`, `shoulder_wrist`, `arm_hole`, `upper_arm`,
                    `bicep`, `elbow`, `forarm`, `wrist`, `outside_leg_length`, `inside_leg_length`, `upper_thigh`, `thigh`, `above_knee`, `knee`, `below_knee`, `calf`, `below_calf`,
                    `above_ankle`, `shoulder_burst`, `shoulder_waist`, `shoulder_hip`, `hip_knee_length`, `knee_ankle_length`, `back_shoulder`, `dorsum`, `crotch_point`)
                    VALUES('" . $row_custom_collection["id_custom_collection"] . "', '" . $collection_measure["total_body_height"] . "', '" . $collection_measure["head"] . "', '" . $collection_measure["neck"] . "', '" . $collection_measure["bust_chest"] . "', '" . $collection_measure["waist"] . "', '" . $collection_measure["stomach"] . "', '" . $collection_measure["abdomen"] . "', '" . $collection_measure["hip"] . "', '" . $collection_measure["shoulder"] . "', '" . $collection_measure["shoulder_elbow"] . "', '" . $collection_measure["shoulder_wrist"] . "', '" . $collection_measure["arm_hole"] . "', '" . $collection_measure["upper_arm"] . "',
                    '" . $collection_measure["bicep"] . "', '" . $collection_measure["elbow"] . "', '" . $collection_measure["forarm"] . "', '" . $collection_measure["wrist"] . "', '" . $collection_measure["outside_leg_length"] . "', '" . $collection_measure["inside_leg_length"] . "', '" . $collection_measure["upper_thigh"] . "', '" . $collection_measure["thigh"] . "', '" . $collection_measure["above_knee"] . "', '" . $collection_measure["knee"] . "', '" . $collection_measure["below_knee"] . "', '" . $collection_measure["calf"] . "', '" . $collection_measure["below_calf"] . "',
                    '" . $collection_measure["above_ankle"] . "', '" . $collection_measure["shoulder_burst"] . "', '" . $collection_measure["shoulder_waist"] . "', '" . $collection_measure["shoulder_hip"] . "', '" . $collection_measure["hip_knee_length"] . "', '" . $collection_measure["knee_ankle_length"] . "', '" . $collection_measure["back_shoulder"] . "', '" . $collection_measure["dorsum"] . "', '" . $collection_measure["crotch_point"] . "');";

                // Error
                if (!mysql_query($insert_measure_query)) {
                    roll_back();
                    echo "<script>
                        alert('Unable to save custom measure');
                        window.history.back(-1);
                    </script>";
                    exit();
                }

                // Insert cart
                $insert_cart_query = "INSERT INTO `cart` (`id_transaction`, `is_guest`, `id_guest`, `id_item`, `is_custom_cart`, `qty`, `amount`, `date_add`, `date_upd`, `level`)
                    VALUES('" . $row_transaction["id_transaction"] . "', '1', '" . $row_guest["id"] . "', '" . $row_custom_collection["id_custom_collection"] . "', '1', '" . $cart_item["quantity"] . "', '" . $cart_item["amount"] . "', NOW(), NOW(), '0');";

                // Error
                if (!mysql_query($insert_cart_query)) {
                    roll_back();
                    echo "<script>
                        alert('Unable to save custom cart');
                        window.history.back(-1);
                    </script>";
                    exit();
                }

            } else {

                // Add cart
                $insert_cart_query = "INSERT INTO `cart` (`id_transaction`, `is_guest`, `id_guest`, `id_item`, `qty`, `amount`, `date_add`, `date_upd`, `level`)
                    VALUES('" . $row_transaction["id_transaction"] . "', '1', '" . $row_guest["id"] . "', '" . $cart_item["id_item"] . "', '" . $cart_item["quantity"] . "', '" . $cart_item["amount"] . "', NOW(), NOW(), '0');";

                // Error
                if (!mysql_query($insert_cart_query)) {
                    roll_back();
                    echo "<script>
                        alert('Unable to save cart');
                        window.history.back(-1);
                    </script>";
                    exit();
                }

            }

        }

        // Insert shipping
        $insert_shipping_query = "INSERT INTO `transaction_shipping` (`id_transaction`, `weight`, `price`, `amount`, `date_add`, `date_upd`)
                VALUES('" . $row_transaction["id_transaction"] . "', '" . $row_cart['weight'] . "', '" . $row_cart['price_shipping'] . "', '" . $row_cart['shipping'] . "', NOW(), NOW());";
        if (!mysql_query($insert_shipping_query)) {
            roll_back();
            echo "<script>
                alert('Unable to save shipping');
                window.history.back(-1);
            </script>";
            exit();
        }

        // Commit
        commit();

        // Remove session
        unset($_SESSION['cart_item']);

        // Success
        echo "<script>
            alert('Payment process successfully');
            window.location.href = 'home.php'
        </script>";
        exit();

    } else {
        echo "<script>
            alert('All Request parameter required');
            window.history.back(-1);
        </script>";
        exit();
    }
}

$content = '
    <div id="Content" class="p-t-0 page-container">
        <div class="content_wrapper clearfix">
            <div class="" style="margin: 0 auto; width: 70%">
                <div class="section mcb-section p-t-0 p-b-20  full-width">
                    <div class="section_wrapper mcb-section-inner one m-l-0 m-r-0">
                        <div class="wrap mcb-wrap one valign-top clearfix">
                                                
                            <div class="column three-fourth full-width m-b-0">
                              <h3 class="fw-500">Register Guest (Checkout Order Items)</h3>
                            </div>';

$content .= '               
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
                                        
                                        <form action="" method="post" role="form" enctype="multipart/form-data">
                                            <div class="full-width wrap mcb-wrap">
                                                
                                                <div class="width-60 wrap mcb-wrap p-r-25">
                                                
                                                    <div class="wrap mcb-wrap full-width m-b-10">
                                                        <div class="full-width pull-left m-r-10">
                                                            <label class="fs-13 fw-500 text-black">Transaction Number <span class="text-red">*</span></label>
                                                            <input type="text" class="full-width m-b-0" name="transaction_number" id="transaction_number" value="' . $transaction_number . '" required readonly="readonly">
                                                        </div>
                                                    </div>
                                                    <div class="wrap mcb-wrap full-width m-b-10">
                                                        <div class="wrap mcb-wrap width-50 pull-left p-r-10">
                                                            <label class="fs-13 fw-500 text-black">First Name <span class="text-red">*</span></label>
                                                            <input type="text" class="full-width m-b-0" placeholder="ex: John Doe" name="first_name" id="first_name" value="' . (isset($guest['first_name']) ? $guest['first_name'] : '') . '" required>
                                                        </div>
                                                        <div class="wrap mcb-wrap width-50 pull-left">
                                                            <label class="fs-13 fw-500 text-black">Last Name <span class="text-red">*</span></label>
                                                            <input type="text" class="full-width m-b-0" placeholder="ex: Alexander" name="last_name" id="last_name" value="' . (isset($guest['last_name']) ? $guest['last_name'] : '') . '" required>
                                                        </div>
                                                    </div>
                                                    <div class="wrap mcb-wrap full-width m-b-10">
                                                        <div class="wrap mcb-wrap width-50 pull-left p-r-10">
                                                            <label class="fs-13 fw-500 text-black">No Telephone <span class="text-red">*</span></label>
                                                            <input type="text" class="full-width m-b-0" placeholder="ex: 08129832xxxx" name="phone_no" id="phone_no" value="' . (isset($guest['phone_no']) ? $guest['phone_no'] : '') . '" required>
                                                        </div>
                                                        <div class="wrap mcb-wrap width-50 pull-left">
                                                            <label class="fs-13 fw-500 text-black">Postal Code <span class="text-red">*</span></label>
                                                            <input type="number" class="full-width m-b-0" placeholder="ex: 90895" name="zip_code" id="zip_code" value="' . (isset($guest['zip_code']) ? $guest['zip_code'] : '') . '" required>
                                                        </div>
                                                    </div>
                                                    <div class="wrap mcb-wrap full-width m-b-10">
                                                        <div class="wrap mcb-wrap width-50 pull-left p-r-10">
                                                            <label class="fs-13 fw-500 text-black">Province <span class="text-red">*</span></label>
                                                            <select name="province" class="m-b-0 full-width" id="province" onchange="changeProvince();" required>
                                                                <option hidden>Province</option>';

$all_province_query = mysql_query("SELECT * FROM `provinsi`;");
while ($row_all_province = mysql_fetch_array($all_province_query)) {
    $content .= '<option value="' . $row_all_province['idProvinsi'] . '" ' . (isset($guest['id_province']) ? (($guest['id_province'] == $row_all_province['idProvinsi']) ? 'selected' : '') : '') . '>' . $row_all_province['namaProvinsi'] . '</option>';
}

$content .= '
                                                            </select>
                                                        </div>
                                                        <div class="wrap mcb-wrap width-50 pull-left">
                                                            <label class="fs-13 fw-500 text-black">City <span class="text-red">*</span></label>
                                                            <select name="city" class="m-b-0 full-width" id="city" onchange="changeCity();" required>
                                                                <option hidden>City</option>';

// Set where province
$where_province = (isset($guest['id_province']) ? "AND `idProvinsi` = '" . $guest['id_province'] . "'" : '');

$all_city_query = mysql_query("SELECT * FROM `kota` WHERE `level` = '0' $where_province;");
while ($row_all_city = mysql_fetch_array($all_city_query)) {
    $content .= '<option value="' . $row_all_city['idKota'] . '" ' . (isset($guest['id_city']) ? (($guest['id_city'] == $row_all_city['idKota']) ? 'selected' : '') : '') . '>' . $row_all_city['namaKota'] . '</option>';
}

$content .= '
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="wrap mcb-wrap full-width m-b-10">
                                                        <div class="full-width pull-left m-r-10">
                                                            <label class="fs-13 fw-500 text-black">Address <span class="text-red">*</span></label>
                                                            <textarea name="address" id="address" class="full-width m-b-0" placeholder="Ex : By Pass I Gusti Ngurah Rai no. 376, Sanur - Denpasar 80228, Bali - Indonesia" required>' . (isset($guest['address']) ? $guest['address'] : '') . '</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="wrap mcb-wrap full-width m-b-10">
                                                        <div class="wrap mcb-wrap width-50 pull-left p-r-10">
                                                            <label class="fs-13 fw-500 text-black">E-mail Address <span class="text-red">*</span></label>
                                                            <input type="email" class="full-width m-b-0" placeholder="ex: jhonDoe@seagods.com" name="email" id="email" value="' . (isset($guest['email']) ? $guest['email'] : '') . '" required>
                                                        </div>
                                                        <div class="wrap mcb-wrap width-50 pull-left" ' . (($currency_code == CURRENCY_USD_CODE) ? 'hidden' : '') . '>
                                                            <label class="fs-13 fw-500 text-black">Account Number <span class="text-red">*</span></label>
                                                            <input type="number" class="full-width m-b-0" placeholder="ex: 90895589483598395" name="account_number" id="account_number" value="' . (isset($guest['account_number']) ? $guest['account_number'] : '') . '" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="wrap mcb-wrap full-width m-b-10" ' . (($currency_code == CURRENCY_USD_CODE) ? 'hidden' : '') . '>
                                                        <div class="wrap mcb-wrap width-50 pull-left p-r-10">
                                                            <label class="fs-13 fw-500 text-black">From Bank <span class="text-red">*</span></label>
                                                            <input type="text" class="full-width m-b-0" placeholder="ex: BRI or BCA or BNI" name="from_bank" id="from_bank" value="' . (isset($guest['from_bank']) ? $guest['from_bank'] : '') . '" required>
                                                        </div>
                                                        <div class="wrap mcb-wrap width-50 pull-left">
                                                            <label class="fs-13 fw-500 text-black">Transfer to Bank <span class="text-red">*</span></label>
                                                            <select name="id_bank" class="m-b-0 full-width" id="id_bank" required>
                                                                <option hidden>Bank</option>';

$bank_query = mysql_query("SELECT * FROM `bank_account` WHERE `level` = '0';");
while ($row_bank = mysql_fetch_array($bank_query)) {
    $content .= '<option value="' . $row_bank['id'] . '" ' . (isset($guest['id_bank']) ? (($guest['id_bank'] == $row_bank['id']) ? 'selected' : '') : '') . '>' . $row_bank['name'] . '</option>';
}

$content .= '
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="wrap mcb-wrap full-width m-b-10" ' . (($currency_code == CURRENCY_USD_CODE) ? 'hidden' : '') . '>
                                                        <div class="wrap mcb-wrap width-50 pull-left p-r-10">
                                                            <label class="fs-13 fw-500 text-black">Upload Proof of Transfer <span class="text-red">*</span></label>
                                                            <input type="file" class="full-width m-b-0" name="photo" required>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="full-width">&nbsp;</div>
                                                    <div class="full-width" id="nex_process_payment">
                                                        <button type="button" id="check_validation" onclick="checkValidation()" class="btn-green-light">Next to Process payment</button>   
                                                    </div>
                                                    
                                                    <div class="full-width" id="payment_button" hidden>';

if ($currency_code == CURRENCY_USD_CODE) {
    $content .= '<div class="from-left pull-left" id="paypal_button"></div>';
} else {
    $content .= '<button type="submit" class="btn-blue-light" name="checkout" id="transfer_button" value="Test">Checkout as Guest</button>';
}

$content .= '
                                                    </div>
                                                </div>

                                                <div class="width-40 wrap mcb-wrap b-l-dashed-grey p-l-25">
                                                    <table hidden>';

foreach ($row_cart['items'] as $key => $item) {

    $content .= '
                                <tr class="form_items[]">
                                    <td><input type="hidden" id="title_' . $key . '" value="' . $item['title'] . '"></td>
                                    <td><input type="hidden" id="qty_' . $key . '" value="' . $item['qty'] . '"></td>
                                    <td><input type="hidden" id="price_' . $key . '" value="' . $item['price'] . '"></td>
                                    <td><input type="hidden" id="amount_' . $key . '" value="' . $item['amount'] . '"></td>
                                </tr>';

}

$content .= '
                                                    </table>
                                                
                                                    <div class="wrap mcb-wrap full-width p-l-15 p-t-35 p-r-15 border-grey">
                                                        <div class="full-width clearfix b-b-grey p-b-10 m-b-10">
                                                            <label class="fs-11 fw-500 m-b-0 pull-left">Total Item</label>
                                                            <p class="fs-14 text-black fw-600 pull-right m-b-0">' . $row_cart['total_qty'] . '</p>
                                                            <input type="hidden" id="total_qty" value="' . $row_cart['total_qty'] . '">
                                                        </div>
                                                        <div class="full-width clearfix b-b-grey p-b-10 m-b-10">
                                                            <label class="fs-11 fw-500 m-b-0 pull-left">Total Weight <span class="text-black">(Kg)</span></label>
                                                            <p class="fs-14 text-black fw-600 pull-right m-b-0">
                                                                <span class="woocommerce-Price-amount">' . $row_cart['weight'] . '</span>
                                                            </p>
                                                            <input type="hidden" id="weight" value="' . $row_cart['weight'] . '">
                                                        </div>
                                                        
                                                        <div class="full-width clearfix b-b-grey p-b-10 m-b-10">
                                                            <label class="fs-11 fw-500 m-b-0 pull-left">Shipping Costs</label>
                                                            <p class="fs-14 text-black fw-600 pull-right m-b-0">
                                                                <span class="woocommerce-Price-amount"><span class="woocommerce-Price-currencySymbol">' . $currency . '</span> ' . (($currency_code == CURRENCY_USD_CODE) ? $row_cart['shipping'] : number_format(($row_cart['shipping'] * $USDtoIDR), 0, '.', ',')) . '</span>
                                                            </p>
                                                            <input type="hidden" id="price_shipping" value="' . $row_cart['price_shipping'] . '">
                                                            <input type="hidden" id="shipping" value="' . $row_cart['shipping'] . '">
                                                        </div>
                                                        
                                                        <div class="full-width clearfix b-b-grey p-b-10 m-b-10">
                                                            <label class="fs-11 fw-500 m-b-0 pull-left">Subtotal</label>
                                                            <p class="fs-14 text-black fw-600 pull-right m-b-0">
                                                                <span class="woocommerce-Price-amount"><span class="woocommerce-Price-currencySymbol">' . $currency . '</span> ' . (($currency_code == CURRENCY_USD_CODE) ? $row_cart['subtotal'] : number_format(($row_cart['subtotal'] * $USDtoIDR), 0, '.', ',')) . '</span>
                                                            </p>
                                                            <input type="hidden" id="subtotal" value="' . $row_cart['subtotal'] . '">
                                                        </div>
                                                                
                                                        <div class="full-width clearfix p-b-5 m-b-30">
                                                            <label class="fs-14 fw-500 m-b-0 pull-left">Total</label>
                                                            <p class="fs-16 text-black fw-600 pull-right m-b-0">
                                                                <span class="woocommerce-Price-amount"><span class="woocommerce-Price-currencySymbol">' . $currency . '</span> ' . (($currency_code == CURRENCY_USD_CODE) ? $row_cart['total'] : number_format(($row_cart['total'] * $USDtoIDR), 0, '.', ',')) . '</span>
                                                            </p>
                                                            <input type="hidden" id="total" value="' . $row_cart['total'] . '">
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
                                            </div>
                                        </form>
                                    
                                </div>
                                                                                        
                            </div>
                        
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>                    
        function showLoading(){
            $("body").loading({
                message: "Please wait..."
            });
        }             
        function hideLoading(){
            $("body").loading("stop"); // untuk berenti
        }
    </script>';

$plugin = '
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
    <script src="js/paypal/set-config.js"></script>
    <link rel="stylesheet" href="plugins/loading/loading.css">
    <script src="plugins/loading/js/jquery-1.11.0.min.js"></script>
    <script src="plugins/loading/loading.js"></script>
    <script>
        function changeProvince() {
            var id_province = jQuery("#province").val();
            console.log(id_province);
            
            jQuery.ajax({
                type: "POST",
                url: "member/change_city.php",
                data: {
                    action: "change_province",
                    id_province: id_province
                },
                dataType: "json",
                success: function(data) {
                    if (data.status == "error") {
                        alert(data.msg);
                    } else {
                        var city = jQuery("#city");
                        city.html("");
                        city.append("<option>city</option>");
                        for (var i = 0; i < data.results.length; i++) {
                            city.append(
                                \'<option value="\' + data.results[i].idKota + \'">\' + data.results[i].namaKota + \'</option>\'
                            );
                        }
                    }
                }
            });
        }
        
        function changeCity() {
            var id_city = jQuery("#city").val();
            
            jQuery.ajax({
                type: "POST",
                url: "member/change_city.php",
                data: {
                    action: "change_city",
                    id_city: id_city
                },
                dataType: "json",
                success: function(data) {
                    window.location.reload();
                }
            });
        } 
        
        function checkValidation() {
            
                var first_name = jQuery("#first_name").val();
                var last_name = jQuery("#last_name").val();
                var phone_number = jQuery("#phone_number").val();
                var address = jQuery("#address").val();
                var country_code = jQuery("#country_code").val();
                var province = jQuery("#province").val().split("-");
                var city = jQuery("#city").val().split("-");
                var zip_code = jQuery("#zip_code").val();

                if (first_name == "" ||
                    last_name == "" ||
                    email == "" ||
                    phone_number == "" ||
                    address == "" ||
                    country_code == "0" ||
                    province == "" ||
                    city == "" ||
                    zip_code == "") {
    
                    alert("All request parameter required");
                } else {
                    jQuery("#nex_process_payment").attr("hidden", true);
                    jQuery("#payment_button").attr("hidden", false);
                }
            
        }
        
        paypal.Button.render({

            style: paypalStyle,

            env: paypalEvn,

            client: {
                sandbox: paypalTokenSandboxClientID,
                production: paypalTokenProductionClientID
            },

            commit: true,
            
            payment: function (data, actions) {

                var id_items = jQuery(\'tr[class="form_items[]"]\').map(function () {
                    return this;
                }).get();

                var item_list = [];

                for (var i = 0; i < id_items.length; i++) {
                    item_list.push({
                        name: jQuery("#title_" + i).val(),
                        quantity: jQuery("#qty_" + i).val(),
                        price: jQuery("#price_" + i).val(),
                        sku: (i + 1),
                        currency: "USD"
                    });
                }

                return actions.payment.create({
                    payment: {
                        transactions: [{
                            amount: {
                                currency: "USD",
                                total: jQuery("#total").val(),
                                // akan diaktifkan bila biaya yang lain selain barang dikenakan
                                details: {
                                    shipping: jQuery("#shipping").val(), // Diisi dengan biaya pengiriman
                                    subtotal: jQuery("#subtotal").val(), // Akan diisi jika salah satu dari tax atau shipping diisi
                                    tax: "0" // akan diisi biaya tax jeka perlu
                                }
                            },
                            item_list: {
                                items: item_list
                                // shipping_address: {
                                //     recipient_name: "Yuswa",
                                //     line1: "Jln Raya Kerobokan 388x",
                                //     line2: "Unit #34",
                                //     city: "Badung",
                                //     country_code: "ID",
                                //     postal_code: "80361",
                                //     phone: "081290792903",
                                //     state: "Bali"
                                // }
                            }
                        }]
                    }
                })
            },

            onAuthorize: function (success, actions) {
                return actions.payment.execute().then(function () {
                    showLoading();
                    
                    jQuery.ajax({
                        type: "POST",
                        url: paypalApiMainUrl + "/v1/oauth2/token",
                        headers: {
                            "Authorization": "Basic " + btoa(paypalTokenSandboxClientID + ":" + paypalTokenSandboxSecret),
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        data: {grant_type: "client_credentials"},
                        dataType: "json",
                        success: function (token) {
                            jQuery.ajax({
                                type: "GET",
                                url: paypalApiMainUrl + "/v1/payments/payment/" + success.paymentID,
                                headers: {
                                    "Content-Type": "application/json",
                                    "Authorization": "Bearer " + token.access_token
                                },
                                success: function (payment) {
                                    console.log(payment);
                                    
                                    var amount = payment.transactions[0].related_resources[0].sale.amount;
                                    
                                    var transaction_number= jQuery("#transaction_number").val();
                                    var first_name= jQuery("#first_name").val();
                                    var last_name= jQuery("#last_name").val();
                                    var phone_no= jQuery("#phone_no").val();
                                    var zip_code= jQuery("#zip_code").val();
                                    var province= jQuery("#province").val();
                                    var city= jQuery("#city").val();
                                    var address= jQuery("#address").val();
                                    var email= jQuery("#email").val();
                                    var weight= jQuery("#weight").val();
                                    var price_shipping= jQuery("#price_shipping").val();
                                    
                                    $.ajax({
                                        type: "POST",
                                        url: "guest_payment.php",
                                        data: {
                                            payment_paypal: true,
                                            transaction_number: transaction_number,
                                            first_name: first_name,
                                            last_name: last_name,
                                            phone_no: phone_no,
                                            zip_code: zip_code,
                                            province: province,
                                            city: city,
                                            address: address,
                                            email: email,
                                            weight: weight,
                                            price_shipping: price_shipping,
                                            state: payment.transactions[0].related_resources[0].sale.state,
                                            total_paypal: amount.total,
                                            shipping: amount.details.shipping,
                                            description: payment.transactions[0].description,
                                            paymentId: payment.id,
                                        },
                                        dataType: "json",
                                        success: function (data) {
                                            console.log(data.results);
                                        }
                                    });
                                   
                                }
                            });
                        }
                    });

                }).catch(function (err) {
                    window.alert("Payment failed");
                })
            }

        }, "#paypal_button");
        
    </script>';

$template = admin_template($content, $titlebar, $titlepage = "", $user = "", $menu, $plugin);

echo $template;

?>