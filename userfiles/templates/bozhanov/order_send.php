<?php

  var_dump($_POST);


$subject='[Кафе за събуждане] ПОРЪЧКА';


$headers.='From: ' . $_REQUEST['names'] . ' <' . $_REQUEST['email'] . '>';

 $headers .= "\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/plain; charset=utf-8\r\n";
  $headers .= "Content-Transfer-Encoding: quoted-printable\r\n";






if (!empty($_REQUEST['name']))
$message.='Име: ' . $_REQUEST['name'] . "\r\n";

if (!empty($_REQUEST['email']))
$message.='E-mail: ' . $_REQUEST['email'] . "\r\n";

if (!empty($_REQUEST['phone']))
$message.='Телефон: ' . $_REQUEST['phone'] . "\r\n";

if (!empty($_REQUEST['city']))
$message.='Град: ' . $_REQUEST['city'] . "\r\n";

if (!empty($_REQUEST['postcode']))
$message.='Пощенски код: ' . $_REQUEST['postcode'] . "\r\n";

if (!empty($_REQUEST['address']))
$message.='Адрес за доставка: ' . $_REQUEST['address'] . "\r\n";

if (!empty($_REQUEST['qty']))
$message.='Количество: ' . $_REQUEST['qty'] . "\r\n";






 $ip = $_SERVER['REMOTE_ADDR'];

 $message.='IP Адрес: ' . $ip . "\r\n";


   $sendto = 'info@massmediapublishing.com,dimitarbozhanov@gmail.com';
   //$sendto = 'alex@ooyes.net';
   $sendto = explode(',',$sendto);
   foreach($sendto as $to){
	   $to = trim($to);
	mail($to, $subject, $message, $headers);
   }







?>
