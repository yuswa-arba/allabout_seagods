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

if ($loggedin = logged_inadmin()) { // Check if they are logged in

    $titlebar = "List Transaction";
    $titlepage = "List Transaction";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    if (isset($_GET["id"])) {

        $id_transaction = isset($_GET['id']) ? strip_tags(trim($_GET['id'])) : "";

        $sql_request = mysql_query("SELECT * FROM `custom_request` WHERE `id_custom_request` = '$id_transaction' AND `level` = '0';");
        $row_request = mysql_fetch_array($sql_request);

        $sql_collection = mysql_query("SELECT * FROM `custom_collection` WHERE `id_custom_collection` = '" . $row_request["id_custom_collection"] . "' AND `level` = '0';");
        $row_collection = mysql_fetch_array($sql_collection);

        $sql_measure = mysql_query("SELECT * FROM `custom_measure` WHERE `id_custom_collection` = '" . $row_collection["id_custom_collection"] . "';");
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
                            <div class="card-title">Member Information</div>
        
                        </div>
                        <div class="card-block">
                            <div class="row">
                                <div class="col-md-4">
                                    <div style="" class="cursor">';

    if (file_exists('images/members/' . $row_member["foto"])) {
        $content .= '<img src="images/members/' . $row_member["foto"] . '" alt="No Image" class="img-responsive" style="width:100%; height:auto;" >';
    } else {
        $content .= '<img src="../member/assets/img/social/person-cropped.jpg" alt = "No Image" class="img-responsive" style = "width:100%; height:auto;" >';
    }

    $content .= '
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="col-md-4 show-details detail-container">
        
                                    <label>First Name</label>
                                    <h5><b>' . $row_member["firstname"] . '</b></h5>
        
                                    <label>Email</label>
                                    <h5><b>' . $row_member["email"] . '</b></h5>
        
                                    <label>Address</label>
                                    <h5><b>' . $row_member["alamat"] . '</b></h5>
                                </div>
                                <div class="col-md-4 show-details detail-container">
                                    <label>Last Name</label>
                                    <h5><b>' . $row_member["lastname"] . '</b></h5>
        
                                    <label>Phone Number</label>
                                    <h5><b>' . $row_member["notelp"] . '</b></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-10">
                    <div class="card card-default filter-item">
                        <div class="card-header ">
                            <div class="card-title">Image Request</div>
                        </div>
                        <div class="card-block">
                            <div class="row">
                                <img style="width: 100%;height:500px;" src="../web/custom/public/images/custom_cart/' . $row_collection["image"] . '" alt="">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-default filter-item">
                                <div class="card-header ">
                                    <div class="card-title">Custom Request</div>
                                </div>
                                <div class="card-block">
                                    <div class="col-md-12 show-details">
                                        <label>Code</label>
                                        <h5><b>' . $row_collection["code"] . '</b></h5>
                
                                        <label>Gender</label>
                                        <h5><b>' . $row_collection["gender"] . '</b></h5>
                
                                        <label>Wet suit type</label>
                                        <h5><b>' . $row_collection["wet_suit_type"] . '</b></h5>
                                        
                                        <label>Arm Zipper</label>
                                        <h5><b>' . $row_collection["arm_zipper"] . '</b></h5>
                
                                        <label>Ankle Zipper</label>
                                        <h5><b>' . $row_collection["ankle_zipper"] . '</b></h5>
                
                                        <label>Price</label>
                                        <h5><b>$ ' . $row_collection["price"] . '</b></h5>
                                        
                                        <label>Status</label>
                                        <h5><b>' . $row_collection["status"] . '</b></h5>
                
                                        <label>Date Add</label>
                                        <h5><b>' . $row_collection["date_add"] . '</b></h5>
                
                                        <label>Date Update</label>
                                        <h5><b>' . $row_collection["date_upd"] . '</b></h5>
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
                                    <div class="col-md-12">
                                        <div class=" h-500">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover">
                                                            <thead class="bg-master-lighter">
                                                            <tr>
                                                                <th width="50%">Name</th>
                                                                <th>Size</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr>
                                                                <td>Total Body Height</td>
                                                                <td>
                                                                    <b>'.$row_measure['total_body_height'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Head</td>
                                                                <td>
                                                                    <b>'.$row_measure['head'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Neck</td>
                                                                <td>
                                                                    <b>'.$row_measure['neck'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Bust / Chest</td>
                                                                <td>
                                                                    <b>'.$row_measure['bust_chest'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Waist</td>
                                                                <td>
                                                                    <b>'.$row_measure['waist'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Stomach</td>
                                                                <td>
                                                                    <b>'.$row_measure['stomach'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Abdomen</td>
                                                                <td>
                                                                    <b>'.$row_measure['abdomen'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Hip</td>
                                                                <td>
                                                                    <b>'.$row_measure['hip'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Shoulder</td>
                                                                <td>
                                                                    <b>'.$row_measure['shoulder'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Shoulder to Elbow</td>
                                                                <td>
                                                                    <b>'.$row_measure['shoulder_elbow'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Shoulder to Wrist</td>
                                                                <td>
                                                                    <b>'.$row_measure['shoulder_wrist'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Arm Hole</td>
                                                                <td>
                                                                    <b>'.$row_measure['arm_hole'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Upper Arm</td>
                                                                <td>
                                                                    <b>'.$row_measure['upper_arm'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Bicep</td>
                                                                <td>
                                                                    <b>'.$row_measure['bicep'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Elbow</td>
                                                                <td>
                                                                    <b>'.$row_measure['elbow'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Forarm</td>
                                                                <td>
                                                                    <b>'.$row_measure['forarm'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Wrist</td>
                                                                <td>
                                                                    <b>'.$row_measure['wrist'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Outside Leg Length</td>
                                                                <td>
                                                                    <b>'.$row_measure['outside_leg_length'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Inside Leg Length</td>
                                                                <td>
                                                                    <b>'.$row_measure['inside_leg_length'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Thigh</td>
                                                                <td>
                                                                    <b>'.$row_measure['thigh'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Above Knee</td>
                                                                <td>
                                                                    <b>'.$row_measure['above_knee'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Knee</td>
                                                                <td>
                                                                    <b>'.$row_measure['knee'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Below Knee</td>
                                                                <td>
                                                                    <b>'.$row_measure['below_knee'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Calf</td>
                                                                <td>
                                                                    <b>'.$row_measure['calf'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Below Calf</td>
                                                                <td>
                                                                    <b>'.$row_measure['below_calf'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Above Ankle</td>
                                                                <td>
                                                                    <b>'.$row_measure['above_ankle'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Shoulder to Bust</td>
                                                                <td>
                                                                    <b>'.$row_measure['shoulder_burst'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Shoulder to Waist</td>
                                                                <td>
                                                                    <b>'.$row_measure['shoulder_waist'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Shoulder Hip</td>
                                                                <td>
                                                                    <b>'.$row_measure['shoulder_hip'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Hip to Knee Length</td>
                                                                <td>
                                                                    <b>'.$row_measure['hip_knee_length'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Knee to Ankle Length</td>
                                                                <td>
                                                                    <b>'.$row_measure['knee_ankle_length'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Back Shoulder</td>
                                                                <td>
                                                                    <b>'.$row_measure['back_shoulder'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Dorsum</td>
                                                                <td>
                                                                    <b>'.$row_measure['dorsum'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Crotch Point</td>
                                                                <td>
                                                                    <b>'.$row_measure['crotch_paint'].' cm</b>
                                                                </td>
                                                            </tr>
                                                            
                                                            </tbody>
                                                        </table>
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

    $plugin = '<script src="assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/TableTools/js/dataTables.tableTools.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/media/js/dataTables.bootstrap.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-datatable/extensions/Bootstrap/jquery-datatable-bootstrap.js" type="text/javascript"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/datatables.responsive.js"></script>
    <script type="text/javascript" src="assets/plugins/datatables-responsive/js/lodash.min.js"></script>';
    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);
    echo $template;

} else {
    header('Location: logout.php');
}

?>