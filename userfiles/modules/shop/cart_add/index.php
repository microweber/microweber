<script type="text/javascript">
mw.require("shop.js");
mw.require("events.js");

</script>

<script type="text/javascript">
	mw.on.moduleReload('cart_fields_<? print $params['id'] ?>', function(){
          mw.reload_module('#<? print $params['id'] ?>');
    	});
</script>
<?


$module_template = get_option('data-template',$params['id']);
if($module_template == false and isset($params['template'])){
	$module_template =$params['template'];
} 


$for_id = false;
$for = 'content';

if($module_template != false and $module_template != 'none'){
		$template_file = module_templates( $config['module'], $module_template);

} else {
		$template_file = module_templates( $config['module'], 'default');

}
 if(isset($params['content-id'])){
	 $for_id = $params['content-id'];
 }
 
 
 if(isset($params['for'])){
	 $for = $params['for'];
 }
//d($module_template );


?>
<? if($for_id != false): ?>

<div class="mw-add-to-cart-holder mw-add-to-cart-<? print $params['id'] ?>">
  <module type="custom_fields" data-content-id="<? print $for_id ?>" data-skip-type="price"  id="cart_fields_<? print $params['id'] ?>"  />
  <? $data = get_custom_fields("field_type=price&for={$for}&for_id=".$for_id); ?>
  <? if(isarr($data) == true): ?>
  <input type="hidden"  name="for" value="<? print $for ?>" />
  <input type="hidden"  name="for_id" value="<? print $for_id ?>" />
  <?   if(isset($template_file) and is_file($template_file) != false){
 	include($template_file);
} else {
	mw_err( 'No default template for '.$config['module'].' is found');
}  ?>
  <? endif; ?>
</div>
<? endif; ?>
