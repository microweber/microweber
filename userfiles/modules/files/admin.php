
<h1>Directory Browser</h1>
<?php
  // Explore the files via a web interface.   
  $script = $config['url']; // the name of this script
  $path   =   MEDIAFILES; // the path the script should access
 if(isset($_REQUEST['path'])){
	 
	  $path   =   $_REQUEST['path']; // the path the script should access
 }
  
 //$data = rglob($path);
 $data = dir_to_array($path, $recursive = 0, $listDirs = 1, $listFiles = 1, $exclude = '');
 
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
<a href="?path=<? print urlencode($path_nav_pop) ?>"><span class="<? print $config['module_class']; ?> path-item"><? print ($item) ?></span><span class="<? print $config['module_class']; ?> ds"><? print DS ?></span></a>
 
<? endforeach ; ?>
<? endif; ?>
<? if(isset($data['dirs'] )): ?>
<ul>
  <? foreach($data['dirs']  as $item): ?>
  <li> <a href="?path=<? print urlencode($item) ?>">
    <? d($item) ?>
    </a> </li>
  <? endforeach ; ?>
</ul>
<? endif; ?>
<? if(isset($data['files'] )): ?>
<ul>
  <? foreach($data['files']  as $item): ?>
  <li> <a target="_blank" href="<? print dir2url($item) ?>">
    <? d($item) ?>
    </a> </li>
  <? endforeach ; ?>
</ul>
<? endif; ?>
