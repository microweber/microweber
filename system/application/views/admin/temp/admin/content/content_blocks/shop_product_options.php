<label class="lbl">Price (item price in EUR)</label>
    <input style="width:200px" name="custom_field_price" id="custom_field_price"
    type="text" value="<?php print  $form_values['custom_fields']['price']; ?>" />



       <label class="lbl">Old Price</label>
    <input style="width:200px" name="custom_field_old_price" id="custom_field_old_price"
    type="text" value="<?php print  $form_values['custom_fields']['old_price']; ?>" />



    <label class="lbl">Sizes (separate the sizes with commas ex. XS, S, M, L)</label>
    <input style="width:200px" name="custom_field_sizes" id="custom_field_sizes" type="text"
    value="<?php print  $form_values['custom_fields']['sizes']; ?>" />

    <label class="lbl">Sku</label>
    <input style="width:200px" name="custom_field_sku" id="custom_field_sku" type="text" value="<?php print  $form_values['custom_fields']['sku']; ?>" />


    <label class="lbl">Weight (in Kilograms for sigle item ex. for 200 grams enter 0.200)</label>
    <input style="width:200px" name="custom_field_weight" id="custom_field_weight" type="text" value="<?php print  $form_values['custom_fields']['weight']; ?>" />

<br />
<br />
<?php //var_dump($form_values['custom_fields']); ?>
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
if(!is_dir($file) && strpos($file, '.png')>0) {
$count++;
$color_files[] = trim($file);
}
}
closedir($dirHandle);
asort($color_files);
?>

<?php if(!empty($color_files)) :?>
<table cellpadding="0" cellspacing="0" class="weiru checkbox">
<?php foreach($color_files as $color): ?>
<?php $color_name = str_replace('_', ' ', $color); ?>
<?php $color_name = str_replace('.png', '', $color_name); ?>
<?php $color_name = ucwords($color_name); ?>



        <tr>
            <td>
            <img class="custom" style="height: 20px" src="<?php print base_url() ?>colors/<?php print $color; ?>" alt="<?php print $color; ?>" title="<?php print $color; ?>" />
             </td>
             <td><label class="chk"><?php print $color_name ?></label>
            </td>
            <td><input class="icolor" type="checkbox" name="colors_selector" value="<?php print $color; ?>" <?php if(in_array($color, $selected_colors_array ) == true) : ?> checked="checked" <?php endif; ?> /></td>
            
             <td><input  type="text" name="custom_field_color_sizes_<?php print md5($color); ?>" value="<?php print  $form_values['custom_fields']['color_sizes_'. md5($color)]; ?>"   /></td>
        </tr>




    <div class="clear" style="clear: both;height: 2px;overflow: hidden;"></div>
<?php endforeach; ?>
</table>
<?php endif; ?>

<div class="clear" style="clear: both;height: 2px;overflow: hidden;"></div><br />
<br />



  <h2 style="font-size: 20px;" class="blue_title">Backgound</h2>

<?php if($form_values['custom_fields']['backgound'] != ''): ?>
<?php $selected_colors_array = explode(',',$form_values['custom_fields']['colors']); ?>
<?php $selected_colors_array  = trimArray($selected_colors_array );?>
<?php //var_dump($selected_colors_array); ?>
<?php else: ?>
<?php $selected_colors_array = array(); ?>
<?php endif; ?>
    <?php $dir = ROOTPATH.'/category_backgrounds/';
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

<?php if(!empty($color_files)) :?>
<table cellpadding="0" cellspacing="0" class="weiru checkbox">








<tr>
            <td>
           None
             </td>
             <td>
            </td>
            
            
             <td><input  type="radio" name="custom_field_background"  value=""   /></td>
        </tr>




<?php foreach($color_files as $color): ?>
<?php $color_name = str_replace('_', ' ', $color); ?>
<?php $color_name = str_replace('.jpg', '', $color_name); ?>
<?php $color_name = ucwords($color_name); 
$temp1 = $this->taxonomy_model->getSingleItem($color_name);
?>






        <tr>
            <td>
          <a href="<?php print base_url() ?>category_backgrounds/<?php print $color; ?>">  <img class="custom" style="height: 20px" src="<?php print base_url() ?>category_backgrounds/<?php print $color; ?>" alt="<?php print $color; ?>" title="<?php print $color; ?>" /></a>
             </td>
             <td><label class="chk"><?php print $color_name ?> - <? print $temp1['taxonomy_value'] ?></label>
            </td>
            
            
             <td><input  type="radio" name="custom_field_background"  <?php if($form_values['custom_fields']['background'] == $color ) : ?> checked="checked" <?php endif; ?>  value="<?php print  $color; ?>"   /></td>
        </tr>




    <div class="clear" style="clear: both;height: 2px;overflow: hidden;"></div>
<?php endforeach; ?>
</table>
<?php endif; ?>



<div style="clear: both;height: 3px;overflow: hidden">&nbsp;</div>
<textarea id="colors_area" style="width:350px;height: 150px" cols="10" rows="10" name="custom_field_colors"><?php print  $form_values['custom_fields']['colors']; ?></textarea>
     


<label class="lbl">Added shipping price per item</label>
<input style="width:200px" name="custom_field_added_shipping"  type="text" value="<?php print  $form_values['custom_fields']['added_shipping']; ?>" />


<br />
<br />





<label class="lbl">In stock</label>
<input name="custom_field_in_stock" type="radio" value="y" <?php if($form_values['custom_fields']['in_stock'] != 'n') : ?> checked="checked" <?php endif; ?> />Yes<br />
<input name="custom_field_in_stock" type="radio" value="n" <?php if($form_values['custom_fields']['in_stock'] == 'n') : ?> checked="checked" <?php endif; ?> />No<br />
 <br />
<br />

<label class="lbl">Out of stock message (leave empty to use the default message)</label>
<input style="width:200px" name="custom_field_out_of_stock_message"  type="text" value="<?php print  $form_values['custom_fields']['out_of_stock_message']; ?>" />


 <br />
<br />

<label class="lbl">External product order link</label>
<input style="width:200px" name="custom_field_external_product_order_link"  type="text" value="<?php print  $form_values['custom_fields']['external_product_order_link']; ?>" />   
     
    

       

         
      
      