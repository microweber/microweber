<? 
 $path = $base_path = $config['path_to_module'].'docs'.DS;
 if(isset($_GET['path'])): ?>
<? $path .=html_entity_decode($_GET['path']).DS;  ?>
<?  
 $dirs =  directory_tree($path);
  $dirs = str_replace($base_path, '', $dirs);
 print $dirs  ;
   ?>
<? endif; ?>
<? if(isset($_GET['file'])): ?>
<?  $file =$base_path.html_entity_decode($_GET['file']);
 $file = str_replace('..','', $file);
if(is_file($file)){
include($file);	
}
 
 ?>
<? endif; ?>
