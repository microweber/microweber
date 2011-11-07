 <?
$id = $params['id'];

if(intval($id) != 0){
	$form_values = get_page($id);
	
	
	//p($form_values);
$try_parent = url_param('content_parent');
if($try_parent != false){
	$form_values['content_parent'] = $try_parent;
}
}

   
   
   
   
?>
  <? if($params['for'] == "page"): ?>
  
  <label>Is Home</label>
  
  
  
  <span class="formfield">
  <select name='is_home'>
  <option value="n">no</option>
   <option value="y" <? if($form_values['is_home'] == 'y') : ?> selected="selected" <? endif; ?> >yes</option>
    </select>
  
  
  
  </span>
  
  
 
  
  <? endif ; ?>






  
  <div class="formitem">
    <label>Require login?</label>
    
     <span class="formfield">
     <select name='require_login'>
  <option>no</option>
   <option value="y" <? if($form_values['require_login'] == 'y'): ?> selected="selected" <? endif; ?> >yes</option>
    </select>
    
    
     </span>
  </div>
  <div class="formitem">
  <label> Original link</label>
  <span class="formfield"><input name="original_link" type="text" value="<? print $form_values['original_link'] ?>" /></span>
   </div>
  
