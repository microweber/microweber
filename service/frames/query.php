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

    var_dump($msg);

    mail('alexander.raikov@gmail.com', $_POST['Subject'], $msg, $headers1);


?>