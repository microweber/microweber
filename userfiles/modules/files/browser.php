<?

/**
 * Simple file browser
 *
 * Gets all files from dir and output them in a template
 *
 * @package		modules
 * @subpackage	files
 * @category	modules
 */
 ?>
<?php
  // Explore the files via a web interface.   
  $script = $config['url']; // the name of this script
  $path   =   MEDIAFILES; // the path the script should access
 if(isset($params['path'])){
	 
	  $path   =   $params['path']; // the path the script should access
 }
  
 //$data = rglob($path);
  $params_get_files = array();    
   $params_get_files['directory']  =  $path;  
   
   if (isset($params['search'])) {
		   $params_get_files['search']  =  $params['search'];  
	}
	
	  if (isset($params['sort_by'])) {
		   $params_get_files['sort_by']  =  $params['sort_by'];  
	}
	  if (isset($params['sort_order'])) {
		   $params_get_files['sort_order']  =  $params['sort_order'];  
	}
	   
  //  $params['keyword']  
 $data = get_files($params_get_files);
 
$path_nav = explode(DS,$path); 

?>
<? if(isarr($path_nav )): ?>
<? 

$path_nav_pop = false;
foreach($path_nav  as $item): ?>
<? 

if($path_nav_pop  == false){
	$path_nav_pop = $item;
} else {

$path_nav_pop = $path_nav_pop.DS.$item;
 
}
 
 ?>

<a href="#path=<? print urlencode($path_nav_pop) ?>"><span class="<? print $config['module_class']; ?> path-item"><? print ($item) ?></span><span class="<? print $config['module_class']; ?> ds"><? print DS ?></span></a>
<? endforeach ; ?>
<? endif; ?>
<? if(isset($data['dirs'] )): ?>
<ul>
  <? foreach($data['dirs']  as $item): ?>
  <li> <a href="#path=<? print urlencode($item) ?>"> <? print($item) ?> </a> </li>
  <? endforeach ; ?>
</ul>
<? endif; ?>
<? if(isset($data['files'] )): ?>
<ul>
  <? foreach($data['files']  as $item): ?>
  <li> <a href="#select-file=<? print dir2url($item) ?>"> <? print($item) ?> </a> </li>
  <? endforeach ; ?>
</ul>
<? endif; ?>
