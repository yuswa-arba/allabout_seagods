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

    $titlebar = "List Setting";
    $titlepage = "List Setting";
    $menu = "";
    $user = '' . $loggedin['username'];

    $content = '
        <div class="page-container ">
            
            <!-- START PAGE CONTENT WRAPPER -->
            <div class="page-content-wrapper ">
                
                <!-- START PAGE CONTENT -->
                <div class="content ">
                    <div class="container container-fixed-lg">
                        
                        <div class="card-block">
                            <table class="table table-hover demo-table-dynamic table-responsive-block" >
                                <thead>
                                    <tr>
                                        <th style="width:20%">NAME</th>
                                        <th style="width:20%">VALUE</th> 
                                        <th style="width:40%">DESCRIPTION</th>
                                        <th style="width:10%"></th> 
                                    </tr>
                                </thead>
                                <tbody>';

    // Query Select blog
    $setting_query = mysql_query("SELECT * FROM `setting_seagods` ORDER BY `id` DESC;");

    while ($row_setting = mysql_fetch_array($setting_query)) {

        $content .= '
                                    <form action="" method="post">
                                        <tr>
                                            <td class="v-align-middle">
                                                <p>' . $row_setting['name'] . '</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p>' . $row_setting['value'] . '</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p>' . $row_setting['description'] . '</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <div class="btn-group">
                                                    <a href="form_setting.php?id=' . $row_setting["id"] . '" class="btn btn-warning"><i class="fa fa-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    </form>';
    }

    $content .= '
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END card -->
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