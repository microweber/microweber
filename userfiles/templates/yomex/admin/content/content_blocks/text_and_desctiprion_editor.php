
<label class="lbl">Short Description </label>
<textarea name="content_description" class="richtext"  rows="10" cols="100" style="width: 645px"><?php print $form_values['content_description']; ?></textarea>
<?php /*<br />
<br />
<label class="lbl"><strong>Choose type: *</strong></label>
      <span class="linput">
        <select name="content_subtype" style="width:230px;" id="postSet">
          <option <?php if(($form_values['content_subtype'] == '' ) or ($form_values['content_subtype'] == 'none' )): ?> selected="selected" <?php endif; ?>  value="none">Article</option>
          <!--<option <?php if($form_values['content_subtype'] == 'article' ): ?> selected="selected" <?php endif; ?>  value="services">Article</option>-->
          <option <?php if($form_values['content_subtype'] == 'trainings' ): ?> selected="selected" <?php endif; ?>  value="trainings">Trainings</option>
          <option <?php if($form_values['content_subtype'] == 'products' ): ?> selected="selected" <?php endif; ?>  value="products">Product</option>
          <option <?php if($form_values['content_subtype'] == 'services' ): ?> selected="selected" <?php endif; ?>  value="services">Service</option>
        </select><br />
<br />

 <label class="lbl">Is special?</label>
    <select name="is_special">
      <option <?php if($form_values['is_special'] == 'n' ): ?> selected="selected" <?php endif; ?> value="n">no</option>
      <option <?php if($form_values['is_special'] == 'y' ): ?> selected="selected" <?php endif; ?> value="y">yes</option>
    </select><br />
<br />
*/
 ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><label class="lbl">Content</label>
      <textarea name="content_body" class="richtext" id="content_body" rows="10" cols="10" style="width:645px"><?php print $form_values['content_body']; ?></textarea></td>
  </tr>
</table>
<?php if(intval($form_values['id'])   != 0  ) :


$more = $this->core_model->getCustomFields('table_content', $form_values['id']);
	 if(!empty($more)){
		ksort($more);
	 }  ?>
<?php if(!empty($more)): ?>
<?php $i = 1;
foreach($more as $k => $v): ?>
<?php //var_dump($k); ?>
<?php if((stristr($k, 'content_body_') == true)and (stristr($k, 'title') == false)) : ?>
<?php // var_dump($i); ?>
<div class="chapter_editor">
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><label class="lbl">Content <?php print $i ?></label>
        <input class="content_chapter_title"  style="width:200px" name="custom_field_content_body_<?php print $i ?>_title"  type="text" value="<?php print  $form_values['custom_fields']['content_body_'.$i.'_title']; ?>"   />
        <a href="javascript:;" onclick="$(this).parents('.chapter_editor').remove();">Remove chapter</a>
        <textarea  name="custom_field_content_body_<?php print $i ?>" class="content_chapter richtext" rows="10" cols="10" style="width:645px"><?php print ( $v);  ?></textarea></td>
    </tr>
  </table>
</div>
<?php // print html_entity_decode( $more['content_body_'.$i._]);  ?>
<?php //print html_entity_decode( $more['content_body_'.$i]);  ?>
<?php $i++; ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
<?php endif; ?>
<div id="new_chapters"></div>
<a href="javascript:newEditor('new_chapters');">Add new chapter</a>
<?php /*

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><label class="lbl">Content 2</label>
      <input style="width:200px" name="custom_fields_content_body2_title"  type="text" value="<?php print  $form_values['custom_fields']['content_body2_title']; ?>"   />
      <textarea name="custom_field_content_body2" class="richtext" rows="10" cols="10" style="width:645px"><?php print $form_values['custom_fields']['content_body2']; ?></textarea></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><label class="lbl">Content 3</label>
      <input style="width:200px" name="custom_fields_content_body3_title"  type="text" value="<?php print  $form_values['custom_fields']['content_body3_title']; ?>"   />
      <textarea name="custom_fields_content_body3" class="richtext" rows="10" cols="10" style="width:645px"><?php print $form_values['custom_fields']['content_body3']; ?></textarea></td>
  </tr>
</table>

*/ ?>
<label class="lbl">301 redirect link</label>
<input style="width:200px" name="page_301_redirect_link"  type="text" value="<?php print  $form_values['page_301_redirect_link'] ; ?>"   />
<br />
<br />
<label class="lbl">Visible on site?</label>
<select name="visible_on_frontend">
  <option <?php if($form_values['visible_on_frontend'] != 'n' ): ?> selected="selected" <?php endif; ?> value="y">yes</option>
  <option <?php if($form_values['visible_on_frontend'] == 'n' ): ?> selected="selected" <?php endif; ?> value="n">no</option>
</select>
<br />
