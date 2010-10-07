
Choose dates:
<script type="text/javascript">
	$(function() {
		$(".datepicker").datepicker({ dateFormat: 'yy-mm-dd' });
		$( ".datepicker" ).datepicker( "option", "dateFormat", 'yy-mm-dd' );
	});
	</script>


<table width="50%" border="1">
  
  <?php for ($i = 1; $i <= 50;  $i++) : ?>
  
    <tr>
    <td><label class="lbl">Date <?php print $i ?></label></td>
    <td><input style="width:200px" class="datepicker" name="custom_field_date_<?php print $i ?>"     type="text" value="<?php print  $form_values['custom_fields']['date_'.$i.'']; ?>" /></td>
     
  </tr>
  

<?php endfor ; ?>



 
</table>


<!--<ul id="offer-opts">
  <li>
    <label class="lbl">Is special?</label>
    <select name="is_special">
      <option <?php if($form_values['is_special'] == 'n' ): ?> selected="selected" <?php endif; ?> value="n">no</option>
      <option <?php if($form_values['is_special'] == 'y' ): ?> selected="selected" <?php endif; ?> value="y">yes</option>
    </select>
  </li>
  <li>
    <label class="lbl">Is seasonal?</label>
    <select name="is_seasonal">
      <option <?php if($form_values['is_seasonal'] == 0 ): ?> selected="selected" <?php endif; ?> value="n">no</option>
      <option <?php if($form_values['is_seasonal'] == 1 ): ?> selected="selected" <?php endif; ?> value="y">yes</option>
    </select>
  </li>
  <li>
    <label class="lbl">Is chosen?</label>
    <select name="is_chosen">
      <option <?php if($form_values['is_chosen'] == 0 ): ?> selected="selected" <?php endif; ?> value="n">no</option>
      <option <?php if($form_values['is_chosen'] == 1 ): ?> selected="selected" <?php endif; ?> value="y">yes</option>
    </select>
  <li>
</ul>-->
<br />
<br />
