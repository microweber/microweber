<?php

  //var_dump($_POST);


if (file_exists('count_file_req.txt'))
	{
		$fil = fopen('count_file.txt', r);
		$dat = fread($fil, filesize('count_file.txt'));
		$count =  $dat+1;
		fclose($fil);
		$fil = fopen('count_file.txt', w);
		fwrite($fil, $dat+1);
	}

	else
	{
		$fil = fopen('count_file.txt', w);
		fwrite($fil, 1);
		$count = '1';
		fclose($fil);
	}



$subject='Yomexbg.com - Запитване от сайта '.$count;


$headers.='От: ' . $_REQUEST['names'] . ' <' . $_REQUEST['email'] . '>';

 $headers .= "\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/plain; charset=utf-8\r\n";
  $headers .= "Content-Transfer-Encoding: quoted-printable\r\n";







if (!empty($_REQUEST['contacts_name']))
$message.='Име: ' . $_REQUEST['contacts_name'] . "\r\n";

if (!empty($_REQUEST['contacts_firm']))
$message.='Фирма: ' . $_REQUEST['contacts_firm'] . "\r\n";

if (!empty($_REQUEST['contacts_email']))
$message.='Email: ' . $_REQUEST['contacts_email'] . "\r\n";

if (!empty($_REQUEST['contacts_phone']))
$message.='Телефон: ' . $_REQUEST['contacts_phone'] . "\r\n";

if (!empty($_REQUEST['otnosno']))
$message.='Относно: ' . $_REQUEST['otnosno'] . "\r\n";

if (!empty($_REQUEST['contacts_message']))
$message.='Съобщение: ' . $_REQUEST['contacts_message'] . "\r\n";



   $to = 'yomex@yomexbg.com';
   //$sendto = explode(',',$sendto);

	   $to = trim($to);
	mail($to, $subject, $message, $headers);

    echo $message;
	
	
	
	
	$subject='[YomexBG.com] Потвърждение ';
$headers = '';
$headers.='From: ' . 'yomex@yomexbg.com' . ' <' . 'yomex@yomexbg.com' . '>';
$headers .= "\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/plain; charset=utf-8\r\n";
  $headers .= "Content-Transfer-Encoding: quoted-printable\r\n";
  $sendto = 'yomex@yomexbg.com';

  $message = file_get_contents('auto_responders/request.html');
 	mail($_REQUEST['contacts_email'], $subject, $message, $headers);

	//UTF8_mail($_REQUEST['email'],$to,$subject,$message,$cc="",$bcc="");

//$message.="\r\nMessage:" . $_REQUEST['message'];



?>