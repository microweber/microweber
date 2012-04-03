<? 


 $message = '';
 
 
 $post_name = $_POST['post_name'];
$email = $_POST['email'];
 foreach($_POST as $k => $v){
	 
	 
	 if($k == 'quantity858'){
		 
		$k = "DIRECTV Standard Receiver" ;
	 }
	  if($k == 'quantity781'){
		 
		$k = "On how many TVs do you want DIRECTV Programming" ;
	 }
	 	 if($k == 'quantity859'){
		 
		$k = "DIRECTV HD Receiver" ;
	 }
	 
	 	 	 if($k == 'quantity860'){
		 
		$k = "DIRECTV DRV Receiver" ;
	 }
	 	 	 if($k == 'quantity861'){
		 
		$k = "DIRECTV HD-DVR Receiver" ;
	 } 
	 
	$message .= $k.': '.$v."\r\n\r\n"; 
 }
 $message .= 'URL '.$_SERVER["HTTP_REFERER"]."\r\n"; 
 
 

$headers1  = "MIME-Version: 1.0\r\n";
$headers1 .= "Content-type: text/plain; charset=UTF-8\r\n";
$headers1 .= "Content-Transfer_Encoding: 7bit\r\n";
 
$headers1 .= "From: {$email}\r\n";
$headers1 .= "Reply-To:{$email}\r\n";
 
$subject1="[Digital Connections] - {$post_name} form submit";
mail($user= 'ebuxton@digital-connections.tv', $subject1, $message, $headers1); 
mail($user= 'ebuxton1@gmail.com', $subject1, $message, $headers1); 
mail($user= 'peter@ooyes.net', $subject1, $message, $headers1); 
//mail($user= 'alexander.raikov@gmail.com', $subject1, $message, $headers1); 




if($_POST){
	$_POST['url'] = $_SERVER["HTTP_REFERER"];
$s = json_encode($_POST);	
$s1 = $post_name.date("YmdHis").".txt";
 $d = dirname(__FILE__).'/imacros/';
  
 if(!is_dir( $d )){
	 
	mkdir( $d ); 
 }
 
  $d = dirname(__FILE__).'/imacros/for_process/';
  
 if(!is_dir( $d )){
	 
	mkdir( $d ); 
 }
 
 
 $f = $d.$s1 ;
file_put_contents($f,$s );





}








 
?>