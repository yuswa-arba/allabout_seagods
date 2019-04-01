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

if ($loggedin = logged_in()) {//  Check if they are logged in

    $titlebar = "Confirmation - No Invoice";
    $titlepage = "Confirmation - No Invoice";

    $menu = "";
    $user = '' . $loggedin['firstname'] . ' ' . $loggedin['lastname'] . '';

    $content = '
        <div class="content ">
            <div class=" container container-fixed-lg">
                
                <!-- START card -->
                <div class="card card-default">
                    <div class="card-block no-scroll card-toolbar">
                        <form method="post" action="" enctype="multipart/form-data" role="form">
                            <div class="form-group form-group-default ">
                                <input class="form-control" name="title" value="READ ONLY - No Invoice">
                            </div>
                            <div class="form-group form-group-default required ">
                            <label>Date</label>
                                <input class="form-control" name="title" value="Read Only - Langsung baca tanggal hari ini">
                            </div>
                            <div class="form-group form-group-default form-group-default-select2 required">
                            <label>To Bank</label>
                                <select class="full-width" data-placeholder="Select Bank" name="id_cat" data-init-plugin="select2">
                                <option>BCA</option>
                                <option>Mandiri</option>
                                </select>
                            </div>
                            <div class="form-group form-group-default required ">
                            <label>From Bank (Member)</label>
                                <input class="form-control" name="title" value="">
                            </div>
                            <div class="form-group form-group-default required ">
                            <label>Member Account Number</label>
                                <input class="form-control" name="title" value="">
                            </div>
                             <div class="form-group form-group-default required ">
                            <label>Nominal</label>
                                <input class="form-control" name="title" value="">
                            </div>
                            <div class="form-group form-group-default ">
                        <label>Upload Photo</label><br/>
                        <input type="file" value="" name="fileimage[]" style="height: 31px;" /> <br />
                        </div>

                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" name="SUBMIT">SUBMIT</button>
                            </div>
                        </form>
                    </div>
                    
                </div>
                <!-- END card -->
                
            </div>
        </div>';

    $plugin = '
    <link href="assets/plugins/summernote/css/summernote.css" rel="stylesheet" type="text/css" media="screen">
	
    <script type="text/javascript" src="assets/plugins/jquery-autonumeric/autoNumeric.js"></script>
    <script type="text/javascript" src="assets/plugins/dropzone/dropzone.min.js"></script>
    <script type="text/javascript" src="assets/plugins/bootstrap-tag/bootstrap-tagsinput.min.js"></script>
    <script type="text/javascript" src="assets/plugins/jquery-inputmask/jquery.inputmask.min.js"></script>
    <script src="assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript"></script>
    <script src="assets/plugins/summernote/js/summernote.min.js" type="text/javascript"></script>
    <script src="assets/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="assets/plugins/bootstrap-typehead/typeahead.bundle.min.js"></script>
    <script src="assets/plugins/bootstrap-typehead/typeahead.jquery.min.js"></script>
    <script src="assets/plugins/handlebars/handlebars-v4.0.5.js"></script>
    <!-- BEGIN PAGE LEVEL JS -->
    <script src="assets/js/form_elements.js" type="text/javascript"></script>
    <script src="assets/js/scripts.js" type="text/javascript"></script>';

    $template = admin_template($content, $titlebar, $titlepage, $user, $menu, $plugin);
    echo $template;

} else {
    header('Location: logout.php');
}

?>