 <?
$id = $params['id'];



$form_values = get_page($id);
//p($form_values);

?>
  <? if($params['for'] == "page"): ?>
  
  <label>Is Home</label>
  <span class="formfield"><input name="is_home" type="text" value="<? print $form_values['is_home'] ?>" /> </span>
  
  
  
  <div class="formitem">
            <label>Parent Page</label>
            <span class="formfield">
            <select name='content_parent' style="width: 300px;">
            <option value="">none</option>
              <?php

 CI::model('content')->content_helpers_getPagesAsUlTree(0, "<option   {removed_ids_code}  {active_code}  value='{id}' />{content_title}", array($form_values['content_parent']), 'checked="checked"', array($form_values['id']) , 'disabled="disabled"' );

 ?>
            </select>
            </span> </div>
  
  <? endif ; ?>






  
  <div class="formitem">
    <label>Require login?</label>
    <span class="formfield"><input name="require_login" type="text" value="<? print $form_values['require_login'] ?>" /> </span>
  </div>
  <div class="formitem">
  <label> Original link</label>
  <span class="formfield"><input name="original_link" type="text" value="<? print $form_values['original_link'] ?>" /></span>
   </div>
  
