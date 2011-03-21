 <?
$id = $params['id'];



$form_values = get_page($id);
//p($form_values);

?>
 require_login
  <input name="require_login" type="text" value="<? print $form_values['require_login'] ?>" />
  <hr />
  original_link
  <input name="original_link" type="text" value="<? print $form_values['original_link'] ?>" />