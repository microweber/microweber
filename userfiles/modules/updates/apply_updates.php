<?   

only_admin_access();

 	
	 $update_api = new \mw\update();
 
	

 d($_POST);
 ?>apply_updates
 
 
 <?  if(isset($_POST['mw_version'])){ ?>
  
 <h2>Installing new version of Microweber: <? print  $_POST['mw_version'] ?></h2>
<textarea>
<? $iudates = $update_api -> install_version($_POST['mw_version']); 
d($iudates);
?>
</textarea>
 
<? }  ?>
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 <? 
 if(isset($_POST['modules'])){ ?>
 <? if(isarr($_POST['modules'])): ?>
  <? foreach($_POST['modules']  as $item): ?> 
 <h2>Installing module: <? print  $item ?></h2>
<textarea>
<? $iudates = $update_api -> install_module($item); 
d($iudates);
?>
</textarea>
 <? endforeach ; ?>
<? endif; ?>
<? }  ?>