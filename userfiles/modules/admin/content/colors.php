<?
$id = $params['id'];

if(intval($id) != 0){
	$form_values = get_page($id);
	
	
	//p($form_values);
//$try_parent = url_param('content_parent');
if($try_parent != false){
	//$form_values['content_parent'] = $try_parent;
}
}

   
  // p($form_values);
   
   
?>

<h2 style="font-size: 20px;" class="blue_title">Colors</h2>
<?php if($form_values['custom_fields']['colors'] != ''): ?>
<?php $selected_colors_array = explode(',',$form_values['custom_fields']['colors']); ?>
<?php $selected_colors_array  = trimArray($selected_colors_array );?>
<?php //var_dump($selected_colors_array); ?>
<?php else: ?>
<?php $selected_colors_array = array(); ?>
<?php endif; ?>
<?php $dir = ROOTPATH.'/colors/';
$color_files = array();
$dirHandle = opendir($dir);
$count = -1;
$returnstr = "";
while ($file = readdir($dirHandle)) {
if(!is_dir($file) && strpos($file, '.jpg')>0) {
$count++;
$color_files[] = trim($file); 
}
}
closedir($dirHandle);
asort($color_files);
?>
<script>
function mw_color_selector_populate_hidden_field(){

 elements = $('.icolor:checked');
 
 $va = '';
 
    elements.each(function() {
						    
						   
						  $va =  $va+ ','+  $(this).val(); 
						   
						   
						   });
	 $('.custom_field_colors').val($va);
	



}
</script>
<?  ?>
<?php if(!empty($color_files)) :?>
<table cellpadding="0" cellspacing="0" class="">
  <tr>
    <th>Color</th>
    <th></th>
    <th></th>
    <th>Sizes</th>
    <th>Picture number</th>
  </tr>
  <?php foreach($color_files as $color): ?>
  <?php $color_name = str_replace('_', ' ', $color); ?>
  <?php $color_name = str_replace('.jpg', '', $color_name); ?>
  <?php $color_name = ucwords($color_name); ?>
  <tr>
    <td><img class="custom" style="width: 60px" src="<?php print base_url() ?>colors/<?php print $color; ?>" alt="<?php print $color; ?>" title="<?php print $color; ?>" /></td>
    <td><label class="chk"><?php print $color_name ?></label></td>
    <td><input class="icolor" type="checkbox" name="colors_selector" value="<?php print $color; ?>" <?php if(in_array($color, $selected_colors_array ) == true) : ?> checked="checked" <?php endif; ?> onclick="mw_color_selector_populate_hidden_field()" /></td>
    <td><input  type="text" name="custom_field_color_sizes_<?php print md5($color); ?>" value="<?php print  $form_values['custom_fields']['color_sizes_'. md5($color)]; ?>"   /></td>
    <td><input  type="text" name="custom_field_color_pic_num_<?php print md5($color); ?>" value="<?php print  $form_values['custom_fields']['color_pic_num_'. md5($color)]; ?>"   /></td>
  </tr>
  <div class="clear" style="clear: both;height: 2px;overflow: hidden;"></div>
  <?php endforeach; ?>
</table>
<?php endif; ?>
<input type="hidden" name="custom_field_colors"  class="custom_field_colors" value="<? print $form_values['custom_fields']['colors']; ?>" />
