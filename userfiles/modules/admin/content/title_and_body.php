<?
$id = $params['id'];



$form_values = get_content($id);
 

?>

<div class="formitem">
  <label>Title</label>
  <span class="formfield">
  <input style="width: 100%" name="content_title" onchange="mw.buildURL(this.value, '#content_url')"  type="text" value="<? print $form_values['content_title'] ?>" />
  </span> </div>
<div class="formitem">
  <label>URL</label>
  <div id="content_url_page"></div>
  <span class="formfield">
  <input style="width: 100%" name="content_url" type="text"  id="content_url"  value="<? print $form_values['content_url'] ?>" />
  </span> </div>
<div class="formitem">
  <label>Description</label>
  <span class="formfield">
  <input style="width: 100%" name="content_description" type="text" value="<? print $form_values['content_description'] ?>" />
  </span> </div>
<div class="formitem">
  <label>Content</label>
  <textarea name="content_body" style="width: 100%" class="richtext" rows="20" cols="150"><? print $form_values['content_body'] ?></textarea>
</div>
