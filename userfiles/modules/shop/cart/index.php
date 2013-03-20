<script type="text/javascript">
mw.require("shop.js", true);
</script>
<?

$template = get_option('data-template', $params['id']);
$template_css_prefix = '';
$template_file = false;
if ($template != false and strtolower($template) != 'none') {
//
  $template_css_prefix = no_ext($template);
  $template_file = module_templates($params['type'], $template);

//d();
} else {
 	$template_file = module_templates($params['type'], 'default');
}


?>
<?
$cart = array();
$cart['session_id'] = session_id();
$cart['order_completed'] = 'n';
 
 $data = get_cart($cart);
 
 if(is_file($template_file)){
	  
	 include($template_file); 
 }
 
 
 
