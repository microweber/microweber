

<!--
<label class="lbl">Price 1 (item price in USD)</label>
<input style="width:200px" name="custom_field_price" id="custom_field_price"   type="text" value="<?php print  $form_values['custom_fields']['price']; ?>" />

<label class="lbl">Price 1 Description</label>
<textarea id="custom_field_price_desc" style="width:350px;height: 150px" cols="10" rows="10" name="custom_field_price_desc"><?php print  $form_values['custom_fields']['price_desc']; ?></textarea>-->


<br />
<br />
<?php for ($i = 1; $i <= 10;  $i++) : ?>
<label class="lbl">Price <?php print $i ?> (item price in USD)</label>
<input style="width:200px" name="custom_field_price<?php print $i ?>"     type="text" value="<?php print  $form_values['custom_fields']['price'.$i]; ?>" />

<label class="lbl">Price <?php print $i ?> Description</label>
<textarea  style="width:350px;height: 150px" cols="10" rows="10" name="custom_field_price_desc<?php print $i ?>"><?php print  $form_values['custom_fields']['price_desc'.$i]; ?></textarea> 
<br />
<br />
<?php endfor ; ?>



<br />
<br />
<label class="lbl">Package Weight (pounds)</label>
<input style="width:200px" name="custom_field_package_weight"  type="text" value="<?php print  $form_values['custom_fields']['package_weight']; ?>"   />

<br />
<br />

<table width="90%" border="0" cellspacing="2" cellpadding="0">
  <tr valign="middle">
    <td><br />
<br />
<label class="lbl">Package Length (inches)</label>
<input style="width:200px" name="custom_field_package_length"  type="text" value="<?php print  $form_values['custom_fields']['package_length']; ?>"   />

<br />
<br />
<label class="lbl">Package Width (inches)</label>
<input style="width:200px" name="custom_field_package_width"  type="text" value="<?php print  $form_values['custom_fields']['package_width']; ?>"   />

<br />
<br />  

<label class="lbl">Package Depth (inches)</label>
<input style="width:200px" name="custom_field_package_height"  type="text" value="<?php print  $form_values['custom_fields']['package_height']; ?>"   /></td>
    <td><img src="<?php print_the_static_files_url() ; ?>images/box_diagram.gif"  border="0" alt=" " /></td>
  </tr>
</table>



<br />
<br />  



<br />
<br />
<div style="clear: both;height: 3px;overflow: hidden">&nbsp;</div>
<textarea id="colors_area" style="width:350px;height: 150px" cols="10" rows="10" name="custom_field_colors"><?php print  $form_values['custom_fields']['colors']; ?></textarea>
<br />
<br />
<label class="lbl">In stock</label>
<input name="custom_field_in_stock" type="radio" value="y" <?php if($form_values['custom_fields']['in_stock'] != 'n') : ?> checked="checked" <?php endif; ?> />
Yes<br />
<input name="custom_field_in_stock" type="radio" value="n" <?php if($form_values['custom_fields']['in_stock'] == 'n') : ?> checked="checked" <?php endif; ?> />
No<br />
<br />
<br />
<label class="lbl">Out of stock message (leave empty to use the default message)</label>
<input style="width:200px" name="custom_field_out_of_stock_message"  type="text" value="<?php print  $form_values['custom_fields']['out_of_stock_message']; ?>" />



<br />
<br />
<label class="lbl">5 years waranty label</label>
<input name="custom_field_waranty_label" type="radio" value="y" <?php if($form_values['custom_fields']['waranty_label'] != 'n') : ?> checked="checked" <?php endif; ?> />
Yes<br />
<input name="custom_field_waranty_label" type="radio" value="n" <?php if($form_values['custom_fields']['waranty_label'] == 'n') : ?> checked="checked" <?php endif; ?> />
No<br />
<br />
<br />