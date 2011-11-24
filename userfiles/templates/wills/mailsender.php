<? 


 $message = '';
 
$email = $_POST['email'];
 foreach($_POST as $k => $v){
	$message .= $k.': '.$v."\r\n"; 
 }
 $message .= 'URL '.$_SERVER["HTTP_REFERER"]."\r\n"; 
 
 

$headers1  = "MIME-Version: 1.0\r\n";
$headers1 .= "Content-type: text/plain; charset=UTF-8\r\n";
$headers1 .= "Content-Transfer_Encoding: 7bit\r\n";
 
$headers1 .= "From: {$email}\r\n";
$headers1 .= "Reply-To:{$email}\r\n";
 
$subject1="[website] - mailform";
 mail($user= 'boksiora+test+wills@gmail.com', $subject1, $message, $headers1); 
 
  mail($user= 'jrh@globalwills.com', $subject1, $message, $headers1); 
  mail($user= 'info@globalwills.com', $subject1, $message, $headers1); 
  
 
 //1 mail($user= 'info@exemplarhealthresources.com', $subject1, $message, $headers1); 
 var_dump( $_POST);
?>