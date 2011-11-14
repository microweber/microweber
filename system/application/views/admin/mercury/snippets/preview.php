 
    <? $module = url_param('name', true); 
	
	//
	
	
	
	?>
    <? if($module != false): ?>
    <? 
	$module = explode('|',$module);
	//p($module);
	?>
    <? $module_name = str_replace('__','/',$module[0]); ?>
     <? $module_id = str_replace(' ','-',$module[1]); ?>
    
    <mw module="<? print $module_name ?>" module_id="<? print $module_id ?>" />
    
    
           <? endif; ?>
       