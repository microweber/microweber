<? 


api_expose('img_fx');
function img_fx($params){
	 
	d($_GET);
	$ret = array();
	  
 if(isset($_GET) and !empty($_GET)){
  if(isset($_GET['url'])){
	  $ret['url'] = 'asdas';
	  
  }
 
 }
 
 return $ret;
 
}

 