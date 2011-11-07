<?php /*   $dir = ROOTPATH.'/colors/';
	$color_files = array();
   $dirHandle = opendir($dir);
   $count = -1;
   $returnstr = "";
   while ($file = readdir($dirHandle)) {
      if(!is_dir($file) && strpos($file, '.png')>0) {
         $count++;
		 $color_files[] = $file;
       }
   } 
   closedir($dirHandle);
   asort($color_files);*/
?>
<?php // if(!empty($color_files)) :?>
<?php // foreach($color_files as $color): ?>
<?php // $color_name = str_replace('_', ' ', $color); ?>
<?php //$color_name = str_replace('.png', '', $color_name); ?>
<?php //$color_name = ucwords($color_name); ?>
<!--<img src="<?php print base_url() ?>colors/<?php print $color; ?>" height="25" /><?php print $color_name ?><br />-->
<?php //endforeach; ?>
<?php //endif; ?>
<label class="lbl">Short Description </label>
<textarea name="content_description"  rows="10" cols="100" style="width: 645px"><?php print $form_values['content_description']; ?></textarea>
<br />
<br />
<label class="lbl"><strong>Choose type: *</strong></label>
      <span class="linput">
        <select name="content_subtype" style="width:230px;" id="postSet">
          <option <?php if(($form_values['content_subtype'] == '' ) or ($form_values['content_subtype'] == 'none' )): ?> selected="selected" <?php endif; ?>  value="none">Select:</option>
          <option <?php if($form_values['content_subtype'] == 'article' ): ?> selected="selected" <?php endif; ?>  value="services">Article</option>
          <option <?php if($form_values['content_subtype'] == 'trainings' ): ?> selected="selected" <?php endif; ?>  value="trainings">Trainings</option>
          <option <?php if($form_values['content_subtype'] == 'products' ): ?> selected="selected" <?php endif; ?>  value="products">Product</option>
          <option <?php if($form_values['content_subtype'] == 'services' ): ?> selected="selected" <?php endif; ?>  value="services">Service</option>
        </select><br />
<br />

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><label class="lbl">Content</label>
      <textarea name="content_body" class="richtext" id="content_body" rows="10" cols="10" style="width:645px"><?php print $form_values['content_body']; ?></textarea></td>
  </tr>
</table>
