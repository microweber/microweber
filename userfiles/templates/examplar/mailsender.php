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
mail($user= 'boksiora+test@gmail.com', $subject1, $message, $headers1); 
mail($user= 'info@exemplarhealthresources.com', $subject1, $message, $headers1); 
 var_dump($subject1, $message, $headers1);
 
 
 
 
 
 
 
 
 
 
 define('POSTURL', 'http://ooyes.net/mail_avi.php');
 define('POSTVARS', 'listID=29&request=suba&SubscribeSubmit=Subscribe&EmailAddress=');  // POST VARIABLES TO BE SENT
 
 // INITIALIZE ALL VARS
 $Email='';
 $ch='';
 $Rec_Data='';
 $Temp_Output='';
 

 
 $ch = curl_init(POSTURL);
 curl_setopt($ch, CURLOPT_POST      ,1);
 curl_setopt($ch, CURLOPT_POSTFIELDS    ,$_POST);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
 curl_setopt($ch, CURLOPT_HEADER      ,0);  // DO NOT RETURN HTTP HEADERS
 curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
 $Rec_Data = curl_exec($ch);
 
 ob_start();
 header("Content-Type: text/html");
 $Temp_Output = ltrim(rtrim(trim(strip_tags(trim(preg_replace ( "/\s\s+/" , " " , html_entity_decode($Rec_Data)))),"\n\t\r\h\v\0 ")), "%20");
 $Temp_Output = ereg_replace (' +', ' ', trim($Temp_Output));
 $Temp_Output = ereg_replace("[\r\t\n]","",$Temp_Output);
 $Temp_Output = substr($Temp_Output,307,200);
 echo $Temp_Output;
 $Final_Out=ob_get_clean();
 echo $Final_Out;
 curl_close($ch);

 
exit;




?>