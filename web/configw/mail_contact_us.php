<?php
function contact_us_mail($nama_customer, $message)
{
    $mail = '
<div>
<b>From (' . $nama_customer . '),</b><br><br><br>
' . $message . '
<div>';

    return $mail;
}