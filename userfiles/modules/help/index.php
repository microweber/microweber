   <? 
		 $path = $config['path_to_module'].'docs'.DS;
		if(isset($_GET['file'])): ?>
        <?  $file =$path.html_entity_decode($_GET['file']);
 $file = str_replace('..','', $file);
  
if(is_file($file)){
include($file);	
}
 ?>
        <? else: ?>
        <module type="help/browser" <? if(isset($_GET['path'])): ?> from_path="<? print $_GET['path'] ?>" <? endif; ?> <? if(isset($_GET['kw'])): ?> kw="<? print $_GET['kw'] ?>" <? endif; ?>  <? if(isset($base_path)): ?> base_path="<? print $base_path ?>" <? endif; ?> />
        <? endif; ?>