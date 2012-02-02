
<form id="mercury_link" style="width:600px">
  <div class="mercury-modal-pane-container">
    <div class="mercury-modal-pane">
    
    
    <? $module = url_param('name', true); 
	
	//
	
	
	
	?>
    <? if($module != false): ?>
    <? 
	
	
	$module = str_ireplace("%7C",'|',$module);
	
	
	
	$module = explode('|',$module);
	//p($module);
	?>
    <? $module_name = str_replace('__','/',$module[0]); ?>
     <? $module_id = str_replace(' ','-',$module[1]); ?>
    
    <mw module="admin/<? print $module_name ?>" module_id="<? print $module_id ?>" />
    
     
       
       <? endif; ?>
       
       
    </div>
  </div>
  <div class="mercury-modal-controls">
    <fieldset class="buttons">
      <ol>
        <li class="commit button">
          <input class="submit" name="commit" type="submit" value="Insert Snippet"/>
        </li>
      </ol>
    </fieldset>
  </div>
</form>
