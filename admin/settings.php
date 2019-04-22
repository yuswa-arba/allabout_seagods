<?php
/*
 * Project Name: SeaGods
 * Project URI: http://seagodswetsuit.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 1 Februari 2018
 * Email: adit@globalxtreme.net
 */
include("config/configuration.php");
include("../web/config/shipping/action_raja_ongkir.php");
include("../web/config/shipping/province_city.php");
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

        $value = '-';

        // Set hometown
        if ($row_setting['name'] == 'hometown') {

            if ($row_setting['name']) {

                // Set parameter
                $parameters = ['id' => $row_setting['value']];

                // Get city
                $get_city = get_city($parameters);

                // Set value
                $value = ((count($get_city->rajaongkir->results) == 1) ? $get_city->rajaongkir->results->city_name : '-');

            } else {
                $value = '-';
            }

        }

        // Set province
        if ($row_setting['name'] == 'province-of-origin') {

            if ($row_setting['name']) {

                // Get province
                $get_province = get_province($row_setting['value']);

                // Set value
                $value = ((count($get_province->rajaongkir->results) == 1) ? $get_province->rajaongkir->results->province : '-');

            } else {
                $value = '-';
            }

        }

        // Set price custom
        if ($row_setting['name'] == 'price-custom-item') {

            // Set value
            $value = '$ ' . number_format($row_setting['value'], 2, '.', ',');

        }

        // Set weight
        if ($row_setting['name'] == 'default-weight-custom-item') {

            // Set value
            $value = $row_setting['value'] . ' Kg';

        }

        // Set currency USD to IDR
        if ($row_setting['name'] == 'currency-value-usd-to-idr') {

            // Set value
            $value = 'Rp ' . number_format($row_setting['value'], 0, '.', ',');

        }

        $content .= '
                                    <form action="" method="post">
                                        <tr>
                                            <td class="v-align-middle">
                                                <p>' . $row_setting['name'] . '</p>
                                            </td>
                                            <td class="v-align-middle">
                                                <p><b>' . $value . '</b></p>
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