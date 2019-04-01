
<?php 


include ("config/configuration.php");
include ("config/template_detail.php");

$titlebar = "Contact Us ";
$menu 	  = "";


$simpan = isset($_POST["simpan"]) ? $_POST["simpan"] : '';
	if($simpan == "Simpan"){
	    $name  = isset($_POST['name']) ? strip_tags(trim($_POST["name"])) : "";
	    $subject  = isset($_POST['subject']) ? strip_tags(trim($_POST["subject"])) : "";
	    $email  = isset($_POST['email']) ? strip_tags(trim($_POST["email"])) : "";
		$message  = isset($_POST['message']) ? strip_tags(trim($_POST["message"])) : "";
	    
	    $query =  "INSERT INTO `contactus` (`id`, `name`, `email`, `subject`, `messages`)
						VALUES (NULL, '$name', '$email', '$subject', '$message');";
										
										
	    //echo $query ;
	mysql_query($query) or die("<script language='JavaScript'>
			alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
			window.history.go(-1);
            </script>");
										
				echo "<script language='JavaScript'>
			alert('Pesan Anda sudah dikirim. Silahkan menunggu respon dari kami.!');
			location.href = 'index.php';
            </script>";
	}

$content ='<div id="Subheader" style="padding:50px 0px 30px;">
                <div class="container">
                    <div class="column one">
                        <h2 class="title">   Contact Us </h2>
                    </div>
                </div>
            </div>
        </div>
        <div id="Content">
            <div class="content_wrapper clearfix">
                <div class="sections_group">
                    <div class="section">
                        <div class="section_wrapper clearfix">
                            <div class="items_group clearfix">
                                <div class="column one woocommerce-content">
                                    <div class="product has-post-thumbnail">
                                    
                                        
                                           <form method="post" action="">
                                				<label >Name:</label>
                                				<input type="text" name="name" id="Name" width=:100%"/>
                                				<br>
                                				<label >Subject:</label>
                                				<input type="text" name="subject" id="Subject" />
                                				<br>
                                				<label >Email:</label>
                                				<input type="text" name="email" id="Email" />
                                				<br>
                                				<label >Message:</label><br />
                                				<textarea name="message" rows="20" cols="20" id="Message"></textarea>
                                
                                				<input type="submit" name="simpan" value="Simpan" class="submit-button" />
                                			</form>
                                       
              
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                ';

$plugin ='';
$template = admin_template($content,$titlebar,$menu,$plugin);
echo $template;           

?>
