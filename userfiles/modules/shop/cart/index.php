
<script>mw.require("tools.js", true);</script>
<script>mw.require("shop.js", true);</script>

<?php

$template = get_option('data-template', $params['id']);
$template_css_prefix = '';
$template_file = false;
$module_template = false;
if ($template != false and strtolower($template) != 'none') {
  $template_css_prefix = no_ext($template);
  $template_file = module_templates($params['type'], $template);

} else {
	if($template == false and isset($params['template'])){
		$module_template =$params['template'];
		$template_file = module_templates($params['type'], $module_template);
	} else {
	    $template_file = module_templates($params['type'], 'default');
	}
}
$sid = mw()->user_manager->session_id();
if($sid == ''){
    // //session_start();
}
$cart = array();
$cart['session_id'] = mw()->user_manager->session_id();
$cart['order_completed'] = 0;

$data = get_cart($cart);
   
if(is_file($template_file) and !isset($params['hide-cart'])){
    include($template_file);
}