<?php

$cur = get_option('currency', 'payments');   
$curencies = mw()->shop_manager->currency_get();
$cur_pos = get_option('currency_symbol_position', 'payments');  
   
   
?>

<div class="mw-ui-row">
  <div class="mw-ui-col" style="width:70%">
    <div class="mw-ui-col-container">
      <h2>
        <?php _e("Currency settings"); ?>
      </h2>
      <?php if(is_array($curencies )): ?>
      <select name="currency" class="mw-ui-field mw_option_field w100" data-option-group="payments" data-reload="mw_curr_rend">
        <?php if(!$cur): ?>
        <option value="" disabled="disabled" selected="selected">Select currency</option>
        <?php endif; ?>
        <?php foreach($curencies  as $item): ?>
        <option  value="<?php print $item[1] ?>" <?php if($cur == $item[1]): ?> selected="selected" <?php endif; ?>><?php print $item[1] ?> <?php print $item[3] ?> (<?php print $item[2] ?>)</option>
        <?php endforeach ; ?>
      </select>
      <?php endif; ?>
    </div>
  </div>
  <div class="mw-ui-col">
    <div class="mw-ui-col-container">
      <h2>
        <?php _e("Symbol position"); ?>
      </h2>
       <select name="currency_symbol_position" class="mw-ui-field mw_option_field w100" data-option-group="payments" data-reload="mw_curr_rend">
        <option value="default">Default</option>
        <option value="before">Before number</option>
        <option value="after">After number</option>
      </select>
     </div>
  </div>
</div>

<br />
 <a href="javascript:$('#mw_currency_render_holder').toggle();void(0);" class="pull-right"> Advanced <span style="opacity:0.3" class="mw-icon-arrow-down-b"></span> </a>
        <div id="mw_currency_render_holder" style="display:none;"><br />
        
        
        
<module type="shop/payments/currency_render" id="mw_curr_rend" />
</div>