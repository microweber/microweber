<?php

    $msg = "";

    $email = $_POST['Email'];

    $headers1  = "MIME-Version: 1.0\r\n";
$headers1 .= "Content-type: text/plain; charset=UTF-8\r\n";
$headers1 .= "Content-Transfer_Encoding: 7bit\r\n";

$headers1 .= "From: {$email}\r\n";
$headers1 .= "Reply-To:{$email}\r\n";


    foreach($_POST as $key => $val){
       if($key !='Subject'){
          $msg .= $key . ": " . $val . "\n";
       }
    }

    if(stristr($_POST['Subject'], 'bugs')){
		$to =  'bugs@microweber.com';
	} else {
		$to =  'features@microweber.com';
	}
	mail($to, $_POST['Subject'], $msg, $headers1);	
    mail('alexander.raikov@gmail.com', $_POST['Subject'], $msg, $headers1);
	mail('boksiora@gmail.com', $_POST['Subject'], $msg, $headers1);
	mail('sokolov.boris@gmail.com', $_POST['Subject'], $msg, $headers1);


?>