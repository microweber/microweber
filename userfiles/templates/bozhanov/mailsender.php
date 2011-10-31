<? 


 $message = '';
 
$email = $_POST['email'];
 foreach($_POST as $k => $v){
	$message .= $k.': '.$v."\n"; 
 }
 $message .= 'URL '.$_SERVER["HTTP_REFERER"]."\n"; 
 
 

$headers1  = "MIME-Version: 1.0\r\n";
$headers1 .= "Content-type: text/html; charset=UTF-8\r\n";
$headers1 .= "From: {$email}\r\n";
$headers1 .= "Reply-To:{$email}\r\n";
 
$subject1="Bozhanov.com - mailform";
mail($user= 'peter@microweber.com', $subject1, $message, $headers1);
 

?>