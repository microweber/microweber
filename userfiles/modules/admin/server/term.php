<?php only_admin_access();?>
 
 <?  //  d($params); ?>
 
 
<? 
if(isset($params["exec_command"])){
	$params["exec_command_params"] = base64_decode(trim($params["exec_command_params"]));
	 $params["exec_command"] = base64_decode(trim($params["exec_command"]));
	//$params["exec_command"] = trim($params["exec_command"]);
	if($params["exec_command"] != ''){
	
	//asd$params["exec_command"] = rtrim($params["exec_command"],";;");
	//
	 //$params["exec_command"] = trim($params["exec_command"]).';' ;	
	// $params["exec_command"] = trim($params["exec_command"],";;"); 
		
	}
//	d($params["exec_command"]);
	
	try {
$arg=  $string = ($params["exec_command"]);
  
 
   print 'Executing function: ' .( ($string));
  // $a = api($string);
 // $a = eval($string);
 //  var_dump ($a );
   
 $a =call_user_func($arg,$params["exec_command_params"]); // As of 5.2.3;

 ?>
 <br />

 <textarea class="term_results"><? print_r ($a ); ?></textarea>
 <?
	 
//	$res = $$string;
   
	 
} catch (Exception $e) {
     throw new Exception( 'Caught exception: ',  $e->getMessage(), "\n");
}  



	
}

?>