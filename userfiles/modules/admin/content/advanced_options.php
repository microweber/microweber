 <?
$id = $params['id'];



$form_values = get_page($id);
//p($form_values);

?>
  <? if($params['for'] == "page"): ?>
  
  <label>Is Home</label>
  <span class="formfield"><input name="is_home" type="text" value="<? print $form_values['is_home'] ?>" /> </span>
  
  <? endif ; ?>
  
  
 require_login
  <input name="require_login" type="text" value="<? print $form_values['require_login'] ?>" />
  <hr />
  original_link
  <input name="original_link" type="text" value="<? print $form_values['original_link'] ?>" />
  
  
