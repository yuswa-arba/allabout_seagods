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

    // Set price custom item
    function get_price($name)
    {
        $query_setting_price = mysql_query("SELECT `value` FROM `setting_seagods` WHERE `name` = '$name' LIMIT 0,1");
        $row_setting_price = mysql_fetch_array($query_setting_price);
        return $row_setting_price['value'];
    }

    // Set nominal curs from USD to IDR
    $USDtoIDR = get_price('currency-value-usd-to-idr');

    if (isset($_POST['nilai'])) {
        $_SESSION['nilai_login'] = $_POST['nilai'] + 1;
    } else {
        $_SESSION['nilai_login'] = 0;
    }

    $loggedin = logged_inadmin();
    $titlebar = (isset($_GET['id']) ? "Edit Procut" : "Add Product");
    $titlepage = $titlebar;
    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    $folderTujuan = 'images/product/'; //folder dimana file akan di letakkan
    $folder1 = 'images/product/600/'; //folder dimana file 150px akan di letakkan
    $folder2 = 'images/product/150/'; //folder dimana file 600px akan di letakkan
    $lebarGambar1 = 600; //ukuran lebar gambar 600px hasil resize
    $lebarGambar2 = 150; //ukuran lebar gambar 150px resize

    $simpan = isset($_POST["simpan"]) ? $_POST["simpan"] : '';
    $update = isset($_POST["update"]) ? $_POST["update"] : '';

    function randomString($length = 5)
    {
        $str = "";
        $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    // Set default file
    $file = array();

    // Action save
    if ($simpan == "Simpan") {

        // Start transaction
        begin_transaction();

        // Set value request
        $title = isset($_POST['title']) ? strip_tags(trim($_POST["title"])) : "";
        $category = explode('-', isset($_POST['id_cat']) ? strip_tags(trim($_POST["id_cat"])) : "");
        $price = isset($_POST['price']) ? strip_tags(trim($_POST["price"])) : "";
        $detail = isset($_POST['detail']) ? $_POST["detail"] : "";
        $describe = isset($_POST['describe']) ? $_POST["describe"] : "";
        $code = generate_item_number();

        // Set value id category and sub category
        $id_cat = $category[0];
        $id_category = $category[1];

        // Create Item
        $queryproduct = "INSERT INTO `item` (`id_item`, `code`, `title`, `id_category` , `id_cat`, `price`, `detail`, `description`, `date_add`, `date_upd`, `level`) 
		    VALUES (NULL, '$code', '$title', '$id_cat' ,'$id_category', '$price', '$detail', '$describe', NOW(), NOW(), '0');";

        // Is Error
        if (!mysql_query($queryproduct)) {
            roll_back();
            echo "<script language='JavaScript'>
                alert('Tidak menyimpan data product, ada kesalahan!');
                window.history.go(-1);
            </script>";
            exit();
        }

        // Set tmp name file
        $tmpName = $_FILES['fileimage']['tmp_name'];

        for ($i = 0; $i < count($tmpName); $i++) {

            $query_last = mysql_query("SELECT * FROM `item` ORDER BY `id_item` DESC LIMIT 0,1");
            $last_data = mysql_fetch_array($query_last);
            $last_id_item = $last_data["id_item"];


            if ($tmpName[$i] != "") {

                // Set memory limit in php.ini
                ini_set('memory_limit', '120M');

                $foto = $_FILES['fileimage']['name'][$i];
                $fotoTmp = $_FILES['fileimage']['tmp_name'][$i];

                $namaFileTujuan = namaFileTujuanProduk($foto, $folderTujuan); //cek file tujuan upload,
                $file[] = $namaFileTujuan;
                $pathFileTujuan = $folderTujuan . $namaFileTujuan; //membuat alamat file tujuan
                $prosesUpload = prosesUploadProduk($fotoTmp, $pathFileTujuan); //proses copy image to folder staff
                $prosesResized1 = resizeGambar1($pathFileTujuan, $lebarGambar1, $folder1, $namaFileTujuan, $prefix); //proses resize
                $prosesResized2 = resizeGambar2($pathFileTujuan, $lebarGambar2, $folder2, $namaFileTujuan, $prefix); //proses resize

                // Save photo
                $query2 = "INSERT INTO `photo`(id_photo, id_item, photo, date_add, date_upd, level)
		            VALUES ('','$last_id_item','$namaFileTujuan', now(),now(),'0')";

                // If error
                if (!mysql_query($query2)) {
                    roll_back();
                    echo "<script language='JavaScript'>
                        alert('Maaf Photo tidak dapat disimpan ke dalam database.!');
                        window.history.go(-1);
                    </script>";
                    exit();
                }

            }
        }

        // Commit
        commit();

        // Success
        echo "<script language='JavaScript'>
			alert('Data telah disimpan!');
			location.href = 'list_products.php';
        </script>";
        exit();
    }


    // Action update
    if ($update == "Update") {

        // Start transaction
        begin_transaction();

        // Set value request
        $id_items = isset($_POST['id_item']) ? strip_tags(trim($_POST["id_item"])) : "";
        $title = isset($_POST['title']) ? strip_tags(trim($_POST["title"])) : "";
        $category = explode('-', isset($_POST['id_cat']) ? strip_tags(trim($_POST["id_cat"])) : "");
        $price = isset($_POST['price']) ? strip_tags(trim($_POST["price"])) : "";
        $detail = isset($_POST['detail']) ? $_POST["detail"] : "";
        $describe = isset($_POST['describe']) ? $_POST["describe"] : "";
        $id_photo = isset($_POST['id_photo']) ? $_POST["id_photo"] : "";

        // Set value id category and sub category
        $id_cat = $category[0];
        $id_category = $category[1];

        $queryproduct = "UPDATE `item` SET `title` = '$title', `id_category` = '$id_cat' ,
	    											  `id_cat` = '$id_category',  `price` = '$price',
													  `detail` = '$detail', `description` = '$describe',
													  `date_upd` = NOW()
												  WHERE `item`.`id_item` = '$id_items';";

        // Is Error
        if (!mysql_query($queryproduct)) {
            roll_back();
            echo "<script language='JavaScript'>
                alert('Tidak dapat update product, Ada kesalahan!');
                window.history.go(-1);
            </script>";
            exit();
        }

        // Insert photo
        $tmpNameNew = [];
        if (isset($_FILES['fileimage']['tmp_name'])) {
            $tmpNameNew = $_FILES['fileimage']['tmp_name'];
        }

        for ($i = 0; $i < count($tmpNameNew); $i++) {

            if ($tmpNameNew[$i] != "") {

                // Set memory limit in php.ini
                ini_set('memory_limit', '120M');

                $foto = $_FILES['fileimage']['name'][$i];
                $fotoTmp = $_FILES['fileimage']['tmp_name'][$i];

                $namaFileTujuan = namaFileTujuanProduk($foto, $folderTujuan); //cek file tujuan upload,
                $file[] = $namaFileTujuan;
                $pathFileTujuan = $folderTujuan . $namaFileTujuan; //membuat alamat file tujuan
                $prosesUpload = prosesUploadProduk($fotoTmp, $pathFileTujuan); //proses copy image to folder staff
                $prosesResized1 = resizeGambar1($pathFileTujuan, $lebarGambar1, $folder1, $namaFileTujuan, $prefix); //proses resize
                $prosesResized2 = resizeGambar2($pathFileTujuan, $lebarGambar2, $folder2, $namaFileTujuan, $prefix); //proses resize

                $query_insert_photo = "INSERT INTO `photo`(id_photo, id_item, photo, date_add, date_upd, level)
		            VALUES ('','$id_items','$namaFileTujuan', now(),now(),'0')";

                // Is Error
                if (!mysql_query($query_insert_photo)) {
                    roll_back();
                    echo "<script language='JavaScript'>
                        alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
                        window.history.go(-1);
                    </script>";
                    exit();
                }

            }
        }

        // Update photo
        $tmpNameUpdate = [];
        if (isset($_FILES['fileimageupdate']['tmp_name'])) {
            $tmpNameUpdate = $_FILES['fileimageupdate']['tmp_name'];
        }

        for ($i = 0; $i < count($tmpNameUpdate); $i++) {

            if ($tmpNameUpdate[$i] != '') {

                // Set memory limit in php.ini
                ini_set('memory_limit', '120M');

                $query_photo = mysql_query("SELECT `photo` FROM `photo` WHERE `id_photo` = '" . $id_photo[$i] . "';");
                $row_photo = mysql_fetch_array($query_photo);

                $foto = $_FILES['fileimageupdate']['name'][$i];
                $fotoTmp = $_FILES['fileimageupdate']['tmp_name'][$i];

                $namaFileTujuan = namaFileTujuanProduk($foto, $folderTujuan); //cek file tujuan upload,
                $file[] = $namaFileTujuan;
                $pathFileTujuan = $folderTujuan . $namaFileTujuan; //membuat alamat file tujuan
                $prosesUpload = prosesUploadProduk($fotoTmp, $pathFileTujuan); //proses copy image to folder staff
                $prosesResized1 = resizeGambar1($pathFileTujuan, $lebarGambar1, $folder1, $namaFileTujuan, $prefix); //proses resize
                $prosesResized2 = resizeGambar2($pathFileTujuan, $lebarGambar2, $folder2, $namaFileTujuan, $prefix); //proses resize

                $query_update_photo = "UPDATE `photo` SET `photo` = '$namaFileTujuan', `date_upd` = NOW() WHERE `id_photo` = '" . $id_photo[$i] . "'";

                // Is Error
                if (!mysql_query($query_update_photo)) {
                    roll_back();
                    echo "<script language='JavaScript'>
                        alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
                        window.history.go(-1);
                    </script>";
                    exit();
                }

                unlink($folderTujuan . $row_photo['photo']);
                unlink($folder1 . 'thumb_' . $row_photo['photo']);
                unlink($folder2 . 'thumb_' . $row_photo['photo']);
            }
        }

        // Commit
        commit();

        echo "<script language='JavaScript'>
			alert('Data telah disimpan!');
			location.href = 'list_products.php" . (isset($_GET['page']) ? '?page=' . $_GET['page'] : '') . "';
        </script>";
        exit();
    }


    // Action for delete photo
    if (isset($_GET["id_photo"])) {

        // Set id photo
        $id_photo = isset($_GET['id_photo']) ? strip_tags(trim($_GET['id_photo'])) : "";

        // Get photo
        $query_photo = mysql_query("SELECT `photo`.* from `photo` where `id_photo` = '$id_photo';");
        $row_photo = mysql_fetch_array($query_photo);

        // Delete photo
        $del = "UPDATE `photo` set `level` = '1' where `id_photo` = '$id_photo';";
        if (!mysql_query($del)) {
            echo "<script language='JavaScript'>
                alert('Maaf Data tidak bisa dihapus di dalam Database, Ada kesalahan!');
                window.history.go(-1);
            </script>";
            exit();
        }

        // Delete file photo
        unlink($folderTujuan . $row_photo['photo']);
        unlink($folder1 . 'thumb_' . $row_photo['photo']);
        unlink($folder2 . 'thumb_' . $row_photo['photo']);

        echo "<script language='JavaScript'>
			alert('Photo telah dihapus!');
			window.history.go(-1);
        </script>";
        exit();
    }


    // Action for get detail prodict
    if (isset($_GET["id"])) {

        // Set id item
        $id_item = isset($_GET['id']) ? strip_tags(trim($_GET['id'])) : "";

        // get item
        $query = mysql_query("SELECT `item`.* FROM `item` WHERE `item`.`level` = '0' AND `item`.`id_item` = '$id_item'  ORDER BY `item`.`id_item` DESC ;");
        if (mysql_num_rows($query) == 0) {
            echo "<script language='JavaScript'>
                alert('Product data not found');
                window.history.go(-1);
            </script>";
            exit();
        }
        $data_item = mysql_fetch_array($query);

        // Set value price IDR
        $priceIDR = ($data_item['price'] * $USDtoIDR);
    }

    $content = '
        <div class=" container    container-fixed-lg">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-default">
                        <div class="card-header ">
                  	        <div class="row">
                                <div class="card-title col-md-6">
                                    ' . $titlebar . '				
                                </div>
                                <div class="card-title col-md-6 p-l-15">
                                    LIST IMAGES					
                                </div>
                  	        </div>
                        </div>
                        
                        <div class="card-block">
                            <form class="" method="post" name="product" id="product" role="form" enctype="multipart/form-data">
                                <div class="row">  
                                    <div class="col-md-6 b-r b-dashed b-grey p-r-15">';

    $content .= '
                                        <div class="form-group form-group-default required ">
                                            <label>Product Name</label>
                                            <input type="text" class="form-control" name="title" value="' . (isset($_GET['id']) ? strip_tags(trim($data_item["title"])) : "") . '" required>
                                            <input type="hidden" class="form-control" name="id_item" value="' . (isset($_GET['id']) ? $_GET['id'] : "") . '">
                                            <input type="hidden" id="usd_to_idr" value="' . $USDtoIDR . '">
                                        </div>
                                        
                                        <div class="form-group form-group-default form-group-default-select2 required">
                                            <label class="">Category</label>
                                            <select class="full-width" data-placeholder="Select Category" name="id_cat" data-init-plugin="select2">';

    $sql_caregory = mysql_query("SELECT * FROM `category` WHERE `id_parent` = '0' AND `LEVEL` = '0' ORDER BY `id_cat` DESC  ;");

    while ($row_category = mysql_fetch_array($sql_caregory)) {

        $content .= '<optgroup label="' . $row_category["category"] . '">';
        $sql_subcaregory = mysql_query("SELECT * FROM `category` WHERE `id_parent` = '$row_category[id_cat]' AND `LEVEL` = '0' ORDER BY `id_cat` DESC  ;");

        while ($row_sucategory = mysql_fetch_array($sql_subcaregory)) {
            $content .= '<option value="' . $row_category["id_cat"] . "-" . $row_sucategory["id_cat"] . '" ' . (isset($_GET['id']) && $data_item["id_cat"] == $row_sucategory["id_cat"] ? "selected" : "") . '>' . $row_sucategory["category"] . '</option>';
        }
        $content .= '</optgroup>';
    }

    $content .= '    
                                            </select>
                                        </div>
                                        
                                        <div class="form-group form-group-default required ">
                                            <label>Price</label>
                                            <input type="text" id ="price" name="price" value="' . (isset($_GET['id']) ? strip_tags(trim($data_item["price"])) : "") . '" class="form-control" required>
                                        </div>
                                        
                                        <div class="form-group form-group-default required ">
                                            <label>Price IDR</label>
                                            <input type="number" id="price_idr" name="price_idr" value="' . (isset($_GET['id']) ? strip_tags(trim($priceIDR)) : "") . '" class="form-control" required disabled>
                                        </div>
                                        
                                        <div class="form-group form-group-default ">
                                            <label>Upload Gambar</label><br/>';

    if (isset($_GET["id"])) {

        $count_photo = mysql_num_rows(mysql_query("SELECT * FROM `photo` WHERE `id_item` = '" . $_GET["id"] . "' AND `level` = '0';"));
        $space_available = 8 - $count_photo;

        for ($i = 0; $i < $space_available; $i++) {
            $content .= '<input type="file" value="" name="fileimage[]" style="height: 31px;" /> <br />';
        }

    } else {

        $content .= '
                                            <input type="file" value="" name="fileimage[]" style="height: 31px;" /> <br />
                                            <input type="file" value="" name="fileimage[]" style="height: 31px;" /> <br />
                                            <input type="file" value="" name="fileimage[]" style="height: 31px;" /> <br />
                                            <input type="file" value="" name="fileimage[]" style="height: 31px;" /> <br />
                                            <input type="file" value="" name="fileimage[]" style="height: 31px;" /> <br />
                                            <input type="file" value="" name="fileimage[]" style="height: 31px;" /> <br />
                                            <input type="file" value="" name="fileimage[]" style="height: 31px;" /> <br />
                                            <input type="file" value="" name="fileimage[]" style="height: 31px;" /> <br />';
    }

    $content .= '                        
                                            </fieldset> 
                                        </div>
                                        
                                        <div class="form-group form-group-default required ">
                                           <label>Detail</label>
                                           <textarea class="form-control" name="detail" required style="height:100px">' . (isset($_GET['id']) ? strip_tags(trim($data_item["detail"])) : "") . '</textarea>
                                        </div>
                                        
                                        <div class="form-group form-group-default required ">
                                            <label>Description</label>
                                            <textarea class="form-control" required name="describe" style="height:200px"> ' . (isset($_GET['id']) ? strip_tags(trim($data_item["description"])) : "") . '</textarea>
                                        </div>
                                        
                                        <button class="btn btn-primary pull-right" 
                                            type="submit" name="' . (isset($_GET['id']) ? "update" : "simpan") . '"
                                            value="' . (isset($_GET['id']) ? "Update" : "Simpan") . '">' . (isset($_GET['id']) ? "Update" : "Save") . ' <i class="fa fa-save m-l-5"></i></button>
                                            
                                        <a href="list_products.php' . (isset($_GET['page']) ? '?page=' . $_GET['page'] : '') . '" class="btn btn-outline-primary pull-right m-r-10">Back to list</a>
                                    </div>
                                    
                                    <div class="col-md-6 p-l-20">
                                        <div class="row">';

    if (isset($_GET["id"])) {

        $photo = mysql_query("SELECT `photo`.* from `photo` where `photo`.`id_item` = '$id_item' AND `photo`.`level` = '0';");

        while ($rows = mysql_fetch_array($photo)) {

            $content .= '
                                            <div class="col-md-4 no-padding p-r-5">
                                                <div class="form-group form-group-default">
                                                    <img style="width: 100%; height: 100%; max-height: 300px;" src="images/product/150/thumb_' . (isset($_GET['id']) ? strip_tags(trim($rows["photo"])) : "") . '">
                                                    <a class="btn btn-block btn-xs btn-danger m-t-5 m-b-5 text-white confirmationDelete"
                                                       href="form_products.php?id_photo=' . (isset($_GET['id']) ? strip_tags(trim($rows["id_photo"])) : "") . '">
                                                       Remove</a>
                                                    <input type="file" value="" name="fileimageupdate[]" style="height: 31px;" class="btn-xs"/>
                                                    <input type="hidden" value="' . (isset($_GET['id']) ? strip_tags(trim($rows["id_photo"])) : "") . '" name="id_photo[]">
                                                </div>
                                            </div>';
        }

    }

    $content .= '    
                                        </div>
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