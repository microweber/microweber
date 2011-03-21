 <?
$id = $params['id'];



$form_values = get_page($id);
//p($form_values);

?>
  Meta<br />
      <br />
      content_meta_title
      <input name="content_meta_title" type="text" value="<? print $form_values['content_meta_title'] ?>" />
      <hr />
      content_meta_description
      <input name="content_meta_description" type="text" value="<? print $form_values['content_meta_description'] ?>" />
      <hr />
      content_meta_keywords
      <input name="content_meta_keywords" type="text" value="<? print $form_values['content_meta_keywords'] ?>" />
      