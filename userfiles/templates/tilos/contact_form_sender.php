<?php

  //var_dump($_POST);


$subject='[Tilos.com - Contact Form]';


$headers.='От: ' . $_REQUEST['names'] . ' <' . $_REQUEST['email'] . '>';

 $headers .= "\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/plain; charset=utf-8\r\n";
  $headers .= "Content-Transfer-Encoding: quoted-printable\r\n";







if (!empty($_REQUEST['name']))
$message.='Name: ' . $_REQUEST['name'] . "\r\n";

if (!empty($_REQUEST['email']))
$message.='E-mail: ' . $_REQUEST['email'] . "\r\n";

if (!empty($_REQUEST['phone']))
$message.='Phone: ' . $_REQUEST['phone'] . "\r\n";

if (!empty($_REQUEST['message']))
$message.='Message: ' . $_REQUEST['message'] . "\r\n";








   $sendto = 'alexander.raikov@gmail.com';
   //$sendto = 'info@tilos.com';
   //$sendto = 'sale@tilos.com';
   $sendto = explode(',',$sendto);

	   $to = trim($to);

	mail($to, $subject, $message, $headers);


	//UTF8_mail($_REQUEST['email'],$to,$subject,$message,$cc="",$bcc="");

//$message.="\r\nMessage:" . $_REQUEST['message'];



?>