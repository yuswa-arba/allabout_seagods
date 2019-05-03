<?php

require '../config/configuration.php';
require 'template/news_subscribe.php';
include("../plugins/mailer/class.phpmailer.php");

// Check news subscriber
$query_news = mysql_query("SELECT * FROM `news_subscriber` 
    WHERE `delivery_process` = '1' AND `sent` = '1' AND `level` = '0';");

// looping
while ($row_news = mysql_fetch_array($query_news)) {

    // Get member
    $query_member = mysql_query("SELECT * FROM `member` WHERE `subscribe` = '1' AND `level` = '0' AND `email` != '';");

    // Looping member
    while ($row_member = mysql_fetch_array($query_member)) {

        // If email is not null
        if ($row_member['email'] != '' && $row_member['email'] != null) {

            $mail = new PHPMailer(); // defaults to using php "mail()"
            $mail->IsSMTP();
            $mail->SMTPDebug = 1; // set mailer to use SMTP
            $mail->Timeout = 120;     // set longer timeout for latency or servers that take a while to respond

            $mail->Host = "mail.seagodswetsuit.com";        // specify main and backup server
            $mail->Port = 587;
            $mail->SMTPAuth = false;    // turn on or off SMTP authentication

            try {

                $message_template = new_subscribe_template($row_news['title'], $row_news['body']);

                $mail->AddAddress($row_member['email'], $row_member['firstname'] . ' ' . $row_member['lastname']);
                $mail->SetFrom('info@seagodswetsuit.com', 'Seagods Wetsuit');

                // Add BCC
                $mail->addBCC('order@seagodswetsuit.com');

                $mail->Subject = $row_news['title'];
                $mail->MsgHTML($message_template);

                if ($mail->Send()) {

                    // Success sending email
                    mysql_query("INSERT INTO `news_email` (`news_id`, `member_id`, `date_add`, `date_upd`) 
                        VALUES('" . $row_news['id'] . "', '" . $row_member['id_member'] . "', NOW(), NOW());");

                    // Update news subscriber
                    mysql_query("UPDATE `news_subscriber` SET `dateTimestamp` = NULL, `delivery_process` = FALSE, `date_upd` = NOW() 
                        WHERE `id` = '" . $row_news['id'] . "';");

                } else {

                    // Insert failed send email
                    mysql_query("INSERT INTO `news_email_failed` (`news_id`, `member_id`, `date_add`, `date_upd`) 
                        VALUES('" . $row_news['id'] . "', '" . $row_member['id_member'] . "', NOW(), NOW());");

                }

            } catch (phpmailerException $e) {
                // Error
            }
        }
    }

}