 
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
 </script>
<script  type="text/javascript">
 
 del_country_<? print $rand1 ?> = function($id){
 
 
 var obj = {};
 obj.id = $id;
      $.post("<? print $config['module_api']; ?>/shipping_to_country/delete",  obj, function(data){
      
	  
	  
	    $(".country-id-"+$id).fadeOut(); 
		  mw.reload_module('[data-parent-module="shop/shipping"]');
      });
	  
	  
	  
}
  
 $(document).ready(function(){
 
    mw.$("#<? print $rand1 ?>").sortable({
       items: 'div',
        
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
<? if(isarr($data )): ?>

<div id="<? print $rand1 ?>">

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
  <div data-field-id="<? print $item['id']; ?>" class="shipping-country-holder country-id-<? print $item['id']; ?>">
    <? if($new == true): ?>
    <h1>Add new</h1>
    <? else : ?>
    <h1>Edit shipping to <? print ucfirst($item['shiping_country']) ?> </h1>
    <? endif; ?>
    <? //d( $item); ?>
    <form action="<? print $config['module_api']; ?>/shipping_add_to_country" id="<? print $rand ?>" data-field-id="<? print $item['id']; ?>">
      <? if($new == false): ?>
      <input type="hidden" name="id" value="<? print $item['id']; ?>" >
      <? endif; ?>
      <select name="shiping_country">
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
      
      <? //d($item['shiping_country']); ?>
      <? // d($countries_used); ?>
      <br />
      shiping_cost<br />
      <input type="text" name="shiping_cost" value="<? print $item['shiping_cost']; ?>" onFocus="if(this.value=='0')this.value='';">
      <br />
      For orders above: 
      shiping_cost_above<br />
      <input type="text" name="shiping_cost_above" value="<? print $item['shiping_cost_above']; ?>" onFocus="if(this.value=='0')this.value='';">
      <br />
      shiping_cost_max<br />
      <input type="text" name="shiping_cost_max" value="<? print $item['shiping_cost_max']; ?>" onFocus="if(this.value=='0')this.value='';">
      <br />
      
      
       <div class="mw-ui-check-selector">
        <div class="mw-ui-label left" style="width: 130px">Is Active?</div>
        <label class="mw-ui-check">
          <input name="is_active" type="radio"  value="n" <? if( '' == trim($item['is_active']) or 'n' == trim($item['is_active'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>No</span></label>
        <label class="mw-ui-check">
          <input name="is_active" type="radio"  value="y" <? if( 'y' == trim($item['is_active'])): ?>   checked="checked"  <? endif; ?> />
          <span></span><span>Yes</span></label>
      </div>
      
      
      <button type="submit" >Save</button>
          

      <span title="Move" class="ico iMove shipping-handle-field"></span>
      
         <? if($new == false): ?>
             <span onclick="del_country_<? print $rand1 ?>('<? print $item['id']; ?>');" class="mw-ui-delete">Delete</span>
     <? endif; ?>
  
      
      
    </form>
  </div>
  <? endforeach ; ?>
</div>
<? endif; ?>
 
