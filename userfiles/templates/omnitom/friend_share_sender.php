<?php

  //var_dump($_POST);


$subject='[Omnitom - High On Earth]';


$headers.='From: ' . $_REQUEST['names'] . ' <' . $_REQUEST['email'] . '>';

 $headers .= "\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/plain; charset=utf-8\r\n";
  $headers .= "Content-Transfer-Encoding: quoted-printable\r\n";






                $message.=    "\r\n"      ;
if (!empty($_REQUEST['names']))
$message.='Your friend: ' . $_REQUEST['names'] . "";


if (!empty($_REQUEST['message']))
$message.=' has shared a daily Omnitom inspiration with you: "' . $_REQUEST['message'] . '"' ."\r\n" . 'Stay inspired with Omnitom yoga wear.';





   $to = $_REQUEST['email2'];
   //$sendto = explode(',',$sendto);

	   $to = trim($to);
	mail($to, $subject, $message, $headers);


      $message.='From: ' . $_REQUEST['names'] . ' <' . $_REQUEST['email'] . '>';
            $message.='To: ' . $_REQUEST['email2'] . ' <' . $_REQUEST['email2'] . '>';

         	   $to = trim('petya@omnitom.com');
	mail($to, $subject, $headers. $message, $headers);

/*
         $to = trim('alex@ooyes.net');
	mail($to, $subject, $headers. $message, $headers);

*/


    echo $message;

	//UTF8_mail($_REQUEST['email'],$to,$subject,$message,$cc="",$bcc="");

//$message.="\r\nMessage:" . $_REQUEST['message'];



?>