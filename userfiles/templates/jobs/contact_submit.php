<?


 $message = '';

$email = $_POST['Email'];
 foreach($_POST as $k => $v){
	$message .= $k.': '.$v."\r\n";
 }

$headers1  = "MIME-Version: 1.0\r\n";
$headers1 .= "Content-type: text/plain; charset=UTF-8\r\n";
$headers1 .= "Content-Transfer_Encoding: 7bit\r\n";

$headers1 .= "From: {$email}\r\n";
$headers1 .= "Reply-To:{$email}\r\n";

$subject1="[EXEMPLAR] - Contact Form";

    mail($user= 'boksiora@gmail.com', $subject1, $message, $headers1);
    mail($user= 'alexander.raikov@gmail.com', $subject1, $message, $headers1);



?>