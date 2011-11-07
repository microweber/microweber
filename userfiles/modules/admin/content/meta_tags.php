 <?
$id = $params['id'];
if(intval($id) != 0){


$form_values = get_page($id);
}
//p($form_values);

?>
   <div class="formitem">
      <label>Meta Title</label>
      <span class="formfield"><input name="content_meta_title" type="text" value="<? print $form_values['content_meta_title'] ?>" /></span>
  </div>

      <div class="formitem">
      <label>Meta Description</label>
      <span class="formfield"><input name="content_meta_description" type="text" value="<? print $form_values['content_meta_description'] ?>" /></span>

      </div>
 <div class="formitem">
      <label>Meta Keywords</label>
      <span class="formfield"><input name="content_meta_keywords" type="text" value="<? print $form_values['content_meta_keywords'] ?>" /></span>

      </div>
