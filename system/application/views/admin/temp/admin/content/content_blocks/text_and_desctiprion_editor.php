
<label class="lbl">Short Description </label>
<textarea name="content_description"  rows="10" cols="100" style="width: 645px"><?php print $form_values['content_description']; ?></textarea>
<br />
<br />
<label class="lbl"><strong>content filename: </strong></label>
      <span class="linput">


                  <input name="content_body_filename" type="text" value="<?php print $form_values['content_body_filename']; ?>">
<br />

 <label class="lbl">Is special?</label>
    <select name="is_special">
      <option <?php if($form_values['is_special'] == 'n' ): ?> selected="selected" <?php endif; ?> value="n">no</option>
      <option <?php if($form_values['is_special'] == 'y' ): ?> selected="selected" <?php endif; ?> value="y">yes</option>
    </select><br />
<br />

 
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><label class="lbl">Content</label>
      <textarea name="content_body" class="richtext" id="content_body" rows="10" cols="10" style="width:645px"><?php print $form_values['content_body']; ?></textarea></td>
  </tr>
</table>

<br />
<br />

<label class="lbl">Embed code</label>
<textarea name="custom_field_embed_code"  class="" id="" rows="10" cols="10" style="width:645px"><?php print  $form_values['custom_fields']['embed_code']; ?></textarea>

 
 
<br />
<br />



<label class="lbl">External link</label>
<input style="width:200px" name="custom_field_external_link"  type="text" value="<?php print  $form_values['custom_fields']['external_link']; ?>" />


