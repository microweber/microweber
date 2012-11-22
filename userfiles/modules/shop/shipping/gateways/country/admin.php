
<? $rand1 = 'shipping_to_country_holder'.uniqid(); ?>
<?
 

  require_once($config['path_to_module'].'country_api.php');
 $shipping_to_country = new shipping_to_country(); 
 
 
 $data = $shipping_to_country->get();
 if( $data == false){
	 $data = array(); 
 }
  $data[] = array();
 
     $countries = countries_list();
     $countries_used = array();
     $countries[] = 'Worldwide';
    ?>
<script  type="text/javascript">
  mw.require('forms.js');
  mw.require('<? print $config['url_to_module'] ?>country.js');
 </script>
<script  type="text/javascript">


mw.shipping_country.url = "<? print $config['module_api']; ?>";
  
 $(document).ready(function(){

    mw.$("#<? print $rand1 ?>").sortable({
       items: '.shipping-country-holder',
       axis:'y',
       cancel:".country-id-0",
       handle:'.shipping-handle-field',
       update:function(){
         var obj = {cforder:[]}
         $(this).find('form').each(function(){
            var id = this.attributes['data-field-id'].nodeValue;
            obj.cforder.push(id);
         });
         $.post("<? print $config['module_api']; ?>/shipping_to_country/reorder", obj, function(){
		    mw.reload_module('[data-parent-module="shop/shipping"]');
		 });
       },
       start:function(a,ui){
              $(this).height($(this).outerHeight());
              $(ui.placeholder).height($(ui.item).outerHeight())
              $(ui.placeholder).width($(ui.item).outerWidth())
       },
       scroll:false,
       placeholder: "custom-field-main-table-placeholder"
  });
 
 
}); 
 

 
</script>


<div class="vSpace"></div>
<div class="vSpace"></div>

<div class="mw-shipping-left-bar">


<span class="shipping-truck shipping-truck-green"></span>


<span class="mw-ui-btn">Add Country</span>


</div>


<? if(isarr($data )): ?>



<div class="mw-shipping-items" id="<? print $rand1 ?>">

<? foreach($data  as $item): ?>
<? if(isset($item['shiping_country'])){
	 $countries_used[] = ($item['shiping_country']);
	 
}
?>
<? endforeach ; ?>

  <? foreach($data  as $item): ?>
  
  
  
  
  
  <? 
$new = false;
if(!isset($item['id'])) :?>
  <?  

$item['id']= 0; 
$item['is_active']= 'y'; 
$item['shiping_country']= 'new'; 
$item['shiping_cost']= '0'; 
$item['shiping_cost_max']= '0';
$item['shiping_cost_above']= '0'; 
$item['position']= '999'; 


$new = true;
 ?>
  <? endif;?>
  <? $rand = 'shipping_to_country_'.uniqid().$item['id']; ?>
  <script type="text/javascript">


$(document).ready(function(){
mw.$('#<? print $rand ?>').submit(function() {
	 mw.form.post($(this) , '<? print $config['module_api']; ?>/shipping_to_country/save');
	 <? if($new == true): ?>
        mw.reload_module('<? print $params['data-type']; ?>');
     <? endif; ?>
	   mw.reload_module('[data-parent-module="shop/shipping"]');
	 return false;
	  });
});



</script>
  <div data-field-id="<? print $item['id']; ?>" onmousedown="mw.tools.focus_on(this);" class="shipping-country-holder country-id-<? print $item['id']; ?>">


    <form action="<? print $config['module_api']; ?>/shipping_add_to_country" id="<? print $rand ?>" data-field-id="<? print $item['id']; ?>">

    <div class="shipping-country-row">
      <div class="shipping-country-label">
          <? if($new == true): ?>
          Add new
          <? else : ?>
          Shipping to <? /* print ucfirst($item['shiping_country']); */ ?>
          <? endif; ?>
      </div>
      <div class="shipping-country-setting">
          <? if($new == false): ?>
      <input type="hidden" name="id" value="<? print $item['id']; ?>" >
      <? endif; ?>
      <select name="shiping_country" class="mw-ui-simple-dropdown">
        <? if($new == true): ?>
        <option value="none">Choose country</option>
        <? endif; ?>

        <? foreach($countries  as $item1): ?>
        <?
		$disabled = '';

		foreach($countries_used  as $item2): ?>
        <?
        if($item2 == $item1){
			$disabled = 'disabled="disabled"';
		}

		?>
        <? endforeach ; ?>

        <option value="<? print $item1 ?>"   <? if($item1 == $item['shiping_country']): ?> selected="selected" <? else : ?> <? print $disabled ?> <? endif; ?>  ><? print $item1 ?></option>
        <? endforeach ; ?>
      </select>

      <span class="shipping-arrow"></span>

      <label>Shipping Price <b><? print option_get('currency_sign', 'payments') ?></b></label>

       <input class="mw-ui-field shipping-price-field" type="text" name="shiping_cost" value="<? print $item['shiping_cost']; ?>" onfocus="if(this.value=='0')this.value='';">

      </div>
  </div>



  <button class="mw-ui-btn mw-ui-btn-small" type="submit"><?php _e("Save"); ?></button>




      <? if($new == false): ?>
             <span title="Move" class="ico iMove shipping-handle-field"></span>
             <span onclick="mw.shipping_country.delete_country('<? print $item['id']; ?>');" class="mw-ui-delete-x" title="<?php _e("Delete"); ?>"></span>
     <? endif; ?>



  <div class="shipping-country-row">
        <div class="shipping-country-label">Shipping Discount</div>
        <div class="shipping-country-setting">


        <div class="same-as-country-selector">
          <label>For orders above:</label>
          <input class="mw-ui-field shipping-price-field right" type="text" name="shiping_cost_above" value="<? print $item['shiping_cost_above']; ?>" onfocus="if(this.value=='0')this.value='';">
         </div>
          <span class="shipping-arrow"></span>
          <label>Shipping Price <b><? print option_get('currency_sign', 'payments') ?></b></label>
          <input class="mw-ui-field shipping-price-field" type="text" name="shiping_cost_max" value="<? print $item['shiping_cost_max']; ?>" onfocus="if(this.value=='0')this.value='';" />
        <div class="mw_clear vSpace">&nbsp;</div>
        <div class="mw-ui-check-selector">
            <div class="left" style=" margin-right: 10px;margin-top: 3px;">Is active?</div>
            <label class="mw-ui-check">
              <input name="is_active" type="radio"  value="y" <? if( 'y' == trim($item['is_active'])): ?>   checked="checked"  <? endif; ?> />
              <span></span><span>Yes</span>
            </label>
            <label class="mw-ui-check">
              <input name="is_active" type="radio"  value="n" <? if( '' == trim($item['is_active']) or 'n' == trim($item['is_active'])): ?>   checked="checked"  <? endif; ?> />
              <span></span><span>No</span>
            </label>
        </div>
      </div>
      </div>


      
    </form>
  </div>
  <? endforeach ; ?>
</div>
<div class="mw_clear"></div>
<? endif; ?>
 
