<?php

include("config/configuration.php");

if($loggedin = logged_in()){ // Check if they are logged in
   header( 'Location: index.php' ) ;
} else {

    $save = isset($_POST['signup']) ? $_POST["signup"] : "";

    if($save == "Create a new account"){
        $username  = isset($_POST['username']) ? mysql_real_escape_string(strip_tags(trim($_POST["username"]))) : '';
        $password  = isset($_POST['password']) ? mysql_real_escape_string(strip_tags(trim($_POST["password"]))) : '';
        $confirm   = isset($_POST['confirm']) ? mysql_real_escape_string(strip_tags(trim($_POST["confirm"]))) : '';
        $firstname     = isset($_POST['firstname']) ? mysql_real_escape_string(strip_tags(trim($_POST["firstname"]))) : '';
        $lastname  = isset($_POST['lastname']) ? mysql_real_escape_string(strip_tags(trim($_POST["lastname"]))) : '';
        $email     = isset($_POST['email']) ? mysql_real_escape_string(strip_tags(trim($_POST["email"]))) : '';
        $cmbprovinsi   = isset($_POST['cmbProvinsi']) ? mysql_real_escape_string(strip_tags(trim($_POST["cmbProvinsi"]))) : '';
        $cmbkota   = isset($_POST['cmbKota']) ? mysql_real_escape_string(strip_tags(trim($_POST["cmbKota"]))) : '';
        $notelp    = isset($_POST['notelp']) ? mysql_real_escape_string(strip_tags(trim($_POST["notelp"]))) : '';
        $sql_username       = "select * from `users` where `username` = '$username'";
        $process_username   = mysql_query($sql_username);
        $num_username       = mysql_num_rows($process_username);
        $sql_email      = "select * from `users` where `email` = '$email'";
        $process_email  = mysql_query($sql_email);
        $num_email      = mysql_num_rows($process_email);

        if($num_username != 0){
             echo "<script language='JavaScript'>
                    alert('Username tidak tersedia.');
                    window.history.go(-1);
                   </script>";
        }elseif($num_email != 0){
             echo "<script language='JavaScript'>
                    alert('Alamat Email Sudah Terpakai.');
                    window.history.go(-1);
                   </script>";
        }elseif($password != $confirm){
             echo "<script language='JavaScript'>
                    alert('Password Tidak Sesuai .');
                    window.history.go(-1);
                   </script>";
        }else{
       
            $data_id_member   = mysql_fetch_array(mysql_query("select `id_member` FROM `member` ORDER BY `id_member` DESC LIMIT 1"));
            $id_member_new    = $data_id_member['id_member']+1;
            $pswd             = md5($password);
            
            $data_id_alamat  = mysql_fetch_array(mysql_query("select `id_alamat` FROM `alamat_member` ORDER BY `id_alamat` DESC LIMIT 1"));
            $id_alamat_new    = $data_id_alamat['id_alamat']+1;
            
            $sql = "INSERT INTO `users`(`id_user`, `username`, `password`, `email`, `group`, `lastvisit`, `online`, `blokir`, `id_member`)
                     VALUES('','$username','$pswd','$email','member',NOW(),'0','tidak','$id_member_new')";
            $sqlalamat = "INSERT INTO `alamat_member`(`id_alamat`,`id_member`,`nama_alamat`,`idpropinsi`,`idkota`,`alamat`,`date_add`,`date_upd`,`level`)
                          VALUES('','".$id_member_new."','alamat','".$cmbprovinsi."','".$cmbkota."','',NOW(),NOW(),'0')";
            $sql2 = "INSERT INTO `member`(`firstname`,`lastname`,`idpropinsi`,`idkota`,`email`,`notelp`,`new_member`,`id_alamat`,`date_add`,`date_upd`,`level`)
                     VALUES('$firstname','$lastname','$cmbprovinsi','$cmbkota','$email','$notelp',NOW(),'$id_alamat_new',NOW(),NOW(),'0')";

            //echo $sql ;
            /*$mail             = new PHPMailer(); // defaults to using php "mail()"
            $mail->IsSMTP();
            $mail->SMTPDebug  = 1; // set mailer to use SMTP
            $mail->Timeout = 120;     // set longer timeout for latency or servers that take a while to respond

            $mail->Host = "smtp.dps.globalxtreme.net";        // specify main and backup server
            $mail->Port       = 2505; 
            $mail->SMTPAuth = false;*/    // turn on or off SMTP authentication

            
               
            try {

               /*$body    = mail_register($username, $pswd, $email);
               $body    = preg_replace("[\\\]",'',$body);
               $mail->SetFrom('noreply@globalxtreme.net', 'GlobalXtreme Store');
               
               $mail->AddAddress($email);
               $mail->SetFrom('noreply@globalxtreme.net', 'GlobalXtreme Store');
               $mail->AddBCC("dwi@xtremewebsolution.net");
              
               
               $mail->Subject  = "Email Verification from Globalxtreme Store";
               $mail->AltBody  = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
               $mail->MsgHTML($body);

               $mail->Send();*/
               
               mysql_query($sql) or die("<script language='JavaScript'>
                                     alert('Maaf Data tidak bisa disimpan ke dalam Database, Ada kesalahan!');
                                     window.history.go(-1);
                                    </script>");
                
                mysql_query($sqlalamat) or die("<script language='JavaScript'>
                                     alert('Maaf Data tidak bisa disimpan ke dalam Database, Ada kesalahan!');
                                     window.history.go(-1);
                                    </script>");

               mysql_query($sql2) or die("<script language='JavaScript'>
                                     alert('Maaf Data tidak bisa disimpan ke dalam Database, Ada kesalahan!');
                                     window.history.go(-1);
                                    </script>");
               
               } /*catch (phpmailerException $e) {
                  echo $e->errorMessage(); //Pretty error messages from PHPMailer
               }*/ catch (Exception $e) {
                  echo $e->getMessage(); //Boring error messages from anything else!
               }
           
            echo "<script language='JavaScript'>
                     alert('Your data will be processed,Please check your Email');
                     location.href = 'index.php';
                  </script>";
        }
    }

}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>Registration New Member</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no" />
    <link rel="apple-touch-icon" href="pages/ico/60.png">
    <link rel="apple-touch-icon" sizes="76x76" href="pages/ico/76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="pages/ico/120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="pages/ico/152.png">
    <link rel="icon" type="image/x-icon" href="favicon.ico" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description" />
    <meta content="" name="author" />
    <link href="member/assets/plugins/pace/pace-theme-flash.css" rel="stylesheet" type="text/css" />
    <link href="member/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="member/assets/plugins/font-awesome/css/font-awesome.css" rel="stylesheet" type="text/css" />
    <link href="member/assets/plugins/jquery-scrollbar/jquery.scrollbar.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="member/assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="member/assets/plugins/switchery/css/switchery.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="member/pages/css/pages-icons.css" rel="stylesheet" type="text/css">
    <link class="main-stylesheet" href="member/pages/css/pages.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript">
    window.onload = function()
    {
      // fix for windows 8
      if (navigator.appVersion.indexOf("Windows NT 6.2") != -1)
        document.head.innerHTML += '<link rel="stylesheet" type="text/css" href="member/pages/css/windows.chrome.fix.css" />'
    }
    </script>
  </head>
  <body class="fixed-header "><br>
    <div class="register-container full-height sm-p-t-30">
      <div class=" flex-column full-height ">
        <img src="member/assets/img/s-logo.png" alt="logo" data-src="member/assets/img/s-logo.png" data-src-retina="member/assets/img/s-logo.png" width="126" height="97">
        <h3>REGISTRATION NEW MEMBER</h3>
        <p>
          Create a SeaGods account. If you already have an account, please <a href="login.php" class="text-info">sign in</a>
        </p>
        <?php 
        $sqlprov = "SELECT `idProvinsi`, `namaProvinsi` FROM `provinsi` ORDER BY `namaProvinsi` ";
                  $getProvinsi = mysql_query($sqlprov,$conn) or die ('Query Gagal');
                 
                  $provinsi = mysql_query("select * FROM `kota`, `provinsi` WHERE `kota`.`idProvinsi`=`provinsi`.`idProvinsi`");
                  $data_provinsi = mysql_fetch_array($provinsi);
                  $data_id_kota = $data_provinsi['idKota']; 
                  $data_nama_kota = $data_provinsi['namaKota'];
                  $data_id_provinsi = $data_provinsi['idProvinsi'];
                  $data_nama_provinsi = $data_provinsi['namaProvinsi'];
                  $data_lengkap_provinsi = mysql_query("select * FROM `provinsi` ORDER BY `namaProvinsi` ASC");
        ?>
        <form id="signup" name="signup" method="post" action="register.php" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group form-group-default">
                <label>Id User*</label>
                <input type="text" name="username" id="username"  placeholder="Catatan : id user adalah nama unik untuk akun sea gods" class="form-control" required> 
              </div>
            </div>
            </span><span id="sstt">
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <div class="form-group form-group-default">
                <label>Id Password*</label>
                <input type="password" name="password" id="password"  placeholder="Catatan : id password ini nanti digunakan untuk untuk login" class="form-control" required>   
              </div>
            </div>
          </div>
         <div class="row">
            <div class="col-md-12">
              <div class="form-group form-group-default">
                <label>Confirm Password*</label>
                <input type="password" name="confirm"  placeholder="Catatan : isikan kembali password yang anda isikan di atas" class="form-control" required>   
              </div>
            </div>
          </div>
          <h4 id="h4.-bootstrap-heading"><B>Personal Information</B><span class="anchorjs-icon"></span></h4><br>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group form-group-default">
                <label>First Name</label>
                <input type="text" name="firstname" placeholder="nama depan disesuaikan dengan ktp" class="form-control" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group form-group-default">
                <label>Last Names</label>
                <input type="text" name="lastname" placeholder="nama belakang disesuaikan dengan ktp" class="form-control" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group form-group-default">
                <label>Email*</label>
                <input type="text" name="email" required="" id="email" placeholder="Catatan : isikan email yang valid" class="form-control" required>
              </div>
            </div>
            </span><span id="stts">
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <div class="form-group form-group-default">
                <label>Provinsi</label>
                <select  name="cmbProvinsi" id="country" class="full-width" data-init-plugin="select2">
                            <option value="">--Pilih Provinsi--</option>';
                            <?php 
                            while($data = mysql_fetch_array($getProvinsi)){
                                echo '<option value="'.$data['idProvinsi'].'">'.$data['namaProvinsi'].'</option>';
                             }

                            ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group form-group-default">
                <label>Provinsi</label>
                        <select  name="cmbKota" id="state" class="full-width" data-init-plugin="select2">
                            <option value="">--Pilih Kota--</option>
                        </select>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
              <div class="form-group form-group-default">
                <label>No Hp*</label>
                <input type="text" name="notelp" placeholder="Catatan : isikan no hp anda yang aktif" class="form-control" required >
              </div>
            </div>
          </div>
          <!--<div class="row">
            <div class="col-md-12">
                <div class="form-group form-group-default">
                        <span class="sd-unl"><label for="pass">Masukkan Kode* :</label></span>
                        <span>
                            <img id="captcha" src="../gxplugin/securimage/securimage_show.php" alt="CAPTCHA Image" /><br />
                           <a href="#" onclick="document.getElementById(\'captcha\').src = \'../gxplugin/securimage/securimage_show.php?\' + Math.random(); return false"> [ ganti gambar ]</a><br />
                            <input value="" type="text" name="captcha_code" size="10" maxlength="6" class="captcha">
                        </span>
                        <span class-"sd-unl" style="font-size:12px; color:grey;"><br>Catatan : isikan kode captcha diatas</span>
                    </div>
              </div>
          </div>-->
          <div class="row m-t-10">
            <div class="col-lg-6">
              <p><small>I agree to the <a href="#" class="text-info">Pages Terms</a> and <a href="#" class="text-info">Privacy</a>.</small></p>
            </div>
            <div class="col-lg-6 text-right">
              <a href="#" class="text-info small">Help? Contact Support</a>
            </div>
          </div>
          <input  class="btn btn-primary btn-cons m-t-10" type="submit" name="signup" value="Create a new account">
        </form>
      </div>
    </div>
    <div class=" full-width">
     
    </div>
    <!-- START OVERLAY -->
    
    <!-- END OVERLAY -->
    <!-- BEGIN VENDOR JS -->
    <script src="member/assets/plugins/pace/pace.min.js" type="text/javascript"></script>
    <script src="member/assets/plugins/jquery/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="member/assets/plugins/modernizr.custom.js" type="text/javascript"></script>
    <script src="member/assets/plugins/jquery-ui/jquery-ui.min.js" type="text/javascript"></script>
    <script src="member/assets/plugins/tether/js/tether.min.js" type="text/javascript"></script>
    <script src="member/assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="member/assets/plugins/jquery/jquery-easy.js" type="text/javascript"></script>
    <script src="member/assets/plugins/jquery-unveil/jquery.unveil.min.js" type="text/javascript"></script>
    <script src="member/assets/plugins/jquery-ios-list/jquery.ioslist.min.js" type="text/javascript"></script>
    <script src="member/assets/plugins/jquery-actual/jquery.actual.min.js"></script>
    <script src="member/assets/plugins/jquery-scrollbar/jquery.scrollbar.min.js"></script>
    <script type="text/javascript" src="member/assets/plugins/select2/js/select2.full.min.js"></script>
    <script type="text/javascript" src="member/assets/plugins/classie/classie.js"></script>
    <script src="member/assets/plugins/switchery/js/switchery.min.js" type="text/javascript"></script>
    <script src="member/assets/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <!-- END VENDOR JS -->
    <script src="member/pages/js/pages.min.js"></script>
    <link rel="stylesheet" href="gxplugin/passwordRequirements_master/css/reset.css" />
    <link rel="stylesheet" href="gxplugin/passwordRequirements_master/css/jquery.passwordRequirements.css" />
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script> 

    <script type="text/javascript">
$(document).ready(function(){
    $('#country').on('change',function(){
        var countryID = $(this).val();
        if(countryID){
            $.ajax({
                type:'POST',
                url:'getKota.php',
                data:'idProvinsi='+countryID,
                success:function(html){
                    $('#state').html(html);
                    
                }
            }); 
        }else{
            $('#state').html('<option value="">Pilih Kota</option>');
            
        }
    });
});
</script>
    <script src="jquery.js"></script>
    <script>
    $(document).ready(function(){
        $('#username').blur(function(){
            $('#pesan').html('<img style="margin-left:10px; width:10px" src="loading.gif">');
            var username = $(this).val();

            $.ajax({
                type    : 'POST',
                url     : 'proses_usr.php',
                data    : 'username='+username,
                success : function(data){
                    $('#pesan').html(data);
                }
            })

        });
    });
    </script>
    <script>
    $(document).ready(function(){
        $('#email').blur(function(){
            $('#pesan_email').html('<img style="margin-left:10px; width:10px" src="loading.gif">');
            var email = $(this).val();

            $.ajax({
                type    : 'POST',
                url     : 'proses_email.php',
                data    : 'email='+email,
                success : function(data){
                    $('#pesan_email').html(data);
                }
            })

        });
    });
    </script>
    <script src="gxplugin/passwordRequirements_master/js/jquery.passwordRequirements.js"></script>
    <script>
    var jq = $.noConflict();
        /* trigger when page is ready */
        jq(document).ready(function (){
            jq(".pr-password").passwordRequirements();
        });
    </script>
<style>
.sd-uid {
    margin: 0 10px 7px 0;
}
.sd-rCont {
    float: right;
    height: 270px;
    width: 45%;
}
.sd-rcc {
    margin-left: auto;
    margin-right: auto;
    text-align: center;
    width: 350px;
}
.sd-rcc {
    text-align: center;
}
.btn-scnd, a.btn-scnd, a.btn-scnd:visited, .btn.btn-scnd.btn-d:hover, a.btn.btn-scnd.btn-d:hover, .btn.btn-scnd.btn-d:focus, a.btn.btn-scnd.btn-d:focus, .btn.btn-scnd.btn-d:active, a.btn.btn-scnd.btn-d:active {
    background: -moz-linear-gradient(center top , #45AAD6, #2386C0) repeat scroll 0 0 rgba(0, 0, 0, 0);
    color: #FFFFFF;
    text-decoration: none;
}
.btn {
    border: 1px solid rgba(0, 0, 0, 0);
    border-radius: 3px 3px 3px 3px;
    box-shadow: 0 3px 0 rgba(0, 0, 0, 0.04);
    cursor: pointer;
    display: inline-block;
    font-size: 16px;
    font-weight: 500;
    padding: 0.5em 1.2em;
    text-align: center;
    text-decoration: none;
    vertical-align: baseline;
    white-space: nowrap;
}
.sd-lCont {
    border-right: 1px solid #CCCCCC;
    box-shadow: 4px 0 1px #EEEEEE;
    padding: 25px;
    width: 50%;
}
.sd-txtA {
    color: #333333;
    display: inline-block;
    font-size: 20px;
    font-weight: bold;
    padding-bottom: 12px;
}
.sd-unl {
    color: #555555;
    display: block;
    font-size: 14px;
    padding: 5px 0 3px;
}
input.txtBxF {
    width: 343px;
}
input.txtBxF {
    border: 1px solid #CCCCCC;
    border-radius: 3px 3px 3px 3px;
    color: #333333;
    font-size: 16px;
    padding: 8px 0 8px 7px;
    width: 250px;
}input.captcha {
    border: 1px solid #CCCCCC;
    border-radius: 3px 3px 3px 3px;
    color: #333333;
    font-size: 16px;
    padding: 8px 0 8px 7px;
    width: 100px;
}
input, button, select, textarea {
    font-family: inherit;
}
input, select {
    vertical-align: middle;
}
.sd-bc {
    padding: 0;
    width: 80%;
    margin: auto;
}

.sd-el {
    margin: 0 0 70px;
}

.sd-bc {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #DDDDDD;
    border-radius: 3px 3px 3px 3px;
    box-shadow: 4px 4px 1px #EEEEEE;
}
.sd-sgn {
    color: #999999;
    padding: 3px 0 0 30px;
}
.sd-km {
    width: 350px;
}
.sd-km {
    color: #666666;
    font-size: 12px;
    margin: 0 0 5px;
}
.btn-prim, a.btn-prim, a.btn-prim:visited, .btn-split, a.btn-split, a.btn-split:visited, .btn.btn-prim.btn-d:hover, a.btn.btn-prim.btn-d:hover, .btn.btn-prim.btn-d:focus, a.btn.btn-prim.btn-d:focus, .btn.btn-prim.btn-d:active, a.btn.btn-prim.btn-d:active {
    background: -moz-linear-gradient(center top , #0079BC, #00509D) repeat scroll 0 0 rgba(0, 0, 0, 0);
    color: #FFFFFF;
    text-decoration: none;
}
.btn {
    border: 1px solid rgba(0, 0, 0, 0);
    border-radius: 3px 3px 3px 3px;
    box-shadow: 0 3px 0 rgba(0, 0, 0, 0.04);
    cursor: pointer;
    display: inline-block;
    font-size: 16px;
    font-weight: 500;
    padding: 0.5em 1.2em;
    text-align: center;
    text-decoration: none;
    vertical-align: baseline;
    white-space: nowrap;
}
</style>
<script type="text/javascript" src="gxplugin/jquery.validate.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("#signup").validate({
    rules: {
        username    : {required: true, minlength: 4, maxlength: 15},
            password    : {required: true,minlength: 6},
            confirm     : {required: true,minlength: 6,equalTo: "#password"},
            firstname   : {required: true},
            email       : {required: true, minlength: 6, email: true},
            notelp  : {required: true, number: true}
        
    },
        messages: { 
        username    : {required: "This field is required."},
            password    : {required: "This field is required.",minlength: "Min password 6 character"},
            confirm     : {required: "This field is required.",minlength: "Min password 6 character",equalTo: "Your passwords do not match."},
            firstname   : {required: "This field is required."},
            email       : {required: "This field is required."},
        notelp      : {required: "This field is required."}
            
    },
    });
    
});

</script>
<script>

</script>
<!--<script type="text/javascript">
$(document).ready(function()
{
    $("#email").change(function()
    {
        var email = $("#email").val();
        $("#stts").html('Sedang mengcek email...');
        $.ajax({
            type: "POST",
            url: "cek_email.php",
            data: "email="+ email,
            success: function(hasil){
            $("#stts").ajaxComplete(function(event, request){

                if(hasil == 'yes')
                {
                    $("#stts").html('<img src="images/available.png" align="absmiddle"> <font color="blue"><b>Available</b></font>');
                }
                else  if(hasil == 'no')
                {
                    $("#stts").html('<img src="images/not_available.png" align="absmiddle"> <font color="red"><b>Not Available</b></font>');
                }
           });
           }

          });
    return false;
    });

});
</script>-->
<!--<script type="text/javascript">
$(document).ready(function()
{
    $("#username").change(function()
    {
        var username = $("#username").val();
        $("#sstt").html('Sedang mengcek username...');
        $.ajax({
            type: "POST",
            url: "user-check.php",
            data: "username="+ username,
            success: function(hasil){
                $("#sstt").ajaxComplete(function(event, request){

                    if(hasil == 'yes')
                    {
                        $("#sstt").html('<img src="images/available.png" align="absmiddle"> <font color="blue"><b>Available</b></font>');
                    }
                    else  if(hasil == 'no')
                    {
                        $("#sstt").html('<img src="images/not_available.png" align="absmiddle"> <font color="red"><b>Not Available</b></font>');
                    }
               });
           }

        });
    return false;
    });

});
</script>-->

  </body>
</html>