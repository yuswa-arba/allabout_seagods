<?php


include("config/configuration.php");
include("config/template_detail.php");
require 'plugins/mailer/class.phpmailer.php';
require_once("config/mail_contact_us.php");

$titlebar = "Contact Us ";
$menu = "";


$simpan = isset($_POST["simpan"]) ? $_POST["simpan"] : '';
if ($simpan == "Simpan") {
    $name = isset($_POST['name']) ? strip_tags(trim($_POST["name"])) : "";
    $subject = isset($_POST['subject']) ? strip_tags(trim($_POST["subject"])) : "";
    $fromEmail = isset($_POST['email']) ? strip_tags(trim($_POST["email"])) : "";
    $message = isset($_POST['message']) ? $_POST["message"] : "";

    if ($fromEmail != "" && $name != "") {

        $mail = new PHPMailer(); // defaults to using php "mail()"
        $mail->IsSMTP();
        $mail->SMTPDebug = 0; // set mailer to use SMTP
        $mail->Timeout = 120;     // set longer timeout for latency or servers that take a while to respond

        $mail->Host = "mail.seagodswetsuit.com";        // specify main and backup server
        $mail->Port = 587;
        $mail->SMTPAuth = false;    // turn on or off SMTP authentication

        try {

            $message_template = contact_us_mail($name, nl2br($message));

            $mail->AddAddress('yuswa@globalxtreme.net', 'bali yuswa yuswa');
            $mail->SetFrom($fromEmail, $name);

            $mail->Subject = $subject;
            $mail->MsgHTML($message_template);

            $mail->Send();

        } catch (phpmailerException $e) {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            echo $e->getMessage(); //Boring error messages from anything else!
        }

        $query = "INSERT INTO `contactus` (`id`, `name`, `email`, `subject`, `messages`)
						VALUES (NULL, '$name', '$fromEmail', '$subject', '$message');";

        mysql_query($query) or die("<script language='JavaScript'>
			alert('Maaf Data tidak bisa diupdate ke dalam Database, Ada kesalahan!');
			window.history.go(-1);
            </script>");

        echo "<script language='JavaScript'>
			alert('Pesan Anda sudah dikirim. Silahkan menunggu respon dari kami.!');
			location.href = 'index.php';
            </script>";
    }
}

$content = '<!--<div id="Subheader" style="padding:50px 0px 30px;">
                <div class="container">
                    <div class="column one">
                        <h2 class="title">   Contact Us </h2>
                    </div>
                </div>
            </div>-->
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

$plugin = '

<!-- Global Site Tag (gtag.js) - Google Analytics -->
<script type="text/javascript" async="" src="https://www.google-analytics.com/analytics.js"></script><script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-131936719-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag("js", new Date());

  gtag("config", "UA-131936719-1");
</script>


';
$template = admin_template($content, $titlebar, $menu, $plugin);
echo $template;

?>
