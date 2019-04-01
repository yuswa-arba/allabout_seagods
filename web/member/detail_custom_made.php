<?php
/*
 * Project Name: SeaGods
 * Project URI: http://seagodswetsuit.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 1 Februari 2018
 * Email: adit@globalxtreme.net
 */
include("config/configuration.php");
session_start();
ob_start();

if (isset($_POST['nilai'])) {
    $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
} else {
    $_SESSION['nilai_login'] = 0;
}

if($loggedin = logged_in()){ // Check if they are logged in

    $titlebar = "Detail Custom Made";
    $titlepage = "Detail Custom Made";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    if (isset($_GET["id"])) {

        $id_collection = isset($_GET['id']) ? strip_tags(trim($_GET['id'])) : "";

        $sql_collection = mysql_query("SELECT * FROM `custom_collection` WHERE `id_custom_collection` = '$id_collection' AND `level` = '0';");
        $row_collection = mysql_fetch_array($sql_collection);

        $sql_measure = mysql_query("SELECT * FROM `custom_measure` WHERE `id_custom_collection` = '".$row_collection["id_custom_collection"]."';");
        $row_measure = mysql_fetch_array($sql_measure);

        $sql_member = mysql_query("SELECT `member`.*, `users`.`email` FROM `member`, `users` 
            WHERE `member`.`id_member` = `users`.`id_member` 
            AND `member`.`id_member` = '$row_collection[id_member]'");
        $row_member = mysql_fetch_array($sql_member);

    }

    $content = '
        <div class="container container-fixed-lg">
            <div class="row">
                <div class="col-lg-10 m-b-10">
                    <div class="card card-default filter-item">
                        <div class="card-header ">
                            <div class="card-title">Employee Information</div>
        
                        </div>
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-4">
                                    <div style="" class="cursor">';

    if (file_exists('images/members/' . $row_member["foto"])) {
        $content .= '<img src="images/members/'.$row_member["foto"].'" alt="No Image" class="img-responsive" style="width:100%; height:auto;" >';
    } else {
        $content .= '<img src="../member/assets/img/social/person-cropped.jpg" alt = "No Image" class="img-responsive" style = "width:100%; height:auto;" >';
    }

    $content .= '
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-4 show-details">
        
                                    <label>First Name</label>
                                    <h5>' . $row_member["firstname"] . '</h5>
        
                                    <label>Email</label>
                                    <h5>' . $row_member["email"] . '</h5>
        
                                    <label>Address</label>
                                    <h5>' . $row_member["alamat"] . '</h5>
                                </div>
                                <div class="col-md-4 show-details">
                                    <label>Last Name</label>
                                    <h5>' . $row_member["lastname"] . '</h5>
        
                                    <label>Phone Number</label>
                                    <h5>' . $row_member["notelp"] . '</h5>
                                </div>
                            </div>
                        </div>
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
        
                                <label>Price</label>
                                <h5>$ ' . $row_collection["price"] . '</h5>
                                
                                <label>Status</label>
                                <h5>' . $row_collection["status"] . '</h5>
        
                                <label>Date Add</label>
                                <h5>' . $row_collection["date_add"] . '</h5>
        
                                <label>Date Update</label>
                                <h5>' . $row_collection["date_upd"] . '</h5>
        
                                <label>Image</label>
                                <img style="width: 100%" src="../custom/public/images/custom_cart/' . $row_collection["image"] . '">
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
                </div>
            </div>
        </div>';

    $plugin = '     <script src="assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
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