 
    <label class="lbl">Is special?</label>
    <select name="is_special">
      <option <?php if($form_values['is_special'] == 'n' ): ?> selected="selected" <?php endif; ?> value="n">no</option>
      <option <?php if($form_values['is_special'] == 'y' ): ?> selected="selected" <?php endif; ?> value="y">yes</option>
    </select>
 
<br />
<br />
<br />

<label class="lbl">Stars (number)</label>
<input type="text"  name="custom_field_stars" value="<?php print  $form_values['custom_fields']['stars']; ?>" />
 

<input type="hidden"  name="custom_field_tabs_layout" value="y" /> 


<br />
<br />
<label class="lbl">Accomodation base</label>
<select name="custom_field_accom_base">

<option  <?php if( $form_values['custom_fields']['accom_base'] == '' ): ?> selected="selected" <?php endif; ?> value=""></option>




  <option  <?php if( $form_values['custom_fields']['accom_base'] == 'RO' ): ?> selected="selected" <?php endif; ?>  value="RO">RO - само нощувка</option>
  <option  <?php if( $form_values['custom_fields']['accom_base'] == 'BB' ): ?> selected="selected" <?php endif; ?>  value="BB">BB – нощувка и закуска</option>
   <option  <?php if( $form_values['custom_fields']['accom_base'] == 'HB' ): ?> selected="selected" <?php endif; ?>  value="HB">HB – полупансион</option>
     <option  <?php if( $form_values['custom_fields']['accom_base'] == 'FB' ): ?> selected="selected" <?php endif; ?>  value="FB">FB – пълен пансион</option>
  
   <option  <?php if( $form_values['custom_fields']['accom_base'] == 'AI' ): ?> selected="selected" <?php endif; ?>  value="AI">AI – всичко включено</option>
  <option  <?php if( $form_values['custom_fields']['accom_base'] == 'UAI' ): ?> selected="selected" <?php endif; ?>  value="UAI">UAI – ултра всичко включено</option>
        
       <!-- <option  <?php if( $form_values['custom_fields']['accom_base'] == 'лв.' ): ?> selected="selected" <?php endif; ?>  value="лв.">лв.</option>-->
      </select>




<!-- <label class="lbl">Tabs layout?</label>
      <select name="custom_field_tabs_layout">
        <option  <?php if( $form_values['custom_fields']['tabs_layout'] == 'n' ): ?> selected="selected" <?php endif; ?>  value="n">&nbsp;no&nbsp;</option>
        <option  <?php if( $form_values['custom_fields']['tabs_layout'] == 'y' ): ?> selected="selected" <?php endif; ?>  value="y">&nbsp;yes&nbsp;</option>
      </select>
<br />-->
<br />
<br />
<label class="lbl">Price from</label>
<input style="width:200px" name="custom_field_price"     type="text" value="<?php print  $form_values['custom_fields']['price']; ?>" />
<select name="custom_field_curency">
        <option  <?php if( $form_values['custom_fields']['curency'] == '€' ): ?> selected="selected" <?php endif; ?>  value="€">€</option>
        <option  <?php if( $form_values['custom_fields']['curency'] == 'лв.' ): ?> selected="selected" <?php endif; ?>  value="лв.">лв.</option>
      </select>


<label class="lbl">Price <?php print $i ?> Description</label>
<textarea  class="richtext" style="width:350px;height: 150px" cols="10" rows="10" name="custom_field_price_desc"><?php print  $form_values['custom_fields']['price_desc']; ?></textarea>
<br />
<br />

<label class="lbl">Prices</label>
<textarea id="custom_field_prices" class="richtext" style="width:350px;height: 150px" cols="10" rows="10" name="custom_field_prices"><?php print  $form_values['custom_fields']['prices']; ?></textarea>
<br />
<label class="lbl">Promotions</label>
<textarea id="custom_field_promotions" class="richtext" style="width:350px;height: 150px" cols="10" rows="10" name="custom_field_promotions"><?php print  $form_values['custom_fields']['promotions']; ?></textarea>