<?php

/*

 * Project Name: SeaGods

 * Project URI: http://seagodswetsuit.com

 * Author: GlobalXtreme.net

 * Version: 1.0  | 1 Februari 2018

 * Email: adit@globalxtreme.net

 */

include("config/configuration.php");
include("config/currency_types.php");

session_start();
ob_start();

if ($loggedin = logged_inadmin()) { // Check if they are logged in

    // Set price custom item
    function get_price($name)
    {
        $query_setting_price = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = '$name' LIMIT 0,1");
        $row_setting_price = mysql_fetch_array($query_setting_price);
        return $row_setting_price['value'];
    }

    // Set nominal curs from USD to IDR
    $USDtoIDR = get_price('currency-value-usd-to-idr');

    global $conn;
    if (isset($_POST['nilai'])) {
        $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
    } else {
        $_SESSION['nilai_login'] = 0;
    }

    $titlebar = "Detail Product";
    $titlepage = "Detail Product";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    if (isset($_GET["id"])) {
        $query_product = mysql_query("SELECT * FROM `item` WHERE `id_item` = '" . $_GET["id"] . "' LIMIT 0,1;");
        $row_product = mysql_fetch_array($query_product);

        $query_category = mysql_query("SELECT * FROM `category` WHERE `id_cat` = '" . $row_product["id_category"] . "' LIMIT 0,1;");
        $row_category = mysql_fetch_array($query_category);

        $query_sub_category = mysql_query("SELECT * FROM `category` WHERE `id_cat` = '" . $row_product["id_cat"] . "' LIMIT 0,1;");
        $row_sub_category = mysql_fetch_array($query_sub_category);
    }

    $content = '
        <div class="container container-fixed-lg">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-default">
                        <div class="card-header ">
                            <div class="card-title">
                                <h4><b>DETAIL PRODUCT <u>' . (isset($_GET['id']) ? $row_product["title"] : "") . '</u></b></h4>
                            </div>
                                <a href="list_products.php' . (isset($_GET['page']) ? '?page=' . $_GET['page'] : '') . '" class="btn btn-default pull-right" name="">Back to List</a>
                            </div>
                        </div>
                        
                        <div class="card card-default">
                            <div class="card-block">
                                <div class="row">
                                
                                    <div class="col-md-4 detail-container">
                                        <label>Title :</label>
                                        <h5><b>' . (isset($_GET['id']) ? $row_product["title"] : "") . '</b></h5>
                                    </div>
                                    
                                    <div class="col-md-4 detail-container">
                                        <label>Code :</label>
                                        <h5><b>' . (isset($_GET['id']) ? $row_product["code"] : "") . '</b></h5>
                                    </div>
                                    
                                    <div class="col-md-2 detail-container">
                                        <label>Price USD :</label>
                                        <h5><b>$ ' . (isset($_GET['id']) ? $row_product["price"] : "") . '</b></h5>
                                    </div>
                                    
                                    <div class="col-md-2 detail-container">
                                        <label>Price IDR :</label>
                                        <h5><b>Rp ' . (isset($_GET['id']) ? number_format(($row_product["price"] * $USDtoIDR), 0, '.', ',') : "") . '</b></h5>
                                    </div>
                                    
                                </div><br>
                                
                                <div class="row">
                                
                                    <div class="col-md-4 detail-container">
                                        <label>Category :</label>
                                        <h5><b>' . (isset($_GET['id']) ? $row_category["category"] : "") . '</b></h5>
                                    </div>
                                    
                                    <div class="col-md-4 detail-container">
                                        <label>Sub Category :</label>
                                        <h5><b>' . (isset($_GET['id']) ? $row_sub_category["category"] : "") . '</b></h5>
                                    </div>
                                    
                                    <div class="col-md-2 detail-container">
                                        <label>Date Add :</label>
                                        <h5><b>' . (isset($_GET['id']) ? $row_product["date_add"] : "") . '</b></h5>
                                    </div>
                                    
                                    <div class="col-md-2 detail-container">
                                        <label>Date Update :</label>
                                        <h5><b>' . (isset($_GET['id']) ? $row_product["date_upd"] : "") . '</b></h5>
                                    </div>
                                    
                                </div><br>
                                
                                <div class="row">
                                
                                    <div class="col-md-4 detail-container">
                                        <label>Detail :</label>
                                        <h5><b>' . (isset($_GET['id']) ? $row_product["detail"] : "") . '</b></h5>
                                    </div>
                                    
                                    <div class="col-md-8 detail-container">
                                        <label>Description :</label>
                                        <h5><b>' . (isset($_GET['id']) ? $row_product["description"] : "") . '</b></h5>
                                    </div>
                                    
                                </div><br>
                            </div>
                        </div>
                        
                        <div class="card card-default">
                            <div class="card-header ">
                                <div class="card-title">
                                    <label><b>Photo</b></h4>
                                </div>
                            </div>
                            <div class="card-block">
                                <div class="row">';

    $query_photo = mysql_query("SELECT * FROM `photo` WHERE `id_item` = '" . $row_product["id_item"] . "' AND `level` = '0';");

    while ($row_photo = mysql_fetch_array($query_photo)) {

        $content .= '
                                    <div class="col-md-4 no-padding p-r-5">
                                       <div class="form-group form-group-default">
                                           <img style="width: 100%; height: 100%; max-height: 300px;" src="images/product/600/thumb_' . (isset($_GET['id']) ? strip_tags(trim($row_photo["photo"])) : "") . '">
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

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);
    echo $template;

} else {
    header('Location: logout.php');
}

?>