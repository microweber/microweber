<?php

  //var_dump($_POST);


$subject='[Yomex - Culture & Tourism]';


$headers.='From: ' . $_REQUEST['names'] . ' <' . $_REQUEST['email'] . '>';

 $headers .= "\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/plain; charset=utf-8\r\n";
  $headers .= "Content-Transfer-Encoding: quoted-printable\r\n";







if (!empty($_REQUEST['names']))
$message.='' . $_REQUEST['names'] . ' Ви препоръча следният адрес:' . "\r\n";


if (!empty($_REQUEST['r_address']))
$message.='' . $_REQUEST['r_address'] . "\r\n";

if (!empty($_REQUEST['message']))
$message.='Съобщение: ' . $_REQUEST['message'] . "\r\n";





   $to = $_REQUEST['email2'];
   //$sendto = explode(',',$sendto);

	   $to = trim($to);
	mail($to, $subject, $message, $headers);

    echo $message;

	//UTF8_mail($_REQUEST['email'],$to,$subject,$message,$cc="",$bcc="");

//$message.="\r\nMessage:" . $_REQUEST['message'];



?>