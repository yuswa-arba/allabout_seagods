<?php
/*
 * Project Name: Globalxtreme Web Rainiersonline Template
 * Project URI: https://rainiersonline.com
 * Author: GlobalXtreme.net
 * Version: 1.0  | 26 September 2017
 * Email: adit@globalxtreme.net
 * Project for Web rainiersonline
 */
function template_store($content="",$title="",$plugin=""){
    
    $title      = ($title!="") ? $title : "";
    $plugin	    = ($plugin!="") ? $plugin : "";
    $content	= ($content!="") ? $content : "";

 
$template ='
<!DOCTYPE html>
<html>
<head>
<title>'.$title.'</title>
<!--/tags -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Elite Shoppy Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--//tags -->
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/font-awesome.css" rel="stylesheet"> 
<link href="css/easy-responsive-tabs.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="css/paging.css">
<!-- //for bootstrap working -->
<link href="//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800" rel="stylesheet">
<link href="//fonts.googleapis.com/css?family=Lato:400,100,100italic,300,300italic,400italic,700,900,900italic,700italic" rel="stylesheet" type="text/css">
'.$plugin.'
<script language="javascript">
//confirm message for logout.
function logout() {
    if (confirm("Anda yakin untuk keluar ?")) {
	   
       window.location.href = "logout.php";
		}
}
</script>

<script type="text/javascript">
jQuery(document).ready(function(){

	// hide #back-top first
	jQuery("#back-top").hide();
	
	// fade in #back-top
	jQuery(function () {
		jQuery(window).scroll(function () {
			if ($(this).scrollTop() > 500) {
				jQuery(\'#back-top\').fadeIn();
			} else {
				jQuery(\'#back-top\').fadeOut();
			}
		});

		// scroll body to 0px on click
		jQuery(\'#back-top a\').click(function () {
			jQuery(\'body,html\').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
		
		jQuery(\'#search-form_is\').click(function () {
		    document.getElementById(\'search-header\').submit();
		    return false;
		});
	});

});
</script>
<style type="text/css">
  /*Style Notifikasi*/
  .bubble
  {
    background: #e02424;    
    margin-left:7px;
    right: 5px;
    top: -5px;
    padding: 2px 6px;
    color: #fff;
    font: bold .9em Tahoma, Arial, Helvetica;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
  }

</style>
</head>
<body>
<!-- header -->
<div class="header" id="home">
	<div class="container">
		<ul>';
		if($loggedin = logged_in()){
			$sql = mysql_query("SELECT * FROM `notif_log` WHERE `status`='1' AND `id_member`='".$loggedin["id_member"]."'");
			$array = mysql_num_rows($sql);
			$jumlah= json_encode($array);
$template .='
		    <li> <a href="member_home.php"><i class="fa fa-unlock-alt" aria-hidden="true"></i> '.$loggedin["username"].' </a></li>
			<li> <a href="javascript:logout();"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Keluar </a></li>
			<li><i class="fa fa-phone" aria-hidden="true"></i> +62 878-5905-2006</li>
			<li><a href="notif_page.php">Notifikasi<span class="bubble" id="jumlah_pesanan">'.$jumlah.'</span></a></li>
';
		}else{
$template .='
		    <li> <a href="#" data-toggle="modal" data-target="#myModal"><i class="fa fa-unlock-alt" aria-hidden="true"></i> Masuk </a></li>
			<li> <a href="signup.php"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Daftar </a></li>
			<li><i class="fa fa-phone" aria-hidden="true"></i> +62 878-5905-2006</li>
			<li><i class="fa fa-envelope-o" aria-hidden="true"></i> <a href="mailto:toko@xtremewebsolution.net">toko@xtremewebsolution.net</a></li>
';
		}
$template .='
			
		</ul>
	</div>
</div>
<!-- //header -->
<!-- header-bot -->
<div class="header-bot">
	<div class="header-bot_inner_wthreeinfo_header_mid">
		
		
		<!-- header-bot -->
			<div class="col-md-4 logo_agile">
			<a href="index.php"><img src="images/logo.png"></a>
				<!--<h1><a href="index.html"><span>R</span>ainiers <i class="fa fa-shopping-bag top_logo_agile_bag" aria-hidden="true"></i></a></h1>-->
			</div>
        <!-- header-bot -->

			<div class="col-md-8 header-middle">
			<form id="search-header" method="get" action="list_produk.php" accept-charset="utf-8">';
	
	$ip      = $_SERVER['REMOTE_ADDR']; // Mendapatkan IP komputer user
	$tanggal = date("Ymd"); // Mendapatkan tanggal sekarang
	$waktu   = time(); //
   
	 // Mencek berdasarkan IPnya, apakah user sudah pernah mengakses hari ini
	$s = mysql_query("SELECT * FROM `statistik` WHERE `ip`='$ip' AND `tanggal`='$tanggal'");
	
	if(isset($_GET['q'])){ if($_GET['q']!='' ){ $template .= '<input type="search" name="q" id="keyword"  value="'.$_GET['q'].'" placeholder="" >'; } else{ $template .= '<input type="search" name="q" placeholder="Search here..." >'; } }
		else{ $template .= '<input type="search" name="q" placeholder="Search here..." >'; }
		 $queryProv = "SELECT * FROM `provinsi` ORDER BY `idProvinsi` ASC";
    $resultProv = mysql_query($queryProv) or die (mysql_error());
    
	
	if(isset($_GET['prov'])){
	    if($_GET['prov']!=''){
		$template .='<input type="hidden" name="prov" value="'.$_GET['prov'].'">';
	    $querykota = "SELECT * FROM `kota` WHERE idProvinsi='$_GET[prov]' ORDER BY namaKota DESC";
	    $resultkota = mysql_query($querykota);
	    $template.='
	    <select name="kota" style="float: left;"> <option value="" selected>Pilih Kota</option>';
		
		
		while ($rowkota = mysql_fetch_assoc($resultkota)){
		    if($rowkota['idKota'] == $_GET['kota']){
			$template.=' <option value="' . $rowkota["idKota"] .'" selected>' . $rowkota["namaKota"] .'</option>'; 
		    }else{
			$template.=' <option value="' . $rowkota["idKota"] .'">' . $rowkota["namaKota"] .'</option>'; 
		    }
		}
		    $template.='</select>';
	}else{$template.='<select name="prov" style="float: left;"> <option value="" selected>Pilih Provinsi</option>';
		while ($rowProv = mysql_fetch_assoc($resultProv)){
		    $template.=' <option value="' . $rowProv["idProvinsi"] .'">' . $rowProv["namaProvinsi"] .'</option>';
		}
	$template.='</select>'; } }
	else{ $template.='<select name="prov" style="float: left;"> <option value="" selected>Pilih Provinsi</option>';  while ($rowProv = mysql_fetch_assoc($resultProv)){ $template.=' <option value="' . $rowProv["idProvinsi"] .'">' . $rowProv["namaProvinsi"] .'</option>'; } $template.='</select>'; }
		
    
$template .='
					
					<input type="submit" value=" ">
					<a href="" id="search-form_is" onclick="document.getElementById(\'search-header\').submit()"><i class="icon-search"></i></a>
				<div class="clearfix"></div>
			</form>

		</div>
		<div class="clearfix"></div>
	</div>
</div>
<!-- //header-bot -->

<!-- banner -->
<div class="ban-top">
	<div class="container">
		<div class="top_nav_left">
			<nav class="navbar navbar-default">
			  <div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
				  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse menu--shylock" id="bs-example-navbar-collapse-1">
				  <ul class="nav navbar-nav menu__list">
					<li class="active menu__item menu__item--current"><a class="menu__link" href="index.php">Home <span class="sr-only">(current)</span></a></li>
					<li class=" menu__item "><a class="menu__link" href="index.php">Fashion <span class="sr-only"></span></a></li>
					<li class=" menu__item "><a class="menu__link" href="list_produk.php?cat=18">Elektronik <span class="sr-only"></span></a></li>
					<li class=" menu__item "><a class="menu__link" href="index.php">Otomotif <span class="sr-only"></span></a></li>
					<!--<li class="dropdown menu__item">
						<a href="#" class="dropdown-toggle menu__link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Fashion <span class="caret"></span></a>
							<ul class="dropdown-menu multi-column columns-3">
								<div class="agile_inner_drop_nav_info">
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="#">Clothing</a></li>
											<li><a href="#">Wallets</a></li>
											<li><a href="#">Footwear</a></li>
											<li><a href="#">Watches</a></li>
											<li><a href="#">Accessories</a></li>
											<li><a href="#">Bags</a></li>
											<li><a href="#">Caps & Hats</a></li>
										</ul>
									</div>
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="#">Jewellery</a></li>
											<li><a href="#">Sunglasses</a></li>
											<li><a href="#">Perfumes</a></li>
											<li><a href="#">Beauty</a></li>
											<li><a href="#">Shirts</a></li>
											<li><a href="#">Sunglasses</a></li>
											<li><a href="#">Swimwear</a></li>
										</ul>
									</div>
									<div class="col-sm-6 multi-gd-img multi-gd-text ">
										<a href="#"><img src="images/top1.jpg" alt=" "/></a>
									</div>
									<div class="clearfix"></div>
								</div>
							</ul>
					</li>-->
					
					<!--<li class="dropdown menu__item">
						<a href="list_produk.php?cat=18" class="dropdown-toggle menu__link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Electronik <span class="caret"></span></a>
							<ul class="dropdown-menu multi-column columns-3">
								<div class="agile_inner_drop_nav_info">
									<div class="col-sm-6 multi-gd-img1 multi-gd-text ">
										<a href="#"><img src="images/top2.jpg" alt=" "/></a>
									</div>
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="#">Handphone</a></li>
											<li><a href="#">Tablet</a></li>
											<li><a href="#">Komputer</a></li>
											<li><a href="#">Laptop</a></li>
											<li><a href="#">TV</a></li>
											<li><a href="#">Audio</a></li>
											<li><a href="#">Digital Camera</a></li>
										</ul>
									</div>
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="#">CCTV</a></li>
											<li><a href="#">Fiber Optic</a></li>
											<li><a href="#">Wireless</a></li>
											<li><a href="#">Modem</a></li>
											<li><a href="#">Antenna</a></li>
											<li><a href="#">Projector</a></li>
											<li><a href="#">Alarm</a></li>
										</ul>
									</div>
									<div class="clearfix"></div>
								</div>
							</ul>
					</li>-->
					
					<!--<li class="dropdown menu__item">
						<a href="#" class="dropdown-toggle menu__link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Otomotif <span class="caret"></span></a>
							<ul class="dropdown-menu multi-column columns-3">
								<div class="agile_inner_drop_nav_info">
									
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
										    <li><a href="#">Car</a></li>
											<li><a href="#">Motorcycle</a></li>
											<li><a href="#">Safety & Care</a></li>

										</ul>
									</div>
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="#">Car Parts</a></li>
											<li><a href="#">Motor Parts </a></li>
											<li><a href="#">Car Accessories</a></li>
											<li><a href="#">Motor Accessories</a></li>	
										</ul>
									</div>
									<div class="col-sm-6 multi-gd-img1 multi-gd-text ">
										<a href="#"><img src="images/top3.jpg" alt=" "/></a>
									</div>
									<div class="clearfix"></div>
								</div>
							</ul>
					</li>-->
					
					<!--<li class=" menu__item"><a class="menu__link" href="#">About</a></li>
					
					<li class=" menu__item"><a class="menu__link" href="#">Contact</a></li>-->
				  </ul>
				</div>
			  </div>
			</nav>	
		</div>
		<div class="top_nav_right">
			<div class="wthreecartaits wthreecartaits2 cart cart box_1"> 
					<form action="chart.php" method="post" class="last">
						<input type="hidden" name="cmd" value="_cart">
						<input type="hidden" name="display" value="1">
						<button class="w3view-cart" type="submit" name="submit" value=""><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></button>
					</form> 
  
						</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<!-- //banner-top -->
';

include_once 'gxplugin/securimage/securimage.php';
$securimage = new Securimage();

if (isset($_POST['login'])) { // Check if submit button has been pressed.#fdf1f1

	/** CHECK COOKIES **/
	echo check_phpsessid();

	$_POST = protection($_POST); // Protect the $_POST variable.
	$_GET = protection($_GET); // Protect the $_GET variable.
     
		if(empty($_POST['username']) || empty($_POST['password'])) { // Check if the form fields are empty or not.
			echo '<script language="JavaScript">
			  alert("Login failed. Check your username and/or password.");
			  </script>'; // If there empty show error message.
		}else{
			if($securimage->check($_POST['captcha_code']) == false) {
            echo "<script language='JavaScript'>
               alert('The security code entered was incorrect.');
	       window.history.go(-1);
               </script>";
			}else{
			$username = isset($_POST['username']) ? mysql_real_escape_string(strip_tags($_POST['username'])) : "";
			$redirect = isset($_GET['r']) ? mysql_real_escape_string(strip_tags($_GET['r'])) : "";
			$chkuser = mysql_query("SELECT * FROM `users` WHERE (`username` = '".$username."' OR `email` = '".$username."') && `password` = '".md5($_POST['password'])."' AND `group` != 'admin' LIMIT 0, 1" ); // Check if the username and password are correct.
				if(mysql_num_rows($chkuser)) { // Check if they are correct
					$vcu = mysql_fetch_array($chkuser); // Get the information
					$jam = date("H");
					$waktu = date("Y-m-d ").($jam-1).date(":i:s");
					
					$results = mysql_query("INSERT into `sessions` (`sess_id`, `uid`, `logged`, `ip`, `waktu`) values ('".$_COOKIE['PHPSESSID']."', '".$vcu['id_user']."', '0', '".$_SERVER['REMOTE_ADDR']."', '$waktu') "); // Insert the session id and user id into the sessions table to create the login.
					
					$sql = mysql_query("UPDATE `users` SET `online` = '0', `lastvisit` = now()  WHERE `id_user` = '".$vcu['id_user']."' ");
					
						if($results) {
							if($sql) { // If it submitted it then success.
							    $_SESSION['user'] = $_POST['username']; 
								if($redirect != "") { // If $_GET['r'] is blank redirect the user to index.php after login if not redirect the user to the url indicated in login.php?r=http://www.google.com
								    header("Location: ".$_GET['r']);
								} elseif($redirect == "") {
								    header("Location: member_home.php");
								}
							} else { // If couldnt submit into sessions table then show error message
									echo '<script language="JavaScript">
									  alert("Unknown Error sql.");
									</script>';
							}	
						} else { // If couldnt submit into sessions table then show error message
						  
						  echo '<script language="JavaScript">
							alert("Unknown Error result.");
						      </script>';
						}
		           
				} else{
				    echo '<script language="JavaScript">
							alert("Incorrect Username Or Password.");
						      </script>';
				}
			}
		}
}
$template .='
<!-- login -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
						<div class="modal-body modal-body-sub_agile">
						<div class="col-md-8 modal_body_left modal_body_left1">
						<h3 class="agileinfo_sign">Masuk <span>Sekarang</span></h3>
						<form action="#" method="post">
							<div class="styled-input agile-styled-input-top">
								<input type="text" name="username" required="">
								<label>Username</label>
								<span></span>
							</div>
							<div class="styled-input">
								<input name="password" type="password" required="">
								<label>Password</label>
								<span></span>
							</div>
							<div class="styled-input">
								<input type="hidden" name="nilai" value="'.$_SESSION['nilai_login'].'"></span>
							</div>
							<div>
								<span class="sd-unl"><label for="pass">Masukkan Kode* :</label></span>
								<span>
									<img id="captcha" src="../gxplugin/securimage/securimage_show.php" alt="CAPTCHA Image" /><br />
									<a href="#" onclick="document.getElementById(\'captcha\').src = \'../gxplugin/securimage/securimage_show.php?\' + Math.random(); return false"> [ ganti gambar ]</a><br />
									<input value="" type="text" name="captcha_code" size="10" maxlength="6" class="captcha">
								</span>
								<span class-"sd-unl" style="font-size:10px; color:grey;"><br>Catatan : isikan kode captcha diatas</span><br>
								<span class-"sd-unl" style="font-size:13px;"><input type="checkbox" name="remember" id="remember" style="margin: 0px; vertical-align: middle;"/> 
								Remember me<br></span>
							</div>
							
							<input type="submit" name="login" value="Masuk">
						</form>
						  <ul class="social-nav model-3d-0 footer-social w3_agile_social top_agile_third">
															<li><a href="#" class="facebook">
																  <div class="front"><i class="fa fa-facebook" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-facebook" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="twitter"> 
																  <div class="front"><i class="fa fa-twitter" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-twitter" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="instagram">
																  <div class="front"><i class="fa fa-instagram" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-instagram" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="pinterest">
																  <div class="front"><i class="fa fa-linkedin" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-linkedin" aria-hidden="true"></i></div></a></li>
														</ul>
														<div class="clearfix"></div>
														<p><a href="signup.php" data-toggle="modal" data-target="#myModal2" > Belum punya akun ?</a></p>

						</div>
						<div class="col-md-4 modal_body_right modal_body_right1">
							<img src="images/log_pic.jpg" alt=" "/>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<!-- //Modal content-->
			</div>
		</div>
<!-- //login -->

'.$content.'
<div class="coupons">
		<div class="coupons-grids text-center">
			<div class="w3layouts_mail_grid">
				<div class="col-md-3 w3layouts_mail_grid_left">
					<div class="w3layouts_mail_grid_left1 hvr-radial-out">
						<i class="fa fa-truck" aria-hidden="true"></i>
					</div>
				<div class="w3layouts_mail_grid_left2">
						<h3>Gratis Ongkos Kirim</h3>
						<p>Hanya Area Malang Kota<br>Syarat dan ketentuan berlaku</p>
					</div>
				</div>
				<div class="col-md-3 w3layouts_mail_grid_left">
					<div class="w3layouts_mail_grid_left1 hvr-radial-out">
						<i class="fa fa-headphones" aria-hidden="true"></i>
					</div>
					<div class="w3layouts_mail_grid_left2">
						<h3>24/7 SUPPORT</h3>
						<p>Hanya melalui Whatsapp</p>
					</div>
				</div>
				<div class="col-md-3 w3layouts_mail_grid_left">
					<div class="w3layouts_mail_grid_left1 hvr-radial-out">
						<i class="fa fa-shopping-bag" aria-hidden="true"></i>
					</div>
					<div class="w3layouts_mail_grid_left2">
						<h3>Garansi Uang Kembali</h3>
						<p>Syarat dan Ketentuan Berlaku</p>
					</div>
				</div>
					<div class="col-md-3 w3layouts_mail_grid_left">
					<div class="w3layouts_mail_grid_left1 hvr-radial-out">
						<i class="fa fa-gift" aria-hidden="true"></i>
					</div>
					<div class="w3layouts_mail_grid_left2">
						<h3>Kupon</h3>
						<p>Syarat dan Ketentuan Berlaku</p>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>

		</div>
</div>
<!--grids-->
<!-- footer -->
<div class="footer">
	<div class="footer_agile_inner_info_w3l">
		<div class="col-md-3 footer-left">
			<h2><a href="index.html"><span>R</span>ainiers </a></h2>
			<p>One Stop Shopping<br>Online Store in Malang</p>
			<ul class="social-nav model-3d-0 footer-social w3_agile_social two">
															<li><a href="#" class="facebook">
																  <div class="front"><i class="fa fa-facebook" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-facebook" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="twitter"> 
																  <div class="front"><i class="fa fa-twitter" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-twitter" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="instagram">
																  <div class="front"><i class="fa fa-instagram" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-instagram" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="pinterest">
																  <div class="front"><i class="fa fa-linkedin" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-linkedin" aria-hidden="true"></i></div></a></li>
														</ul>
		</div>
		<div class="col-md-9 footer-right">
			<div class="sign-grds">
				<div class="col-md-4 sign-gd">
					<h4>Our <span>Information</span> </h4>
					<ul>
						<li><a href="index.php">Home</a></li>
						<li><a href="info.php?inf=about">Tentang Kami</a></li>
						<li><a href="info.php?inf=term">Syarat dan Ketentuan</a></li>
						<li><a href="info.php?inf=ads">Pasang Iklan</a></li>
						<li><a href="info.php?inf=member">Verified Member</a></li>
						<!--<li><a href="info.php?inf=rekber">Rekening Bersama</a></li>-->
					</ul>
				</div>
				
				<div class="col-md-5 sign-gd-two">
					<h4>Store <span>Information</span></h4>
					<div class="w3-address">
						<div class="w3-address-grid">
							<div class="w3-address-left">
								<i class="fa fa-phone" aria-hidden="true"></i>
							</div>
							<div class="w3-address-right">
								<h6>Mobile Phoner</h6>
								<p>+62 878-5905-2006</p>
							</div>
							<div class="clearfix"> </div>
						</div>
						<div class="w3-address-grid">
							<div class="w3-address-left">
								<i class="fa fa-envelope" aria-hidden="true"></i>
							</div>
							<div class="w3-address-right">
								<h6>Email </h6>
								<p><a href="mailto:toko@xtremewebsolution.net"> toko@xtremewebsolution.net</a></p>
							</div>
							<div class="clearfix"> </div>
						</div>
						<div class="w3-address-grid">
							<div class="w3-address-left">
								<i class="fa fa-map-marker" aria-hidden="true"></i>
							</div>
							<div class="w3-address-right">
								<h6>Alamat</h6>
								<p>Bukit Dieng MA 1B<br>Malang, East Java 
								
								</p>
							</div>
							<div class="clearfix"> </div>
						</div>
					</div>
				</div>
				<!--<div class="col-md-3 sign-gd flickr-post">
					<h4>Flickr <span>Posts</span></h4>
					<ul>
						<li><a href="#"><img src="images/t1.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t2.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t3.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t4.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t1.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t2.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t3.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t2.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t4.jpg" alt=" " class="img-responsive" /></a></li>
					</ul>
				</div>-->
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="clearfix"></div>
			<div class="agile_newsletter_footer">
					<div class="col-sm-6 newsleft">
				<h3>BERLANGGANAN INFORMASI TERBARU KAMI</h3>
			</div>
			<div class="col-sm-6 newsright">
				<form action="#" method="post">
					<input type="email" placeholder="Enter your email..." name="email" required="">
					<input type="submit" value="Submit">
				</form>
			</div>

		<div class="clearfix"></div>
	</div>
		<p class="copy-right">&copy 2017 Rainiers Online. All rights reserved </p>
	</div>
</div>
<!-- //footer -->

<!-- login -->
			<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content modal-info">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>						
						</div>
						<div class="modal-body modal-spa">
							<div class="login-grids">
								<div class="login">
									<div class="login-bottom">
										<h3>Daftar Gratis</h3>
										<form>
											<div class="sign-up">
												<h4>Email :</h4>
												<input type="text" value="Type here" onfocus="this.value = \'\';" onblur="if (this.value == \'\') {this.value = \'Type here\';}" required="">	
											</div>
											<div class="sign-up">
												<h4>Password :</h4>
												<input type="password" value="Password" onfocus="this.value = \'\';" onblur="if (this.value == \'\') {this.value = \'Password\';}" required="">
												
											</div>
											<div class="sign-up">
												<h4>Re-type Password :</h4>
												<input type="password" value="Password" onfocus="this.value = \'\';" onblur="if (this.value == \'\') {this.value = \'Password\';}" required="">
												
											</div>
											<div class="sign-up">
												<input type="submit" value="REGISTER NOW" >
											</div>
											
										</form>
									</div>
									<div class="login-right">
										<h3>Masuk dengan akun Anda</h3>
										<form>
											<div class="sign-in">
												<h4>Email :</h4>
												<input type="text" value="Type here" onfocus="this.value = \'\';" onblur="if (this.value == \'\') {this.value = \'Type here\';}" required="">	
											</div>
											<div class="sign-in">
												<h4>Password :</h4>
												<input type="password" value="Password" onfocus="this.value = \'\';" onblur="if (this.value == \'\') {this.value = \'Password\';}" required="">
												<a href="#">Forgot password?</a>
											</div>
											<div class="single-bottom">
												<input type="checkbox"  id="brand" value="">
												<label for="brand"><span></span>Remember Me.</label>
											</div>
											<div class="sign-in">
												<input type="submit" value="SIGNIN" >
											</div>
										</form>
									</div>
									<div class="clearfix"></div>
								</div>
								<p>By logging in you agree to our <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
<!-- //login -->
<a href="#home" class="scroll" id="toTop" style="display: block;"> <span id="toTopHover" style="opacity: 1;"> </span></a>

<!-- js -->
<script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
<!-- //js -->
<script src="js/modernizr.custom.js"></script>
	<!-- Custom-JavaScript-File-Links --> 
	<!-- cart-js 
	<script src="js/minicart.min.js"></script>
<script>
	// Mini Cart
	paypal.minicart.render({
		action: \'#\'
	});

	if (~window.location.search.indexOf(\'reset=true\')) {
		paypal.minicart.reset();
	}
</script>

	<!-- //cart-js --> 
<!-- script for responsive tabs -->						
<script src="js/easy-responsive-tabs.js"></script>
<script>
	$(document).ready(function () {
	$(\'#horizontalTab\').easyResponsiveTabs({
	type: \'default\', //Types: default, vertical, accordion           
	width: \'auto\', //auto or any width like 600px
	fit: true,   // 100% fit in a container
	closed: \'accordion\', // Start closed if in accordion view
	activate: function(event) { // Callback function if tab is switched
	var $tab = $(this);
	var $info = $(\'#tabInfo\');
	var $name = $(\'span\', $info);
	$name.text($tab.text());
	$info.show();
	}
	});
	$(\'#verticalTab\').easyResponsiveTabs({
	type: \'vertical\',
	width: \'auto\',
	fit: true
	});
	});
</script>
<!-- //script for responsive tabs -->		
<!-- stats -->
	<script src="js/jquery.waypoints.min.js"></script>
	<script src="js/jquery.countup.js"></script>
	<script>
		$(\'.counter\').countUp();
	</script>
<!-- //stats -->
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/jquery.easing.min.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){		
			event.preventDefault();
			$(\'html,body\').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script>
<!-- here stars scrolling icon -->
	<script type="text/javascript">
		$(document).ready(function() {
			/*
				var defaults = {
				containerID: \'toTop\', // fading element id
				containerHoverID: \'toTopHover\', // fading element hover id
				scrollSpeed: 1200,
				easingType: \'linear\' 
				};
			*/
								
			$().UItoTop({ easingType: \'easeOutQuart\' });
								
			});
	</script>
<!-- //here ends scrolling icon -->


<!-- for bootstrap working -->
<script type="text/javascript" src="js/bootstrap.js"></script>
</body>
</html>

';
    return $template;
}

function template_member($content="",$title="",$plugin=""){
    
    $title      = ($title!="") ? $title : "";
    $plugin	    = ($plugin!="") ? $plugin : "";
    $content	= ($content!="") ? $content : "";
    
$template ='
<!DOCTYPE html>
<html>
<head>
<title>'.$title.'</title>
<!--/tags -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Elite Shoppy Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--//tags -->
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/font-awesome.css" rel="stylesheet"> 
<link href="css/easy-responsive-tabs.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="css/paging.css">
<!-- //for bootstrap working -->
<link href="//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800" rel="stylesheet">
<link href="//fonts.googleapis.com/css?family=Lato:400,100,100italic,300,300italic,400italic,700,900,900italic,700italic" rel="stylesheet" type="text/css">
'.$plugin.'

<style>
.phone {
width: 30px;
height: 30px;
background: url(../images/sprite-inner.1.0.6.png) -110px -180px;
margin: -7px 5px 0 5px;
}
.pinbb {
width: 30px;
height: 30px;
background: url(../images/sprite-inner.1.0.6.png) -265px -259px;
margin: -7px 5px 0 5px;
}
.wechat {
width: 30px;
height: 30px;
background: url(../images/sprite-inner.1.0.6.png) -265px -290px;
margin: -7px 5px 0 5px;
}
.email {
width: 30px;
height: 30px;
background: url(../images/sprite-inner.1.0.6.png) -139px -180px;
margin: -7px 5px 0 5px;
}
.harga {
width: 285px;
height: 65px;
background: url(../images/sprite-inner.1.0.6.png) 0 -71px no-repeat;
margin-left: -25px;
position: absolute;
}
.subharga{
float: right;
min-width: 200px;
font-size: 20px;
font-weight: bold;
color: #f93131;
margin: 23px 30px 0 0;
text-shadow: 1px 2px 2px rgba(255,255,255,.7);
}
</style>

<style>
#menu_member_page1,#menu_member_page2,#menu_member_page3{
color: rgb(249, 49, 49);
    background: none repeat scroll 0% 0% rgb(224, 224, 224);
    border: 1px solid rgb(208, 208, 208); text-shadow: none;
color: inherit;
padding: 8px 15px;
font-weight: bold;
text-decoration: none;
display: block;
background: none repeat scroll 0% 0% rgb(238, 238, 238);
border-radius: 0px 0px 0px 0px;
border: 1px solid rgb(221, 221, 221);
margin: 0px 0px -1px;
transition: all 200ms linear 0s;
font-size: inherit;
}
.list_iklan:hover
{
background: #DDD;
}
.kondisi{
  display: inline-block;
  background-color: #cbcbcb;
  color: #000;
  text-shadow: 0 1px 0 rgba(255,255,255,0.75);
  padding: 1px 4px 2px;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
  font-size: 10.998px;
  font-weight: bold;
  line-height: 14px;
  color: #fff;
  text-shadow: 0 -1px 0 rgba(0,0,0,0.25);
  white-space: nowrap;
  vertical-align: baseline;
  background-color: #999;
}
.container, .panel{
    margin-right: auto;
    margin-left: auto;
  width: 1170px;
}
.nav__primary .sf-menu > ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}
.sf-menu > li > a { font: bold 17px/55px Open Sans;  color:#181818; }
.nav.footer-nav a { font: normal 14px/20px Arial, Helvetica, sans-serif;  color:#181818; }
</style>
		
	<script type="text/javascript">
		// Init navigation menu
		jQuery(function(){
		// main navigation init
			jQuery(\'ul.sf-menu\').superfish({
				delay: 1000, // the delay in milliseconds that the mouse can remain outside a sub-menu without it closing
				animation: {
					opacity: "show",
					height: "show"
				}, // used to animate the sub-menu open
				speed: "normal", // animation speed 
				autoArrows: false, // generation of arrow mark-up (for submenu)
				disableHI: true // to disable hoverIntent detection
			});

		//Zoom fix
		//IPad/IPhone
			var viewportmeta = document.querySelector && document.querySelector(\'meta[name="viewport"]\'),
				ua = navigator.userAgent,
				gestureStart = function () {
					viewportmeta.content = "width=device-width, minimum-scale=0.25, maximum-scale=1.6";
				},
				scaleFix = function () {
					if (viewportmeta && /iPhone|iPad/.test(ua) && !/Opera Mini/.test(ua)) {
						viewportmeta.content = "width=device-width, minimum-scale=1.0, maximum-scale=1.0";
						document.addEventListener("gesturestart", gestureStart, false);
					}
				};
			scaleFix();
		})
	</script>
<style type="text/css">
	

.nav__primary .sf-menu {
	float:left;
	border-left:1px solid #e5e5e5;
}
.nav__primary .sf-menu > li {
	margin:-1px 0 0 0;
	background:none;
	border:none;
}
.nav__primary .sf-menu > li > a {
	height:55px;
	line-height:55px;
	padding:0 20px;
	border-left:none;
	border-right:1px solid #e5e5e5;
	text-transform:uppercase;
	font-weight:600;
	background:none;
	border-top:3px solid transparent;
}
.nav__primary .sf-menu > li > a:hover, .nav__primary .sf-menu > li.sfHover > a, .nav__primary .sf-menu > li.current-menu-item > a, .nav__primary .sf-menu > li.current_page_item > a {
	border-top:3px solid #f93131;
	color:#f93131;
}
.nav__primary .sf-menu ul {
	background:#272727;
	padding:20px 30px;
	border-top:3px solid #f93131;
	
}
.nav__primary .sf-menu ul li {
	border:none;
	background:none;
}
.nav__primary .sf-menu ul li a {
	line-height:20px;
	padding:6px 0;
	font-size:14px;
	color:#787878;
	border:none;
	text-align:left;
	text-decoration:none;
}
.nav__primary .sf-menu ul li a:before {
	font-family:FontAwesome;
	/*content:"\f0a9";*/
	font-size:18px;
	color:#fff;
	display:inline-block;
	margin:-4px 8px 0 0;
	vertical-align:middle;
	-webkit-transition:all 200ms linear;
	-moz-transition:all 200ms linear;
	-o-transition:all 200ms linear;
	transition:all 200ms linear;
}
.nav__primary .sf-menu ul > li > a:hover, .nav__primary .sf-menu ul > li.sfHover > a, .nav__primary .sf-menu ul > li.current-menu-item > a, .nav__primary .sf-menu ul > li.current_page_item > a {
	color:#f93131;
	background:none !important;
}
.nav__primary .sf-menu ul > li > a:hover:before, .nav__primary .sf-menu ul > li.sfHover > a:before, .nav__primary .sf-menu ul > li.current-menu-item > a:before, .nav__primary .sf-menu ul > li.current_page_item > a:before { color:#f93131; }
.nav__primary .sf-menu ul > li:hover ul, .nav__primary .sf-menu ul > li.sfHover ul {
	left:202px;
	top:-23px;
}
.nav__primary .sf-menu .sf-sub-indicator {
	background:none;
	width:auto;
	height:auto;
	position:absolute;
	right:2px;
	top:50%;
	margin:-8px 0 0 0;
	width:14px;
	height:20px;
}
.nav__primary .sf-menu .sf-sub-indicator:after {
	content:"\f107";
	font-family:FontAwesome;
	text-indent:0;
	position:absolute;
	left:0;
	top:0;
	line-height:20px;
	font-size:14px;
}
.nav__primary .sf-menu ul .sf-sub-indicator:after { content:"\f105"; }
.sf-menu, .sf-menu * {
	margin:0;
	padding:0;
	list-style:none;
}
.sf-menu { line-height:1.0; }
.sf-menu ul {
	display:none;
	position:absolute;
	top:-999em;
	width:10em;
}
.sf-menu ul li { width:100%; }
.sf-menu li:hover { visibility:inherit; }
.sf-menu li {
	position:relative;
	float:left;
}
.sf-menu a {
	position:relative;
	display:block;
}
.sf-menu li:hover ul, .sf-menu li.sfHover ul {
	top:100%;
	left:0;
	z-index:99;
}
ul.sf-menu li:hover li ul, ul.sf-menu li.sfHover li ul { top:-999em; }
ul.sf-menu li li:hover ul, ul.sf-menu li li.sfHover ul {
	top:0;
	left:100%;
}
ul.sf-menu li li:hover li ul, ul.sf-menu li li.sfHover li ul { top:-999em; }
ul.sf-menu li li li:hover ul, ul.sf-menu li li li.sfHover ul {
	top:0;
	left:10em;
}
.sf-menu { float:right; }
.sf-menu > li {
	background:#ddd;
	text-align:center;
}
.sf-menu > li > a {
	padding:10px 12px;
	border-top:1px solid #DDD;
	border-left:1px solid #fff;
	color:#e30202;
	text-decoration:none;
}
.sf-menu li .desc {
	display:block;
	font-size:0.9em;
}
.sf-menu li li { background:#AABDE6; }
.sf-menu li li a {
	padding:10px 12px;
	border-top:1px solid #DDD;
	border-left:1px solid #fff;
	color:#e30202;
	text-decoration:none;
}
.sf-menu > li > a:hover, .sf-menu > li.sfHover> a, .sf-menu > li.current-menu-item > a, .sf-menu > li.current_page_item > a { background:#CFDEFF; }
.sf-menu li li > a:hover, .sf-menu li li.sfHover > a, .sf-menu li li.current-menu-item > a, .sf-menu li li.current_page_item > a { background:#CFDEFF; }
.sf-menu a.sf-with-ul {
	padding-right:2.25em;
	min-width:1px;
}
</style>
<script language="javascript">
//confirm message for logout.
function logout() {
    if (confirm("Anda yakin untuk keluar ?")) {
	   
       window.location.href = "logout.php";
		}
}
</script>

<script type="text/javascript">
jQuery(document).ready(function(){

	// hide #back-top first
	jQuery("#back-top").hide();
	
	// fade in #back-top
	jQuery(function () {
		jQuery(window).scroll(function () {
			if ($(this).scrollTop() > 500) {
				jQuery(\'#back-top\').fadeIn();
			} else {
				jQuery(\'#back-top\').fadeOut();
			}
		});

		// scroll body to 0px on click
		jQuery(\'#back-top a\').click(function () {
			jQuery(\'body,html\').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
		
		jQuery(\'#search-form_is\').click(function () {
		    document.getElementById(\'search-header\').submit();
		    return false;
		});
	});

});
</script>
<style type="text/css">
  /*Style Notifikasi*/
  .bubble
  {
    background: #e02424;    
    margin-left:7px;
    right: 5px;
    top: -5px;
    padding: 2px 6px;
    color: #fff;
    font: bold .9em Tahoma, Arial, Helvetica;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
  }

</style>
</head>
<body>
<!-- header -->
<div class="header" id="home">
	<div class="container">
		<ul>';
		if($loggedin = logged_in()){
			$sql = mysql_query("SELECT * FROM `notif_log` WHERE `status`='1' AND `id_member`='".$loggedin["id_member"]."'");
			$array = mysql_num_rows($sql);
			$jumlah= json_encode($array);
$template .='
		    <li> <a href="member_home.php"><i class="fa fa-unlock-alt" aria-hidden="true"></i> '.$loggedin["username"].' </a></li>
			<li> <a href="javascript:logout();"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Keluar </a></li>
			<li><i class="fa fa-phone" aria-hidden="true"></i> +62 878-5905-2006</li>
			<li><a href="notif_page.php">Notifikasi<span class="bubble" id="jumlah_pesanan">'.$jumlah.'</span></a></li>
';
		}else{
$template .='
		    <li> <a href="#" data-toggle="modal" data-target="#myModal"><i class="fa fa-unlock-alt" aria-hidden="true"></i> Masuk </a></li>
			<li> <a href="signup.php"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Daftar </a></li>
			<li><i class="fa fa-phone" aria-hidden="true"></i> +62 878-5905-2006</li>
			<li><i class="fa fa-envelope-o" aria-hidden="true"></i> <a href="mailto:toko@xtremewebsolution.net">toko@xtremewebsolution.net</a></li>
';
		}
$template .='
		</ul>
	</div>
</div>
<!-- //header -->
<!-- header-bot -->
<div class="header-bot">
	<div class="header-bot_inner_wthreeinfo_header_mid">
		
		
		<!-- header-bot -->
			<div class="col-md-4 logo_agile">
			<a href="index.php"><img src="images/logo.png"></a>
				<!--<h1><a href="index.html"><span>R</span>ainiers <i class="fa fa-shopping-bag top_logo_agile_bag" aria-hidden="true"></i></a></h1>-->
			</div>
        <!-- header-bot -->

			<div class="col-md-8 header-middle">
			<form id="search-header" method="get" action="list_produk.php" accept-charset="utf-8">';
	
	$ip      = $_SERVER['REMOTE_ADDR']; // Mendapatkan IP komputer user
	$tanggal = date("Ymd"); // Mendapatkan tanggal sekarang
	$waktu   = time(); //
   
	 // Mencek berdasarkan IPnya, apakah user sudah pernah mengakses hari ini
	$s = mysql_query("SELECT * FROM `statistik` WHERE `ip`='$ip' AND `tanggal`='$tanggal'");
	
	// Kalau belum ada, simpan data user tersebut ke database
	if(mysql_num_rows($s) == 0){
		mysql_query("INSERT INTO `statistik`(`ip`, `tanggal`, `hits`, `online`) VALUES('$ip','$tanggal','1','$waktu')");
	}
	// Jika sudah ada, update
	else{
		mysql_query("UPDATE `statistik` SET `hits`=hits+1, `online`='$waktu' WHERE `ip`='$ip' AND `tanggal`='$tanggal'");
	}	
	
	if(isset($_GET['q'])){ if($_GET['q']!='' ){ $template .= '<input type="search" name="q" id="keyword"  value="'.$_GET['q'].'" placeholder="" >'; } else{ $template .= '<input type="search" name="q" placeholder="Search here..." >'; } }
		else{ $template .= '<input type="search" name="q" placeholder="Search here..." >'; }
		 $queryProv = "SELECT * FROM `provinsi` ORDER BY `idProvinsi` ASC";
    $resultProv = mysql_query($queryProv) or die (mysql_error());
    
	
	if(isset($_GET['prov'])){
	    if($_GET['prov']!=''){
		$template .='<input type="hidden" name="prov" value="'.$_GET['prov'].'">';
	    $querykota = "SELECT * FROM `kota` WHERE idProvinsi='$_GET[prov]' ORDER BY namaKota DESC";
	    $resultkota = mysql_query($querykota);
	    $template.='
	    <select name="kota" style="float: left;"> <option value="" selected>Pilih Kota</option>';
		
		
		while ($rowkota = mysql_fetch_assoc($resultkota)){
		    if($rowkota['idKota'] == $_GET['kota']){
			$template.=' <option value="' . $rowkota["idKota"] .'" selected>' . $rowkota["namaKota"] .'</option>'; 
		    }else{
			$template.=' <option value="' . $rowkota["idKota"] .'">' . $rowkota["namaKota"] .'</option>'; 
		    }
		}
		    $template.='</select>';
	}else{$template.='<select name="prov" style="float: left;"> <option value="" selected>Pilih Provinsi</option>';
		while ($rowProv = mysql_fetch_assoc($resultProv)){
		    $template.=' <option value="' . $rowProv["idProvinsi"] .'">' . $rowProv["namaProvinsi"] .'</option>';
		}
	$template.='</select>'; } }
	else{ $template.='<select name="prov" style="float: left;"> <option value="" selected>Pilih Provinsi</option>';  while ($rowProv = mysql_fetch_assoc($resultProv)){ $template.=' <option value="' . $rowProv["idProvinsi"] .'">' . $rowProv["namaProvinsi"] .'</option>'; } $template.='</select>'; }
		
    $queryKategori = "select * FROM `kategori` WHERE `level`='0' ORDER BY `name_kategori` ASC";
    $resultKategori = mysql_query($queryKategori) or die (mysql_error());
       	
	if(isset($_GET['cat'])){
	    if(($_GET['cat'])!=''){
		$template.='<select name="cat" style="float: left;"><option value="" selected>Pilih Kategori</option>';
		while ($rowKategori = mysql_fetch_assoc($resultKategori)) {
		    if($rowKategori['id_kategori'] == $_GET['cat']){
			$template.='<option value="'.$rowKategori["id_kategori"].'" selected>'.$rowKategori["name_kategori"].'</option>';
		    }else{
			$template.='<option value="'.$rowKategori["id_kategori"].'">'.$rowKategori["name_kategori"].'</option>';
		    }
		    
		}
		
		$template.='</select>';
	    }else{ $template.='<select name="cat" style="float: left;"><option value="" selected>Pilih Kategori</option>';
		while ($rowKategori = mysql_fetch_assoc($resultKategori)) {
		    $template.=' <option value="'.$rowKategori["id_kategori"].'">'.$rowKategori["name_kategori"].'</option>';
		}
		$template.='</select>';
	    }
	}else{ $template.='<select name="cat" style="float: left;"><option value="" selected>Pilih Kategori</option>';
	    while ($rowKategori = mysql_fetch_assoc($resultKategori)) {
		$template.=' <option value="'.$rowKategori["id_kategori"].'">'.$rowKategori["name_kategori"].'</option>';
	    }
	    $template.='</select>';
	}
$template .='
					
					<input type="submit" value=" ">
					<a href="" id="search-form_is" onclick="document.getElementById(\'search-header\').submit()"><i class="icon-search"></i></a>
				<div class="clearfix"></div>
			</form>

		</div>
		<div class="clearfix"></div>
	</div>
</div>
<!-- //header-bot -->

<!-- banner -->
<div class="ban-top">
	<div class="container">
		<div class="top_nav_left">
			<nav class="navbar navbar-default">
			  <div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
				  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse menu--shylock" id="bs-example-navbar-collapse-1">
				  <ul class="nav navbar-nav menu__list">
					
					';
$pasang_iklan = ($loggedin["group"]=="admin_toko") ? '<li><a href="pasang_iklan.php">Tambah Produk</a></li>' : '';
$penjualan= ($loggedin["group"]=="admin_toko") ? '<li><a href="penjualan.php">Penjualan</a></li>' : '';
$menulist  ="";
$menu_list = ($title=="Rainiers Online - Member Page") ? '<li class="active menu__item menu__item--current"><a class="menu__link" href="member_home.php">Dashboard <span class="sr-only">(current)</span></a></li>' : '<li class=" menu__item"><a class="menu__link" href="member_home.php">Dashboard </a></li>';
$menu_list .= ($title=="Rainiers Online - Iklan Aktif" || $title=="Rainiers Online - Pasang Iklan") ? '<li class="active dropdown menu__item menu__item--current">
						<a href="#" class="dropdown-toggle menu__link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Produk <span class="caret"></span></a>
							<ul class="dropdown-menu multi-column columns-1">
								<div class="agile_inner_drop_nav_info">
									<div class="multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="p_iklan_aktif.php">Produk Iklan Aktif</a></li>
											'.$pasang_iklan.'
										</ul>
									</div>
									<div class="clearfix"></div>
								</div>
							</ul>
					</li>' : '<li class="dropdown menu__item ">
						<a href="#" class="dropdown-toggle menu__link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Produk <span class="caret"></span></a>
							<ul class="dropdown-menu multi-column columns-2">
								<div class="agile_inner_drop_nav_info">
									<div class="">
										<ul class="multi-column-dropdown">
											<li><a href="iklan_aktif.php">Produk Iklan Aktif</a></li>
											'.$pasang_iklan.'
										</ul>
									</div>
									<div class="clearfix"></div>
								</div>
							</ul>
					</li>';
$menu_list .= ($title=="Rainiers Online - Penjualan" || $title=="Rainiers Online - Pembelian" || $title=="Rainiers Online - Info Pembayaran" || $title=="Rainiers Online - Detail Pembelian" || $title=="Rainiers Online - Detail Status Pembelian" || $title=="Rainiers Online - Form Konfirmasi") ? '<li class="active dropdown menu__item menu__item--current">
						<a href="#" class="dropdown-toggle menu__link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Transaksi <span class="caret"></span></a>
							<ul class="dropdown-menu multi-column columns-1">
								<div class="agile_inner_drop_nav_info">
									<div class="multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="pembelian.php">Pembelian</a></li>
											'.$penjualan.'
										</ul>
									</div>
									<div class="clearfix"></div>
								</div>
							</ul>
					</li>' : '<li class="dropdown menu__item ">
						<a href="#" class="dropdown-toggle menu__link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Transaksi <span class="caret"></span></a>
							<ul class="dropdown-menu multi-column columns-2">
								<div class="agile_inner_drop_nav_info">
									<div class="">
										<ul class="multi-column-dropdown">
											<li><a href="pembelian.php">Pembelian</a></li>
											'.$penjualan.'
										</ul>
									</div>
									<div class="clearfix"></div>
								</div>
							</ul>
					</li>';	
$template .=''.$menu_list.'
					<!--<li class="dropdown menu__item">
						<a href="#" class="dropdown-toggle menu__link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Fashion <span class="caret"></span></a>
							<ul class="dropdown-menu multi-column columns-3">
								<div class="agile_inner_drop_nav_info">
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="#">Clothing</a></li>
											<li><a href="#">Wallets</a></li>
											<li><a href="#">Footwear</a></li>
											<li><a href="#">Watches</a></li>
											<li><a href="#">Accessories</a></li>
											<li><a href="#">Bags</a></li>
											<li><a href="#">Caps & Hats</a></li>
										</ul>
									</div>
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="#">Jewellery</a></li>
											<li><a href="#">Sunglasses</a></li>
											<li><a href="#">Perfumes</a></li>
											<li><a href="#">Beauty</a></li>
											<li><a href="#">Shirts</a></li>
											<li><a href="#">Sunglasses</a></li>
											<li><a href="#">Swimwear</a></li>
										</ul>
									</div>
									<div class="col-sm-6 multi-gd-img multi-gd-text ">
										<a href="#"><img src="images/top1.jpg" alt=" "/></a>
									</div>
									<div class="clearfix"></div>
								</div>
							</ul>
					</li>
					
					<li class="dropdown menu__item">
						<a href="#" class="dropdown-toggle menu__link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Electronic <span class="caret"></span></a>
							<ul class="dropdown-menu multi-column columns-3">
								<div class="agile_inner_drop_nav_info">
									<div class="col-sm-6 multi-gd-img1 multi-gd-text ">
										<a href="#"><img src="images/top2.jpg" alt=" "/></a>
									</div>
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="#">Handphone</a></li>
											<li><a href="#">Tablet</a></li>
											<li><a href="#">Komputer</a></li>
											<li><a href="#">Laptop</a></li>
											<li><a href="#">TV</a></li>
											<li><a href="#">Audio</a></li>
											<li><a href="#">Digital Camera</a></li>
										</ul>
									</div>
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="#">CCTV</a></li>
											<li><a href="#">Fiber Optic</a></li>
											<li><a href="#">Wireless</a></li>
											<li><a href="#">Modem</a></li>
											<li><a href="#">Antenna</a></li>
											<li><a href="#">Projector</a></li>
											<li><a href="#">Alarm</a></li>
										</ul>
									</div>
									<div class="clearfix"></div>
								</div>
							</ul>
					</li>
					
					<li class="dropdown menu__item">
						<a href="#" class="dropdown-toggle menu__link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Automotive <span class="caret"></span></a>
							<ul class="dropdown-menu multi-column columns-3">
								<div class="agile_inner_drop_nav_info">
									
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
										    <li><a href="#">Car</a></li>
											<li><a href="#">Motorcycle</a></li>
											<li><a href="#">Safety & Care</a></li>

										</ul>
									</div>
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="#">Car Parts</a></li>
											<li><a href="#">Motor Parts </a></li>
											<li><a href="#">Car Accessories</a></li>
											<li><a href="#">Motor Accessories</a></li>	
										</ul>
									</div>
									<div class="col-sm-6 multi-gd-img1 multi-gd-text ">
										<a href="#"><img src="images/top3.jpg" alt=" "/></a>
									</div>
									<div class="clearfix"></div>
								</div>
							</ul>
					</li>
					
					<li class=" menu__item"><a class="menu__link" href="#">About</a></li>
					
					<li class=" menu__item"><a class="menu__link" href="#">Contact</a></li> -->
				  </ul>
				</div>
			  </div>
			</nav>	
		</div>
		<!--<div class="top_nav_right">
			<div class="wthreecartaits wthreecartaits2 cart cart box_1">
					<form action="chart.php" method="post" class="last">
						<input type="hidden" name="cmd" value="_cart">
						<input type="hidden" name="display" value="1">
						<button class="w3view-cart" type="submit" name="submit" value=""><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></button>
					</form> 
  
						</div>
		</div>-->
		<div class="clearfix"></div>
	</div>
</div>
<!-- //banner-top -->
';


include_once '../gxplugin/securimage/securimage.php';
$securimage = new Securimage();

if (isset($_POST['login'])) { // Check if submit button has been pressed.#fdf1f1

	/** CHECK COOKIES **/
	echo check_phpsessid();

	$_POST = protection($_POST); // Protect the $_POST variable.
	$_GET = protection($_GET); // Protect the $_GET variable.

		if(empty($_POST['username']) || empty($_POST['password'])) { // Check if the form fields are empty or not.
			echo '<script language="JavaScript">
			  alert("Login failed. Check your username and/or password.");
			  </script>'; // If there empty show error message.
		} else {
			if($securimage->check($_POST['captcha_code']) == false) {
            echo "<script language='JavaScript'>
               alert('The security code entered was incorrect.');
			window.history.go(-1);
               </script>";
			}else{
			$username = isset($_POST['username']) ? mysql_real_escape_string(strip_tags($_POST['username'])) : "";
			$redirect = isset($_GET['r']) ? mysql_real_escape_string(strip_tags($_GET['r'])) : "";
			$chkuser = mysql_query("SELECT * FROM `users` WHERE (`username` = '".$username."' OR `email` = '".$username."') && `password` = '".md5($_POST['password'])."' AND `group` != 'admin' LIMIT 0, 1" ); // Check if the username and password are correct.
				if(mysql_num_rows($chkuser)) { // Check if they are correct
					$vcu = mysql_fetch_array($chkuser); // Get the information
					$jam = date("H");
					$waktu = date("Y-m-d ").($jam-1).date(":i:s");
					
					$results = mysql_query("INSERT into `sessions` (`sess_id`, `uid`, `logged`, `ip`, `waktu`) values ('".$_COOKIE['PHPSESSID']."', '".$vcu['id_user']."', '0', '".$_SERVER['REMOTE_ADDR']."', '$waktu') "); // Insert the session id and user id into the sessions table to create the login.
					
					$sql = mysql_query("UPDATE `users` SET `online` = '0', `lastvisit` = now()  WHERE `id_user` = '".$vcu['id_user']."' ");
					
						if($results) {
							if($sql) { // If it submitted it then success.
							    $_SESSION['user'] = $_POST['username']; 
								if($redirect != "") { // If $_GET['r'] is blank redirect the user to index.php after login if not redirect the user to the url indicated in login.php?r=http://www.google.com
								    header("Location: ".$_GET['r']);
								} elseif($redirect == "") {
								    header("Location:".$_SERVER['PHP_SELF']);
								}
							} else { // If couldnt submit into sessions table then show error message
									echo '<script language="JavaScript">
									  alert("Unknown Error sql.");
									</script>';
							}	
						} else { // If couldnt submit into sessions table then show error message
						  
						  echo '<script language="JavaScript">
							alert("Unknown Error result.");
						      </script>';
						}
		           
				} else{
				    echo '<script language="JavaScript">
							alert("Incorrect Username Or Password.");
						      </script>';
				}
			}
		}
}
$template .='
<!-- login -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
						<div class="modal-body modal-body-sub_agile">
						<div class="col-md-8 modal_body_left modal_body_left1">
						<h3 class="agileinfo_sign">Masuk <span>Sekarang</span></h3>
						<form action="#" method="post">
							<div class="styled-input agile-styled-input-top">
								<input type="text" name="username" required="">
								<label>Username</label>
								<span></span>
							</div>
							<div class="styled-input">
								<input name="password" type="password" required="">
								<label>Password</label>
								<span></span>
							</div>
							<div class="styled-input">
								<input type="hidden" name="nilai" value="'.$_SESSION['nilai_login'].'"></span>
							</div>
							<input type="submit" name="login" value="Masuk">
						</form>
						  <ul class="social-nav model-3d-0 footer-social w3_agile_social top_agile_third">
															<li><a href="#" class="facebook">
																  <div class="front"><i class="fa fa-facebook" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-facebook" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="twitter"> 
																  <div class="front"><i class="fa fa-twitter" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-twitter" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="instagram">
																  <div class="front"><i class="fa fa-instagram" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-instagram" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="pinterest">
																  <div class="front"><i class="fa fa-linkedin" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-linkedin" aria-hidden="true"></i></div></a></li>
														</ul>
														<div class="clearfix"></div>
														<p><a href="signup.php" data-toggle="modal" data-target="#myModal2" > Belum punya akun ?</a></p>

						</div>
						<div class="col-md-4 modal_body_right modal_body_right1">
							<img src="images/log_pic.jpg" alt=" "/>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<!-- //Modal content-->
			</div>
		</div>
<!-- //login -->
<!-- /banner_bottom_agile_info 
<div class="page-head_agile_info_w3l">
		<div class="container">
			<h3>Member <span>Page  </span></h3>
			<!--/w3_short
				 <div class="services-breadcrumb">
						<div class="agile_inner_breadcrumb">

						   <ul class="w3_short">
								<li><a href="index.php">Home</a><i>|</i></li>
								<li>Member Page</li>
							</ul>
						 </div>
				</div>
	   <!--//w3_short
	</div>
</div>-->
'.$content.'
<div class="coupons">
		<div class="coupons-grids text-center">
			<div class="w3layouts_mail_grid">
				
				<div class="clearfix"> </div>
			</div>

		</div>
</div>
<!--grids-->
<!-- footer -->
<div class="footer">
	<div class="footer_agile_inner_info_w3l">
		<div class="col-md-3 footer-left">
			<h2><a href="index.html"><span>R</span>ainiers </a></h2>
			<p>One Stop Shopping<br>Online Store in Malang</p>
			<ul class="social-nav model-3d-0 footer-social w3_agile_social two">
															<li><a href="#" class="facebook">
																  <div class="front"><i class="fa fa-facebook" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-facebook" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="twitter"> 
																  <div class="front"><i class="fa fa-twitter" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-twitter" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="instagram">
																  <div class="front"><i class="fa fa-instagram" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-instagram" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="pinterest">
																  <div class="front"><i class="fa fa-linkedin" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-linkedin" aria-hidden="true"></i></div></a></li>
														</ul>
		</div>
		<div class="col-md-9 footer-right">
			<div class="sign-grds">
				<div class="col-md-4 sign-gd">
					<h4>Informasi <span>Kami</span> </h4>
					<ul>
						<li><a href="index.html">Home</a></li>
						<li><a href="#">Tentang Kami</a></li>
						<li><a href="#">Syarat dan Ketentuan</a></li>
						<li><a href="#">Panduan Berlanja</a></li>
						<li><a href="#">Membership</a></li>
						<li><a href="#">Kontak Kami</a></li>
					</ul>
				</div>
				
				<div class="col-md-5 sign-gd-two">
					<h4>Store <span>Information</span></h4>
					<div class="w3-address">
						<div class="w3-address-grid">
							<div class="w3-address-left">
								<i class="fa fa-phone" aria-hidden="true"></i>
							</div>
							<div class="w3-address-right">
								<h6>Mobile Phoner</h6>
								<p>+62 878-5905-2006</p>
							</div>
							<div class="clearfix"> </div>
						</div>
						<div class="w3-address-grid">
							<div class="w3-address-left">
								<i class="fa fa-envelope" aria-hidden="true"></i>
							</div>
							<div class="w3-address-right">
								<h6>Email </h6>
								<p><a href="mailto:toko@xtremewebsolution.net"> toko@xtremewebsolution.net</a></p>
							</div>
							<div class="clearfix"> </div>
						</div>
						<div class="w3-address-grid">
							<div class="w3-address-left">
								<i class="fa fa-map-marker" aria-hidden="true"></i>
							</div>
							<div class="w3-address-right">
								<h6>Alamat</h6>
								<p>Bukit Dieng MA 1B<br>Malang, East Java 
								
								</p>
							</div>
							<div class="clearfix"> </div>
						</div>
					</div>
				</div>
				<!--<div class="col-md-3 sign-gd flickr-post">
					<h4>Flickr <span>Posts</span></h4>
					<ul>
						<li><a href="#"><img src="images/t1.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t2.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t3.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t4.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t1.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t2.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t3.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t2.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t4.jpg" alt=" " class="img-responsive" /></a></li>
					</ul>
				</div>-->
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="clearfix"></div>
			<div class="agile_newsletter_footer">
					<div class="col-sm-6 newsleft">
				<h3>BERLANGGANAN INFORMASI TERBARU KAMI</h3>
			</div>
			<div class="col-sm-6 newsright">
				<form action="#" method="post">
					<input type="email" placeholder="Enter your email..." name="email" required="">
					<input type="submit" value="Submit">
				</form>
			</div>

		<div class="clearfix"></div>
	</div>
		<p class="copy-right">&copy 2017 Rainiers Online. All rights reserved </p>
	</div>
</div>
<!-- //footer -->

<!-- login -->
			<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content modal-info">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>						
						</div>
						<div class="modal-body modal-spa">
							<div class="login-grids">
								<div class="login">
									<div class="login-bottom">
										<h3>Daftar Gratis Sekarang</h3>
										<form>
											<div class="sign-up">
												<h4>Email :</h4>
												<input type="text" value="Type here" onfocus="this.value = \'\';" onblur="if (this.value == \'\') {this.value = \'Type here\';}" required="">	
											</div>
											<div class="sign-up">
												<h4>Password :</h4>
												<input type="password" value="Password" onfocus="this.value = \'\';" onblur="if (this.value == \'\') {this.value = \'Password\';}" required="">
												
											</div>
											<div class="sign-up">
												<h4>Re-type Password :</h4>
												<input type="password" value="Password" onfocus="this.value = \'\';" onblur="if (this.value == \'\') {this.value = \'Password\';}" required="">
												
											</div>
											<div class="sign-up">
												<input type="submit" value="REGISTER NOW" >
											</div>
											
										</form>
									</div>
									<div class="login-right">
										<h3>Masuk dengan akun Anda</h3>
										<form>
											<div class="sign-in">
												<h4>Email :</h4>
												<input type="text" value="Type here" onfocus="this.value = \'\';" onblur="if (this.value == \'\') {this.value = \'Type here\';}" required="">	
											</div>
											<div class="sign-in">
												<h4>Password :</h4>
												<input type="password" value="Password" onfocus="this.value = \'\';" onblur="if (this.value == \'\') {this.value = \'Password\';}" required="">
												<a href="#">Forgot password?</a>
											</div>
											<div class="single-bottom">
												<input type="checkbox"  id="brand" value="">
												<label for="brand"><span></span>Remember Me.</label>
											</div>
											<div class="sign-in">
												<input type="submit" value="SIGNIN" >
											</div>
										</form>
									</div>
									<div class="clearfix"></div>
								</div>
								<p>By logging in you agree to our <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
<!-- //login -->
<a href="#home" class="scroll" id="toTop" style="display: block;"> <span id="toTopHover" style="opacity: 1;"> </span></a>

<!-- js -->
<script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
<!-- //js -->
<script src="js/modernizr.custom.js"></script>
	<!-- Custom-JavaScript-File-Links --> 
	<!-- cart-js 
	<script src="js/minicart.min.js"></script>
<script>
	// Mini Cart
	paypal.minicart.render({
		action: \'#\'
	});

	if (~window.location.search.indexOf(\'reset=true\')) {
		paypal.minicart.reset();
	}
</script>

	<!-- //cart-js --> 
<!-- script for responsive tabs -->						
<script src="js/easy-responsive-tabs.js"></script>
<script>
	$(document).ready(function () {
	$(\'#horizontalTab\').easyResponsiveTabs({
	type: \'default\', //Types: default, vertical, accordion           
	width: \'auto\', //auto or any width like 600px
	fit: true,   // 100% fit in a container
	closed: \'accordion\', // Start closed if in accordion view
	activate: function(event) { // Callback function if tab is switched
	var $tab = $(this);
	var $info = $(\'#tabInfo\');
	var $name = $(\'span\', $info);
	$name.text($tab.text());
	$info.show();
	}
	});
	$(\'#verticalTab\').easyResponsiveTabs({
	type: \'vertical\',
	width: \'auto\',
	fit: true
	});
	});
</script>
<!-- //script for responsive tabs -->		
<!-- stats -->
	<script src="js/jquery.waypoints.min.js"></script>
	<script src="js/jquery.countup.js"></script>
	<script>
		$(\'.counter\').countUp();
	</script>
<!-- //stats -->
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/jquery.easing.min.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){		
			event.preventDefault();
			$(\'html,body\').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script>
<!-- here stars scrolling icon -->
	<script type="text/javascript">
		$(document).ready(function() {
			/*
				var defaults = {
				containerID: \'toTop\', // fading element id
				containerHoverID: \'toTopHover\', // fading element hover id
				scrollSpeed: 1200,
				easingType: \'linear\' 
				};
			*/
								
			$().UItoTop({ easingType: \'easeOutQuart\' });
								
			});
	</script>
<!-- //here ends scrolling icon -->


<!-- for bootstrap working -->
<script type="text/javascript" src="js/bootstrap.js"></script>
</body>
</html>

';
    return $template;
}

function template_detail($content="",$title="",$plugin=""){
    
    $title      = ($title!="") ? $title : "";
    $plugin	    = ($plugin!="") ? $plugin : "";
    $content	= ($content!="") ? $content : "";
    
$template ='
<!DOCTYPE html>
<html>
<head>
<title>'.$title.'</title>
<!--/tags -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Elite Shoppy Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //tags -->
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />
<link href="css/font-awesome.css" rel="stylesheet"> 
<link href="css/easy-responsive-tabs.css" rel="stylesheet" type="text/css"/>
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />

<link rel="stylesheet" type="text/css" href="css/paging.css">
<!-- //for bootstrap working -->
<link href="//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800" rel="stylesheet">
<link href="//fonts.googleapis.com/css?family=Lato:400,100,100italic,300,300italic,400italic,700,900,900italic,700italic" rel="stylesheet" type="text/css">
'.$plugin.'

<script type="text/javascript">
jQuery(document).ready(function(){

	// hide #back-top first
	jQuery("#back-top").hide();
	
	// fade in #back-top
	jQuery(function () {
		jQuery(window).scroll(function () {
			if ($(this).scrollTop() > 500) {
				jQuery(\'#back-top\').fadeIn();
			} else {
				jQuery(\'#back-top\').fadeOut();
			}
		});

		// scroll body to 0px on click
		jQuery(\'#back-top a\').click(function () {
			jQuery(\'body,html\').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
		
		jQuery(\'#search-form_is\').click(function () {
		    document.getElementById(\'search-header\').submit();
		    return false;
		});
	});

});
</script>
<style type="text/css">
  /*Style Notifikasi*/
  .bubble
  {
    background: #e02424;    
    margin-left:7px;
    right: 5px;
    top: -5px;
    padding: 2px 6px;
    color: #fff;
    font: bold .9em Tahoma, Arial, Helvetica;
    -moz-border-radius: 3px;
    -webkit-border-radius: 3px;
    border-radius: 3px;
  }

</style>
</head>
<body>
<!-- header -->
<div class="header" id="home">
	<div class="container">
		<ul>';
		if($loggedin = logged_in()){
			$sql = mysql_query("SELECT * FROM `notif_log` WHERE `status`='1'  AND `id_member`='".$loggedin["id_member"]."'");
			$array = mysql_num_rows($sql);
			$jumlah= json_encode($array);
$template .='
		    <li> <a href="member_home.php"><i class="fa fa-unlock-alt" aria-hidden="true"></i> '.$loggedin["username"].' </a></li>
			<li> <a href="javascript:logout();"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Keluar </a></li>
			<li><i class="fa fa-phone" aria-hidden="true"></i> +62 878-5905-2006</li>
			<li><a href="notif_page.php">Notifikasi<span class="bubble" id="jumlah_pesanan">'.$jumlah.'</span></a></li>
';
		}else{
$template .='
		    <li> <a href="#" data-toggle="modal" data-target="#myModal"><i class="fa fa-unlock-alt" aria-hidden="true"></i> Masuk </a></li>
			<li> <a href="signup.php"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Daftar </a></li>
			<li><i class="fa fa-phone" aria-hidden="true"></i> +62 878-5905-2006</li>
			<li><i class="fa fa-envelope-o" aria-hidden="true"></i> <a href="mailto:toko@xtremewebsolution.net">toko@xtremewebsolution.net</a></li>
';
		}
$template .='
		</ul>
	</div>
</div>
<!-- //header -->
<!-- header-bot -->
<div class="header-bot">
	<div class="header-bot_inner_wthreeinfo_header_mid">
		
		
		<!-- header-bot -->
			<div class="col-md-4 logo_agile">
			<a href="index.php"><img src="images/logo.png"></a>
				<!--<h1><a href="index.html"><span>R</span>ainiers <i class="fa fa-shopping-bag top_logo_agile_bag" aria-hidden="true"></i></a></h1>-->
			</div>
        <!-- header-bot -->

			<div class="col-md-8 header-middle">
			<form id="search-header" method="get" action="list_produk.php" accept-charset="utf-8">';
	
	$ip      = $_SERVER['REMOTE_ADDR']; // Mendapatkan IP komputer user
	$tanggal = date("Ymd"); // Mendapatkan tanggal sekarang
	$waktu   = time(); //
   
	 // Mencek berdasarkan IPnya, apakah user sudah pernah mengakses hari ini
	$s = mysql_query("SELECT * FROM `statistik` WHERE `ip`='$ip' AND `tanggal`='$tanggal'");
	
	// Kalau belum ada, simpan data user tersebut ke database
	if(mysql_num_rows($s) == 0){
		mysql_query("INSERT INTO `statistik`(`ip`, `tanggal`, `hits`, `online`) VALUES('$ip','$tanggal','1','$waktu')");
	}
	// Jika sudah ada, update
	else{
		mysql_query("UPDATE `statistik` SET `hits`=hits+1, `online`='$waktu' WHERE `ip`='$ip' AND `tanggal`='$tanggal'");
	}	
	
	if(isset($_GET['q'])){ if($_GET['q']!='' ){ $template .= '<input type="search" name="q" id="keyword"  value="'.$_GET['q'].'" placeholder="" >'; } else{ $template .= '<input type="search" name="q" placeholder="Search here..." >'; } }
		else{ $template .= '<input type="search" name="q" placeholder="Search here..." >'; }
		 $queryProv = "SELECT * FROM `provinsi` ORDER BY `idProvinsi` ASC";
    $resultProv = mysql_query($queryProv) or die (mysql_error());
    
	
	if(isset($_GET['prov'])){
	    if($_GET['prov']!=''){
		$template .='<input type="hidden" name="prov" value="'.$_GET['prov'].'">';
	    $querykota = "SELECT * FROM `kota` WHERE idProvinsi='$_GET[prov]' ORDER BY namaKota DESC";
	    $resultkota = mysql_query($querykota);
	    $template.='
	    <select name="kota" style="float: left;"> <option value="" selected>Pilih Kota</option>';
		
		
		while ($rowkota = mysql_fetch_assoc($resultkota)){
		    if($rowkota['idKota'] == $_GET['kota']){
			$template.=' <option value="' . $rowkota["idKota"] .'" selected>' . $rowkota["namaKota"] .'</option>'; 
		    }else{
			$template.=' <option value="' . $rowkota["idKota"] .'">' . $rowkota["namaKota"] .'</option>'; 
		    }
		}
		    $template.='</select>';
	}else{$template.='<select name="prov" style="float: left;"> <option value="" selected>Pilih Provinsi</option>';
		while ($rowProv = mysql_fetch_assoc($resultProv)){
		    $template.=' <option value="' . $rowProv["idProvinsi"] .'">' . $rowProv["namaProvinsi"] .'</option>';
		}
	$template.='</select>'; } }
	else{ $template.='<select name="prov" style="float: left;"> <option value="" selected>Pilih Provinsi</option>';  while ($rowProv = mysql_fetch_assoc($resultProv)){ $template.=' <option value="' . $rowProv["idProvinsi"] .'">' . $rowProv["namaProvinsi"] .'</option>'; } $template.='</select>'; }
		
    $queryKategori = "select * FROM `kategori` WHERE `level`='0' ORDER BY `name_kategori` ASC";
    $resultKategori = mysql_query($queryKategori) or die (mysql_error());
       	
	if(isset($_GET['cat'])){
	    if(($_GET['cat'])!=''){
		$template.='<select name="cat" style="float: left;"><option value="" selected>Pilih Kategori</option>';
		while ($rowKategori = mysql_fetch_assoc($resultKategori)) {
		    if($rowKategori['id_kategori'] == $_GET['cat']){
			$template.='<option value="'.$rowKategori["id_kategori"].'" selected>'.$rowKategori["name_kategori"].'</option>';
		    }else{
			$template.='<option value="'.$rowKategori["id_kategori"].'">'.$rowKategori["name_kategori"].'</option>';
		    }
		    
		}
		
		$template.='</select>';
	    }else{ $template.='<select name="cat" style="float: left;"><option value="" selected>Pilih Kategori</option>';
		while ($rowKategori = mysql_fetch_assoc($resultKategori)) {
		    $template.=' <option value="'.$rowKategori["id_kategori"].'">'.$rowKategori["name_kategori"].'</option>';
		}
		$template.='</select>';
	    }
	}else{ $template.='<select name="cat" style="float: left;"><option value="" selected>Pilih Kategori</option>';
	    while ($rowKategori = mysql_fetch_assoc($resultKategori)) {
		$template.=' <option value="'.$rowKategori["id_kategori"].'">'.$rowKategori["name_kategori"].'</option>';
	    }
	    $template.='</select>';
	}
$template .='
					
					<input type="submit" value=" ">
					<a href="" id="search-form_is" onclick="document.getElementById(\'search-header\').submit()"><i class="icon-search"></i></a>
				<div class="clearfix"></div>
			</form>

		</div>
		<div class="clearfix"></div>
	</div>
</div>
<!-- //header-bot -->

<!-- banner -->
<div class="ban-top">
	<div class="container">
		<div class="top_nav_left">
			<nav class="navbar navbar-default">
			  <div class="container-fluid">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
				  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse menu--shylock" id="bs-example-navbar-collapse-1">
				  <ul class="nav navbar-nav menu__list">
					<li class="active menu__item menu__item--current"><a class="menu__link" href="index.php">Home <span class="sr-only">(current)</span></a></li>
					
					
					<li class="dropdown menu__item">
						<a href="#" class="dropdown-toggle menu__link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Fashion <span class="caret"></span></a>
							<ul class="dropdown-menu multi-column columns-3">
								<div class="agile_inner_drop_nav_info">
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="#">Clothing</a></li>
											<li><a href="#">Wallets</a></li>
											<li><a href="#">Footwear</a></li>
											<li><a href="#">Watches</a></li>
											<li><a href="#">Accessories</a></li>
											<li><a href="#">Bags</a></li>
											<li><a href="#">Caps & Hats</a></li>
										</ul>
									</div>
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="#">Jewellery</a></li>
											<li><a href="#">Sunglasses</a></li>
											<li><a href="#">Perfumes</a></li>
											<li><a href="#">Beauty</a></li>
											<li><a href="#">Shirts</a></li>
											<li><a href="#">Sunglasses</a></li>
											<li><a href="#">Swimwear</a></li>
										</ul>
									</div>
									<div class="col-sm-6 multi-gd-img multi-gd-text ">
										<a href="#"><img src="images/top1.jpg" alt=" "/></a>
									</div>
									<div class="clearfix"></div>
								</div>
							</ul>
					</li>
					
					<li class="dropdown menu__item">
						<a href="#" class="dropdown-toggle menu__link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Electronic <span class="caret"></span></a>
							<ul class="dropdown-menu multi-column columns-3">
								<div class="agile_inner_drop_nav_info">
									<div class="col-sm-6 multi-gd-img1 multi-gd-text ">
										<a href="#"><img src="images/top2.jpg" alt=" "/></a>
									</div>
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="#">Handphone</a></li>
											<li><a href="#">Tablet</a></li>
											<li><a href="#">Komputer</a></li>
											<li><a href="#">Laptop</a></li>
											<li><a href="#">TV</a></li>
											<li><a href="#">Audio</a></li>
											<li><a href="#">Digital Camera</a></li>
										</ul>
									</div>
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="#">CCTV</a></li>
											<li><a href="#">Fiber Optic</a></li>
											<li><a href="#">Wireless</a></li>
											<li><a href="#">Modem</a></li>
											<li><a href="#">Antenna</a></li>
											<li><a href="#">Projector</a></li>
											<li><a href="#">Alarm</a></li>
										</ul>
									</div>
									<div class="clearfix"></div>
								</div>
							</ul>
					</li>
					
					<li class="dropdown menu__item">
						<a href="#" class="dropdown-toggle menu__link" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Automotive <span class="caret"></span></a>
							<ul class="dropdown-menu multi-column columns-3">
								<div class="agile_inner_drop_nav_info">
									
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
										    <li><a href="#">Car</a></li>
											<li><a href="#">Motorcycle</a></li>
											<li><a href="#">Safety & Care</a></li>

										</ul>
									</div>
									<div class="col-sm-3 multi-gd-img">
										<ul class="multi-column-dropdown">
											<li><a href="#">Car Parts</a></li>
											<li><a href="#">Motor Parts </a></li>
											<li><a href="#">Car Accessories</a></li>
											<li><a href="#">Motor Accessories</a></li>	
										</ul>
									</div>
									<div class="col-sm-6 multi-gd-img1 multi-gd-text ">
										<a href="#"><img src="images/top3.jpg" alt=" "/></a>
									</div>
									<div class="clearfix"></div>
								</div>
							</ul>
					</li>
					
					<li class=" menu__item"><a class="menu__link" href="#">About</a></li>
					
					<li class=" menu__item"><a class="menu__link" href="#">Contact</a></li>
				  </ul>
				</div>
			  </div>
			</nav>	
		</div>
		<div class="top_nav_right">
			<div class="wthreecartaits wthreecartaits2 cart cart box_1"> 
					<form action="chart.php" method="post" class="last">
						<input type="hidden" name="cmd" value="_cart">
						<input type="hidden" name="display" value="1">
						<button class="w3view-cart" type="submit" name="submit" value=""><i class="fa fa-cart-arrow-down" aria-hidden="true"></i></button>
					</form> 
  
						</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<!-- //banner-top -->
';


include_once '../gxplugin/securimage/securimage.php';
$securimage = new Securimage();

if (isset($_POST['login'])) { // Check if submit button has been pressed.#fdf1f1

	/** CHECK COOKIES **/
	echo check_phpsessid();

	$_POST = protection($_POST); // Protect the $_POST variable.
	$_GET = protection($_GET); // Protect the $_GET variable.

		if(empty($_POST['username']) || empty($_POST['password'])) { // Check if the form fields are empty or not.
			echo '<script language="JavaScript">
			  alert("Login failed. Check your username and/or password.");
			  </script>'; // If there empty show error message.
		} else {
			if($securimage->check($_POST['captcha_code']) == false) {
            echo "<script language='JavaScript'>
               alert('The security code entered was incorrect.');
			window.history.go(-1);
               </script>";
			}else{
			$username = isset($_POST['username']) ? mysql_real_escape_string(strip_tags($_POST['username'])) : "";
			$redirect = isset($_GET['r']) ? mysql_real_escape_string(strip_tags($_GET['r'])) : "";
			$chkuser = mysql_query("SELECT * FROM `users` WHERE (`username` = '".$username."' OR `email` = '".$username."') && `password` = '".md5($_POST['password'])."' AND `group` != 'admin' LIMIT 0, 1" ); // Check if the username and password are correct.
				if(mysql_num_rows($chkuser)) { // Check if they are correct
					$vcu = mysql_fetch_array($chkuser); // Get the information
					$jam = date("H");
					$waktu = date("Y-m-d ").($jam-1).date(":i:s");
					
					$results = mysql_query("INSERT into `sessions` (`sess_id`, `uid`, `logged`, `ip`, `waktu`) values ('".$_COOKIE['PHPSESSID']."', '".$vcu['id_user']."', '0', '".$_SERVER['REMOTE_ADDR']."', '$waktu') "); // Insert the session id and user id into the sessions table to create the login.
					
					$sql = mysql_query("UPDATE `users` SET `online` = '0', `lastvisit` = now()  WHERE `id_user` = '".$vcu['id_user']."' ");
					
						if($results) {
							if($sql) { // If it submitted it then success.
							    $_SESSION['user'] = $_POST['username']; 
								if($redirect != "") { // If $_GET['r'] is blank redirect the user to index.php after login if not redirect the user to the url indicated in login.php?r=http://www.google.com
								    header("Location: ".$_GET['r']);
								} elseif($redirect == "") {
								    header("Location:".$_SERVER['PHP_SELF']);
								}
							} else { // If couldnt submit into sessions table then show error message
									echo '<script language="JavaScript">
									  alert("Unknown Error sql.");
									</script>';
							}	
						} else { // If couldnt submit into sessions table then show error message
						  
						  echo '<script language="JavaScript">
							alert("Unknown Error result.");
						      </script>';
						}
		           
				} else{
				    echo '<script language="JavaScript">
							alert("Incorrect Username Or Password.");
						      </script>';
				}
			}
		}
}
$template .='
<!-- login -->
		<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
						<div class="modal-body modal-body-sub_agile">
						<div class="col-md-8 modal_body_left modal_body_left1">
						<h3 class="agileinfo_sign">Masuk <span>Sekarang</span></h3>
						<form action="#" method="post">
							<div class="styled-input agile-styled-input-top">
								<input type="text" name="username" required="">
								<label>Username</label>
								<span></span>
							</div>
							<div class="styled-input">
								<input name="password" type="password" required="">
								<label>Password</label>
								<span></span>
							</div>
							<div class="styled-input">
								<input type="hidden" name="nilai" value="'.$_SESSION['nilai_login'].'"></span>
							</div>
							<input type="submit" name="login" value="Masuk">
						</form>
						  <ul class="social-nav model-3d-0 footer-social w3_agile_social top_agile_third">
															<li><a href="#" class="facebook">
																  <div class="front"><i class="fa fa-facebook" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-facebook" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="twitter"> 
																  <div class="front"><i class="fa fa-twitter" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-twitter" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="instagram">
																  <div class="front"><i class="fa fa-instagram" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-instagram" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="pinterest">
																  <div class="front"><i class="fa fa-linkedin" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-linkedin" aria-hidden="true"></i></div></a></li>
														</ul>
														<div class="clearfix"></div>
														<p><a href="signup.php" data-toggle="modal" data-target="#myModal2" > Belum punya akun ?</a></p>

						</div>
						<div class="col-md-4 modal_body_right modal_body_right1">
							<img src="images/log_pic.jpg" alt=" "/>
						</div>
						<div class="clearfix"></div>
					</div>
				</div>
				<!-- //Modal content-->
			</div>
		</div>
<!-- //login -->

'.$content.'
<div class="coupons">
		<div class="coupons-grids text-center">
			<div class="w3layouts_mail_grid">
				<div class="col-md-3 w3layouts_mail_grid_left">
					<div class="w3layouts_mail_grid_left1 hvr-radial-out">
						<i class="fa fa-truck" aria-hidden="true"></i>
					</div>
					<div class="w3layouts_mail_grid_left2">
						<h3>Gratis Ongkos Kirim</h3>
						<p>Hanya Area Malang Kota<br>Syarat dan ketentuan berlaku</p>
					</div>
				</div>
				<div class="col-md-3 w3layouts_mail_grid_left">
					<div class="w3layouts_mail_grid_left1 hvr-radial-out">
						<i class="fa fa-headphones" aria-hidden="true"></i>
					</div>
					<div class="w3layouts_mail_grid_left2">
						<h3>24/7 SUPPORT</h3>
						<p>Hanya melalui Whatsapp</p>
					</div>
				</div>
				<div class="col-md-3 w3layouts_mail_grid_left">
					<div class="w3layouts_mail_grid_left1 hvr-radial-out">
						<i class="fa fa-shopping-bag" aria-hidden="true"></i>
					</div>
					<div class="w3layouts_mail_grid_left2">
						<h3>Garansi Uang Kembali</h3>
						<p>Syarat dan Ketentuan Berlaku</p>
					</div>
				</div>
					<div class="col-md-3 w3layouts_mail_grid_left">
					<div class="w3layouts_mail_grid_left1 hvr-radial-out">
						<i class="fa fa-gift" aria-hidden="true"></i>
					</div>
					<div class="w3layouts_mail_grid_left2">
						<h3>Kupon</h3>
						<p>Syarat dan Ketentuan Berlaku</p>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>

		</div>
</div>
<!--grids-->
<!-- footer -->
<div class="footer">
	<div class="footer_agile_inner_info_w3l">
		<div class="col-md-3 footer-left">
			<h2><a href="index.html"><span>R</span>ainiers </a></h2>
			<p>One Stop Shopping<br>Online Store in Malang</p>
			<ul class="social-nav model-3d-0 footer-social w3_agile_social two">
															<li><a href="#" class="facebook">
																  <div class="front"><i class="fa fa-facebook" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-facebook" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="twitter"> 
																  <div class="front"><i class="fa fa-twitter" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-twitter" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="instagram">
																  <div class="front"><i class="fa fa-instagram" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-instagram" aria-hidden="true"></i></div></a></li>
															<li><a href="#" class="pinterest">
																  <div class="front"><i class="fa fa-linkedin" aria-hidden="true"></i></div>
																  <div class="back"><i class="fa fa-linkedin" aria-hidden="true"></i></div></a></li>
														</ul>
		</div>
		<div class="col-md-9 footer-right">
			<div class="sign-grds">
				<div class="col-md-4 sign-gd">
					<h4>Our <span>Information</span> </h4>
					<ul>
						<li><a href="index.html">Home</a></li>
						<li><a href="#">Tentang Kami</a></li>
						<li><a href="#">Syarat dan Ketentuan</a></li>
						<li><a href="#">Panduan Berlanja</a></li>
						<li><a href="#">Membership</a></li>
						<li><a href="#">Kontak Kami</a></li>
					</ul>
				</div>
				
				<div class="col-md-5 sign-gd-two">
					<h4>Store <span>Information</span></h4>
					<div class="w3-address">
						<div class="w3-address-grid">
							<div class="w3-address-left">
								<i class="fa fa-phone" aria-hidden="true"></i>
							</div>
							<div class="w3-address-right">
								<h6>Mobile Phoner</h6>
								<p>+62 878-5905-2006</p>
							</div>
							<div class="clearfix"> </div>
						</div>
						<div class="w3-address-grid">
							<div class="w3-address-left">
								<i class="fa fa-envelope" aria-hidden="true"></i>
							</div>
							<div class="w3-address-right">
								<h6>Email </h6>
								<p><a href="mailto:toko@xtremewebsolution.net"> toko@xtremewebsolution.net</a></p>
							</div>
							<div class="clearfix"> </div>
						</div>
						<div class="w3-address-grid">
							<div class="w3-address-left">
								<i class="fa fa-map-marker" aria-hidden="true"></i>
							</div>
							<div class="w3-address-right">
								<h6>Alamat</h6>
								<p>Bukit Dieng MA 1B<br>Malang, East Java 
								
								</p>
							</div>
							<div class="clearfix"> </div>
						</div>
					</div>
				</div>
				<!--<div class="col-md-3 sign-gd flickr-post">
					<h4>Flickr <span>Posts</span></h4>
					<ul>
						<li><a href="#"><img src="images/t1.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t2.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t3.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t4.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t1.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t2.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t3.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t2.jpg" alt=" " class="img-responsive" /></a></li>
						<li><a href="#"><img src="images/t4.jpg" alt=" " class="img-responsive" /></a></li>
					</ul>
				</div>-->
				<div class="clearfix"></div>
			</div>
		</div>
		<div class="clearfix"></div>
			<div class="agile_newsletter_footer">
					<div class="col-sm-6 newsleft">
				<h3>BERLANGGANAN INFORMASI TERBARU KAMI</h3>
			</div>
			<div class="col-sm-6 newsright">
				<form action="#" method="post">
					<input type="email" placeholder="Enter your email..." name="email" required="">
					<input type="submit" value="Submit">
				</form>
			</div>

		<div class="clearfix"></div>
	</div>
		<p class="copy-right">&copy 2017 Rainiers Online. All rights reserved </p>
	</div>
</div>
<!-- //footer -->

<!-- login -->
			<div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content modal-info">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>						
						</div>
						<div class="modal-body modal-spa">
							<div class="login-grids">
								<div class="login">
									<div class="login-bottom">
										<h3>Daftar Gratis</h3>
										<form>
											<div class="sign-up">
												<h4>Email :</h4>
												<input type="text" value="Type here" onfocus="this.value = \'\';" onblur="if (this.value == \'\') {this.value = \'Type here\';}" required="">	
											</div>
											<div class="sign-up">
												<h4>Password :</h4>
												<input type="password" value="Password" onfocus="this.value = \'\';" onblur="if (this.value == \'\') {this.value = \'Password\';}" required="">
												
											</div>
											<div class="sign-up">
												<h4>Re-type Password :</h4>
												<input type="password" value="Password" onfocus="this.value = \'\';" onblur="if (this.value == \'\') {this.value = \'Password\';}" required="">
												
											</div>
											<div class="sign-up">
												<input type="submit" value="REGISTER NOW" >
											</div>
											
										</form>
									</div>
									<div class="login-right">
										<h3>Masuk dengan akun Anda</h3>
										<form>
											<div class="sign-in">
												<h4>Email :</h4>
												<input type="text" value="Type here" onfocus="this.value = \'\';" onblur="if (this.value == \'\') {this.value = \'Type here\';}" required="">	
											</div>
											<div class="sign-in">
												<h4>Password :</h4>
												<input type="password" value="Password" onfocus="this.value = \'\';" onblur="if (this.value == \'\') {this.value = \'Password\';}" required="">
												<a href="#">Forgot password?</a>
											</div>
											<div class="single-bottom">
												<input type="checkbox"  id="brand" value="">
												<label for="brand"><span></span>Remember Me.</label>
											</div>
											<div class="sign-in">
												<input type="submit" value="SIGNIN" >
											</div>
										</form>
									</div>
									<div class="clearfix"></div>
								</div>
								<p>By logging in you agree to our <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
<!-- //login -->
<a href="#home" class="scroll" id="toTop" style="display: block;"> <span id="toTopHover" style="opacity: 1;"> </span></a>
<!-- js -->
<script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
<!-- //js -->
<script src="js/modernizr.custom.js"></script>
	<!-- Custom-JavaScript-File-Links --> 
	<!-- cart-js 
	<script src="js/minicart.min.js"></script>
<script>
	// Mini Cart
	paypal.minicart.render({
		action: \'#\'
	});

	if (~window.location.search.indexOf(\'reset=true\')) {
		paypal.minicart.reset();
	}
</script>

	<!-- //cart-js --> 
	<!-- single -->
<script src="js/imagezoom.js"></script>
<!-- single -->
<!-- script for responsive tabs -->						
<script src="js/easy-responsive-tabs.js"></script>
<script>
	$(document).ready(function () {
	$(\'#horizontalTab\').easyResponsiveTabs({
	type: \'default\', //Types: default, vertical, accordion           
	width: \'auto\', //auto or any width like 600px
	fit: true,   // 100% fit in a container
	closed: \'accordion\', // Start closed if in accordion view
	activate: function(event) { // Callback function if tab is switched
	var $tab = $(this);
	var $info = $(\'#tabInfo\');
	var $name = $(\'span\', $info);
	$name.text($tab.text());
	$info.show();
	}
	});
	$(\'#verticalTab\').easyResponsiveTabs({
	type: \'vertical\',
	width: \'auto\',
	fit: true
	});
	});
</script>
<!-- FlexSlider -->
<script src="js/jquery.flexslider.js"></script>
						<script>
						// Can also be used with $(document).ready()
							$(window).load(function() {
								$(\'.flexslider\').flexslider({
								animation: "slide",
								controlNav: "thumbnails"
								});
							});
						</script>
					<!-- //FlexSlider-->
<!-- //script for responsive tabs -->		
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/jquery.easing.min.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){		
			event.preventDefault();
			$(\'html,body\').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script>
<!-- here stars scrolling icon -->
	<script type="text/javascript">
		$(document).ready(function() {
			/*
				var defaults = {
				containerID: \'toTop\', // fading element id
				containerHoverID: \'toTopHover\', // fading element hover id
				scrollSpeed: 1200,
				easingType: \'linear\' 
				};
			*/
								
			$().UItoTop({ easingType: \'easeOutQuart\' });
								
			});
	</script>
<!-- //here ends scrolling icon -->

<!-- for bootstrap working -->
<script type="text/javascript" src="js/bootstrap.js"></script>
</body>
</html>

';
    return $template;
}

function mail_register($username="", $pswd="", $email=""){
    
    $username	= ($username!="") ? $username : "";
    $pswd	= ($pswd!="") ? $pswd : "";
    $email	= ($email!="") ? $email : "";
    
    
    $template = '<table width="530" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr bgcolor="#FF9999"> 
    <td width="186" height="70"><div align="center"><img src="'.URL.'images/logo.png" width="164" height="48"></div></td>
    <td width="314" colspan="-6"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Hi, '.$username.', 
      </strong><br>
      Silahkan mengkonfirmasi akun Anda,</font></td>
  </tr>
</table>

<table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="500">&nbsp;</td>
  </tr>
  <tr> 
    <td><font color="#666666" size="2" face="Verdana, Arial, Helvetica, sans-serif">Untuk 
      mendapatkan akses penuh sebagai member di Toko Online kami, silahkan mengkonfirmasi 
      akun Anda dengan menekan tombol di bawah ini</font></td>
  </tr>
  <tr> 
    <td height="52"> <div align="center"><a href="'.URL.'new.verifikasi.php?go=newpage&u='.$username.'&p='.$pswd.'&e='.$email.'" ><img src="'.URL.'images/confirm.png" border="0"></a></div></td>
  </tr>
  <tr> 
    <td><div align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">atau 
        klik link di bawah ini</font></div></td>
  </tr>
  <tr> 
    <td height="23"> <div align="left"><a href="'.URL.'new/verifikasi.php?go=newpage&u='.$username.'&p='.$pswd.'&e='.$email.'">Click Here to verification</a></div></td>
  </tr>
  <tr> 
    <td height="21">
<div align="center"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><div align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Terima 
        Kasih</font></div></td>
  </tr>
  <tr> 
    <td><div align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">salam 
        Hormat</font>,</div></td>
  </tr>
  <tr> 
    <td><div align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Manajemen 
        Toko </strong></font></div></td>
  </tr>
</table>';


return $template;
}
function mail_forgot($username="", $pswd="", $email=""){
    
    $username	= ($username!="") ? $username : "";
    $pswd	= ($pswd!="") ? $pswd : "";
    $email	= ($email!="") ? $email : "";
    
    
    $template = '<table width="530" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr bgcolor="#FF9999"> 
    <td width="186" height="70"><div align="center"><img src="'.URL.'images/logo.png" width="164" height="48"></div></td>
    <td width="314" colspan="-6"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Hi, '.$username.', 
      </strong><br>
      Silahkan melanjutkan proses di bawah ini,</font></td>
  </tr>
</table>

<table width="500" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="500">&nbsp;</td>
  </tr>
  <tr> 
    <td><font color="#666666" size="2" face="Verdana, Arial, Helvetica, sans-serif">Untuk melanjutkan proses ke halaman ganti kata sandi silahkan klik tombol di bawah ini</font></td>
  </tr>
  <tr> 
    <td height="52"> <div align="center"><a href="'.URL.'new/forgot.php?reset=ok&u='.$username.'&p='.$pswd.'&e='.$email.'" ><img src="'.URL.'images/confirm.png" border="0"></a></div></td>
  </tr>
  <tr> 
    <td><div align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">atau 
        klik link di bawah ini</font></div></td>
  </tr>
  <tr> 
    <td height="23"> <div align="left"><a href="'.URL.'new/forgot.php?reset=ok&u='.$username.'&p='.$pswd.'&e='.$email.'">Klik disini untuk ke halaman ganti kata sandi</a></div></td>
  </tr>
  <tr> 
    <td height="21">
<div align="center"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td><div align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Terima 
        Kasih</font></div></td>
  </tr>
  <tr> 
    <td><div align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif">Salam 
        Hormat</font>,</div></td>
  </tr>
  <tr> 
    <td><div align="left"><font color="#333333" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Manajemen 
        Toko </strong></font></div></td>
  </tr>
</table>';


return $template;
}
?>