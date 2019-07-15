<?php
/*
 * Project Name: SeaGods
 * Project URI: http://seagodswetsuit.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 1 Februari 2018
 * Email: adit@globalxtreme.net
 */
include("config/configuration.php");
include("config/fungsi_images.php");
include("config/currency_types.php");

session_start();
ob_start();

if ($loggedin = logged_inadmin()) { // Check if they are logged in

    if (isset($_POST['nilai'])) {
        $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
    } else {
        $_SESSION['nilai_login'] = 0;
    }

    $titlebar = 'Edit Size product';
    $titlepage = $titlebar;
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    // Save
    if (isset($_POST['save'])) {

        // Set parameter
        $size = isset($_POST['size']) ? mysql_real_escape_string(trim($_POST['size'])) : '';
        $id_item = isset($_POST['id_item']) ? mysql_real_escape_string(trim($_POST['id_item'])) : '';

        // Check value
        if (empty($size)) {
            echo "<script>
                alert('Missing required parameter: Size');
                window.history.back(-1);
            </script>";
            exit();
        }

        // Set size
        $size = str_replace(" ", "", $size);

        // Update size
        $update_size_query = "UPDATE `item` SET `size` = '$size' WHERE `id_item` = '$id_item';";
        if (!mysql_query($update_size_query)) {
            echo "<script>
                alert('Unable to update size product');
                window.history.back(-1);
            </script>";
            exit();
        }

        // Success
        echo "<script>
            alert('Size product has been updated successfully');
            window.location.href = 'list_products.php" . (isset($_GET['page']) ? ('?page=' . $_GET['page']) : '') . "';
        </script>";
        exit();

    }

    // Check parameter
    if (isset($_GET['id'])) {

        // Set item
        $item_query = mysql_query("SELECT `id_item`, `size` FROM `item` WHERE `id_item` = '" . $_GET['id'] . "' AND `level` = '0' LIMIT 0,1;");
        if (mysql_num_rows($item_query) == 0) {
            echo "<script>
                alert('Product data not found');
                window.history.back(-1);
            </script>";
            exit();
        }
        $row_item = mysql_fetch_assoc($item_query);

    } else {
        echo "<script>
            alert('Missing required parameter: ID');
            window.history.back(-1);
        </script>";
        exit();
    }

    $content = '
        <div class=" container    container-fixed-lg">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-default">
                        <div class="card-header ">
                  	        <div class="row">
                                <div class="card-title col-md-10">
                                    ' . $titlebar . '
                                </div>
                  	        </div>
                        </div>
                        
                        <div class="card-block">
                            <form method="post" name="form_size" role="form" enctype="multipart/form-data">
                                <div class="row">  
                                    <div class="col-md-6 b-r b-dashed b-grey p-r-15">
                                        <div class="form-group form-group-default">
                                            <label>Size Product</label>
                                            <input type="hidden" name="id_item" value="' . $row_item['id_item'] . '">
                                            <input type="text" class="form-control" name="size" value="' . str_replace(',', ', ', $row_item['size']) . '" placeholder="Ex: L,M,S">
                                        </div>
                                        <button type="submit" name="save" class="btn btn-primary pull-right">Save</button>
                                        <a href="list_products.php' . (isset($_GET['page']) ? ('?page=' . $_GET['page']) : '') . '" class="btn btn-default pull-right">Back to List</a>
                                    </div>
                                </div>
                            </form>
				        </div>
                    </div>
				</div>
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
	
    <!-- upload -->
    <script language="javascript">
        jq = $.noConflict();
        jq(document).ready(function(){
            fields = 0;
            jq(\' . add_more\').click(function(e){
                e.preventDefault();
            
            if (fields != 4) {
                jq(".textADD").append(\'Gambar : <input type = "file" value = "" name = "fileimage[]" style = "height: 31px;" /> <br />\');
                fields += 1;
            } else {
                jq(".textADD").append(\' < br />Hanya 4 upload fields yang diijinkan . \');
                document.product.add_more.disabled= true;
            }
            });
        });
                
        /*Confirm before delete*/
        var elems = document.getElementsByClassName(\'confirmationDelete\');
        var confirmIt = function (e) {
            if (!confirm(\'Are you sure want to Remove ? \')) e.preventDefault();
        };
        for (var i = 0, l = elems.length; i < l; i++) {
            elems[i].addEventListener(\'click\', confirmIt, false);
        }
        
        jq("#price").keyup(function() {
            
            var price = jq("#price").val();
            var usd_to_idr = jq("#usd_to_idr").val();
            
            var price_usd = (price * usd_to_idr);
            
            jq("#price_idr").val(price_usd);
        });
        
	</script>
    <!-- End upload -->';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);
    echo $template;

} else {
    header('Location: logout.php');
}

?>