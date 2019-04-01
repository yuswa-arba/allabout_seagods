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

if ($loggedin = logged_inadmin()) { // Check if they are logged in

    global $conn;

    if (isset($_POST['nilai'])) {
        $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
    } else {
        $_SESSION['nilai_login'] = 0;
    }

    $loggedin = logged_inadmin();
    $titlebar = "Detail Member";
    $titlepage = "Detail member";
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    if (isset($_GET["id"])) {

        $id_member = isset($_GET['id']) ? strip_tags(trim($_GET['id'])) : "";
        $query = "SELECT `member`.*, `users`.* FROM `member`,`users` WHERE `users`.`id_member` = `member`.`id_member` AND  `member`.`level` = '0' AND `member`.`id_member` = '$id_member' ORDER BY `member`.`id_member` DESC  ;";
        $id = mysql_query($query);
        $data_member = mysql_fetch_array($id);

    }

    $content = '
        <div class="container container-fixed-lg">
            <div class="row">
                <div class="col-lg-10">
                    <div class="card card-default">
                        <div class="card-header ">
                            <div class="card-title">
                                <h4><b>DETAIL MEMBER <u>' . (isset($_GET['id']) ? $data_member["firstname"] . " " . $data_member["lastname"] : "") . '</u></b></h4>
                            </div>
                                <a href="list_member.php" class="btn btn-outline-primary pull-right" name="">Back to List</a>
                        </div>
                    </div>
                    
                    <div class="card card-default">
                        <div class="card-block">
                            <div class="row">
                                    <div class="col-md-4 detail-container">
                                        <label>Firs Name </label>
                                        <h5><b>' . (isset($_GET['id']) ? $data_member["firstname"] : "") . '</b></h5>
                                    
                                        <label>Last Name</label>
                                        <h5><b>' . (isset($_GET['id']) ? $data_member["lastname"] : "") . '</b></h5>
                                    </div>
                                    
                                    <div class="col-md-4 detail-container">
                                        <label>Username</label>
                                        <h5><b>' . (isset($_GET['id']) ? $data_member["username"] : "") . '</b></h5>
                                        
                                        <label>E-mail</label>
                                        <h5><b>' . (isset($_GET['id']) ? $data_member["email"] : "") . '</b></h5>
                                    </div>
                                    
                                    <div class="col-md-4 detail-container">
                                        <label>No Telephone</label>
                                        <h5><b>' . (isset($_GET['id']) ? $data_member["notelp"] : "") . '</b></h5>
                                    
                                        <label>Postal Code</label>
                                        <h5><b>' . (isset($_GET['id']) ? $data_member["kode_pos"] : "") . '</b></h5>
                                    </div>
                                </div><br>
                                
                                <div class="row">
                                    <div class="col-md-4 detail-container">
                                        <label>Address</label>
                                        <h5><b>' . (isset($_GET['id']) ? $data_member["alamat"] : "") . '</b></h5>
                                    </div>
                                    <div class="col-md-8 detail-container">
                                        <label>Photo</label>
                                        <div class="row">
                                            <div class="col-md-12">
                                            <div style="max-width: 400px">
                                            ' . (isset($_GET['id']) ?
            (file_exists('images/members/' . $data_member['foto']) ?
                '<img style="width: 95%; height: 100%;" src="images/members/' . $data_member["foto"] . '"/>' : "<h5><b>Image does not exists</b></h5>") : "") . '
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

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);

    echo $template;
} else {
    header('Location: logout.php');
}

?>