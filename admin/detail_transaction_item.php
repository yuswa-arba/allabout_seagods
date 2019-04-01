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

    $titlebar = "Transaction Detail Item";
    $titlepage = "Transaction Detail Item";
    $menu = "";
    $user = '' . $loggedin['username'] . '';

    if (isset($_GET["id_transaction"]) && isset($_GET['id_cart'])) {

        // Set value request
        $id_transaction = isset($_GET['id_transaction']) ? strip_tags(trim($_GET['id_transaction'])) : "";
        $id_cart = isset($_GET['id_cart']) ? strip_tags(trim($_GET['id_cart'])) : "";

        // Set Transaction
        $query_transaction = mysql_query("SELECT * FROM `transaction` WHERE `id_transaction` = '$id_transaction' LIMIT 0,1;");
        if (mysql_num_rows($query_transaction) == 0) {
            echo "<script>
                alert('Transaction not found');
                window.history.back(-1);
            </script>";
            exit();
        }
        $row_transaction = mysql_fetch_array($query_transaction);


        // Set Card
        $query_card = mysql_query("SELECT * FROM `cart` WHERE `id_cart` = '$id_cart' LIMIT 0,1;");
        if (mysql_num_rows($query_card) == 0) {
            echo "<script>
                alert('Cart not found');
                window.history.back(-1);
            </script>";
            exit();
        }
        $row_cart = mysql_fetch_array($query_card);

    } else {
        echo "<script>
            alert('Parameter can\'t empty.!!');
            window.history.back(-1);
        </script>";
        exit();
    }

    $content = '
        <div class="container container-fixed-lg">
            <div class="row">';

    if ($row_cart['is_custom_cart']) {

        $sql_collection = mysql_query("SELECT * FROM `custom_collection` WHERE `id_custom_collection` = '".$row_cart["id_item"]."' AND `level` = '0';");
        $row_collection = mysql_fetch_array($sql_collection);

        // Set price
        $price = get_price('price-custom-item');

        $sql_measure = mysql_query("SELECT * FROM `custom_measure` WHERE `id_custom_collection` = '" . $row_collection["id_custom_collection"] . "';");
        $row_measure = mysql_fetch_array($sql_measure);

        $content .= '   
                <div class="card card-default">
                    <div class="card-header ">
                        <div class="card-title">
                            <h4><b>Custom Wetsuit</b></h4>
                        </div>
                        <a href="detail_transaction.php?id_transaction='.$row_transaction['id_transaction'].'" class="btn btn-default pull-right" name="">Back to Transaction</a>
                    </div>
                </div>
                         
                <div class="col-md-6">
                    <div class="card card-default filter-item">
                        <div class="card-header ">
                            <div class="card-title">Custom Request</div>
                        </div>
                        <div class="card-block">
                            <div class="col-md-12 show-details">
                                <label>Code</label>
                                <h5>' . $row_collection["code"] . '</h5>
        
                                <label>Gender</label>
                                <h5>' . $row_collection["gender"] . '</h5>
        
                                <label>Wet suit type</label>
                                <h5>' . $row_collection["wet_suit_type"] . '</h5>
                                
                                <label>Arm Zipper</label>
                                <h5>' . $row_collection["arm_zipper"] . '</h5>
        
                                <label>Ankle Zipper</label>
                                <h5>' . $row_collection["ankle_zipper"] . '</h5>
        
                                <label>Old Price</label>
                                <h5>$ ' . $row_collection["price"] . '</h5>
                                
                                <label>Current Price</label>
                                <h5>$ ' . $price . '</h5>
                                
                                <label>Date Add</label>
                                <h5>' . $row_collection["date_add"] . '</h5>
        
                                <label>Date Update</label>
                                <h5>' . $row_collection["date_upd"] . '</h5>
        
                                <label>Image</label>
                                <img style="width: 100%" src="../web/custom/public/images/custom_cart/' . $row_collection["image"] . '">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-default filter-item">
                        <div class="card-header ">
                            <div class="card-title">Measure</div>
                        </div>
                        <div class="card-block">
                             <div class="table-responsive" style="overflow-y: scroll; height: 670px;">
                                 <table class="table table-striped">
                                     <tbody>
                                         <tr>
                                             <td style="width: 82%;">Total Body Height</td>
                                             <td style="width: 18%;">' . $row_measure["total_body_height"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Head</td>
                                             <td style="width: 18%;">' . $row_measure["head"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Neck</td>
                                             <td style="width: 18%;">' . $row_measure["neck"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Bust/Chest</td>
                                             <td style="width: 18%;">' . $row_measure["bust_chest"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Waist</td>
                                             <td style="width: 18%;">' . $row_measure["waist"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Stomach</td>
                                             <td style="width: 18%;">' . $row_measure["stomach"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Abdomen</td>
                                             <td style="width: 18%;">' . $row_measure["abdomen"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Hip</td>
                                             <td style="width: 18%;">' . $row_measure["hip"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Shoulder</td>
                                             <td style="width: 18%;">' . $row_measure["shoulder"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Shoulder to Elbow</td>
                                             <td style="width: 18%;">' . $row_measure["shoulder_elbow"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Shoulder to Wrist</td>
                                             <td style="width: 18%;">' . $row_measure["shoulder_wrist"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Arm Hole</td>
                                             <td style="width: 18%;">' . $row_measure["arm_hole"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Upper Arm</td>
                                             <td style="width: 18%;">' . $row_measure["upper_arm"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Bicep</td>
                                             <td style="width: 18%;">' . $row_measure["bicep"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Elbow</td>
                                             <td style="width: 18%;">' . $row_measure["elbow"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Forarm</td>
                                             <td style="width: 18%;">' . $row_measure["forarm"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Wrist</td>
                                             <td style="width: 18%;">' . $row_measure["wrist"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Outside Leg Length</td>
                                             <td style="width: 18%;">' . $row_measure["outside_leg_length"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Inside Leg Length</td>
                                             <td style="width: 18%;">' . $row_measure["inside_leg_length"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Upper Thigh</td>
                                             <td style="width: 18%;">' . $row_measure["upper_thigh"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Thigh</td>
                                             <td style="width: 18%;">' . $row_measure["thigh"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Above Knee</td>
                                             <td style="width: 18%;">' . $row_measure["above_knee"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Knee</td>
                                             <td style="width: 18%;">' . $row_measure["knee"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Below Knee</td>
                                             <td style="width: 18%;">' . $row_measure["below_knee"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Calf</td>
                                             <td style="width: 18%;">' . $row_measure["calf"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Bellow Calf</td>
                                             <td style="width: 18%;">' . $row_measure["below_calf"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Above Ankle</td>
                                             <td style="width: 18%;">' . $row_measure["above_ankle"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Shoulder to Burst</td>
                                             <td style="width: 18%;">' . $row_measure["shoulder_burst"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Shoulder to Waist</td>
                                             <td style="width: 18%;">' . $row_measure["shoulder_waist"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Shoulder to Hip</td>
                                             <td style="width: 18%;">' . $row_measure["shoulder_hip"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Hip to Knee Length</td>
                                             <td style="width: 18%;">' . $row_measure["hip_knee_length"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Knee to Ankle Length</td>
                                             <td style="width: 18%;">' . $row_measure["knee_ankle_length"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Back Shoulder</td>
                                             <td style="width: 18%;">' . $row_measure["back_shoulder"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Dorsum</td>
                                             <td style="width: 18%;">' . $row_measure["dorsum"] . ' cm</td>
                                         </tr>
                                         <tr>
                                             <td style="width: 82%;">Croth Point</td>
                                             <td style="width: 18%;">' . $row_measure["crotch_point"] . ' cm</td>
                                         </tr>
                                     </tbody>
                                 </table>
                             </div>
                        </div>
                    </div>
                </div>';

    } else {

        $query_product = mysql_query("SELECT * FROM `item` WHERE `id_item` = '" . $row_cart["id_item"] . "' LIMIT 0,1;");
        $row_product = mysql_fetch_array($query_product);

        $query_category = mysql_query("SELECT * FROM `category` WHERE `id_cat` = '" . $row_product["id_category"] . "' LIMIT 0,1;");
        $row_category = mysql_fetch_array($query_category);

        $query_sub_category = mysql_query("SELECT * FROM `category` WHERE `id_cat` = '" . $row_product["id_cat"] . "' LIMIT 0,1;");
        $row_sub_category = mysql_fetch_array($query_sub_category);

        // Set nominal curs from USD to IDR
        $USDtoIDR = get_price('currency-value-usd-to-idr');

        $content .= '
                <div class="card card-default">
                    <div class="card-header ">
                        <div class="card-title">
                            <h4><b>'.$row_product["title"].'</b></h4>
                        </div>
                        <a href="detail_transaction.php?id_transaction='.$row_transaction['id_transaction'].'" class="btn btn-default pull-right" name="">Back to Transaction</a>
                    </div>
                </div>
                        
                <div class="card card-default">
                    <div class="card-block">
                        <div class="row">
                        
                            <div class="col-md-4 detail-container">
                                <label>Title :</label>
                                <h5><b>' . $row_product["title"] . '</b></h5>
                            </div>
                            
                            <div class="col-md-4 detail-container">
                                <label>Code :</label>
                                <h5><b>' . $row_product["code"] . '</b></h5>
                            </div>
                            
                            <div class="col-md-2 detail-container">
                                <label>Price USD :</label>
                                <h5><b>$ ' . $row_product["price"] . '</b></h5>
                            </div>
                            
                            <div class="col-md-2 detail-container">
                                <label>Price IDR :</label>
                                <h5><b>Rp ' . number_format(($row_product["price"] * $USDtoIDR), 0, '.', ',') . '</b></h5>
                            </div>
                            
                        </div><br>
                        
                        <div class="row">
                        
                            <div class="col-md-4 detail-container">
                                <label>Category :</label>
                                <h5><b>' . $row_category["category"] . '</b></h5>
                            </div>
                            
                            <div class="col-md-4 detail-container">
                                <label>Sub Category :</label>
                                <h5><b>' . $row_sub_category["category"] . '</b></h5>
                            </div>
                            
                            <div class="col-md-2 detail-container">
                                <label>Date Add :</label>
                                <h5><b>' . $row_product["date_add"] . '</b></h5>
                            </div>
                            
                            <div class="col-md-2 detail-container">
                                <label>Date Update :</label>
                                <h5><b>' . $row_product["date_upd"] . '</b></h5>
                            </div>
                            
                        </div><br>
                        
                        <div class="row">
                        
                            <div class="col-md-4 detail-container">
                                <label>Detail :</label>
                                <h5><b>' . $row_product["detail"] . '</b></h5>
                            </div>
                            
                            <div class="col-md-8 detail-container">
                                <label>Description :</label>
                                <h5><b>' . $row_product["description"] . '</b></h5>
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
                                           <img style="width: 100%; height: 100%; max-height: 300px;" src="images/product/600/thumb_' . strip_tags(trim($row_photo["photo"])) . '">
                                       </div>
                                    </div>';

        }

    $content .= '
                             </div>
                        </div>';
    }

    $content .= '
            </div>
        </div>';

    $plugin = '     
    <script src="assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/lodash.min.js"></script>
	    <!-- BEGIN PAGE LEVEL JS -->
    <script src="assets/js/datatables.js" type="text/javascript"></script>
    <script src="assets/js/scripts.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL JS -->';
    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);
    echo $template;

} else {
    header('Location: logout.php');
}

?>