<? $v = ( url_param('action', true) );?>
<? if($v) {
	 
 }  else { ?>
<? 
$modules_options = array();
$modules_options['skip_admin'] = true;
$modules_options['ui'] = true;
$CI = get_instance ();
if(is_callable($CI->template_model) == false){
 $CI->load->model ( 'Template_model', 'template_model' );
}
$templates = $CI->template_model->getModuleTemplates($params['module_name']);

//

?>
 <script type="text/javascript">
function set_template_<? print $params['module_id'] ?>($filename){


//alert($filename, $layout_name);

	 $('input.template_<? print $params['module_id'] ?>').val($filename).change();
 

  //call_layout_config_module();
}

</script>


<input name="template" class="mw_option_field template_<? print $params['module_id'] ?>"  type="text" refresh_modules="forms/mail_form" option_group="<? print $params['module_id'] ?>"  value="<?php print option_get('template', $params['module_id']) ?>" />

<ul class="mw-template-list">
  <? foreach($templates as $style): ?>
  <li data-template-name="<? print $style['name'] ?>" data-template-file="<? print $style['template'] ?>"  class="template-item" alt="<? print addslashes($style['name']) ?>">
   <a href="javascript:set_template_<? print $params['module_id'] ?>('<? print $style['template'] ?>');">
    <? if($style['thumbnail']): ?>
    <img alt="<? print $style['name'] ?>" title="<? print addslashes($style['name']) ?>"   data-element-name="<? print $module2['name'] ?>"   src="<? print $style['thumbnail'] ?>" height="64"     />
    <? endif; ?>
    <span alt="<? print addslashes($style['description']) ?>"><? print $style['name'] ?></span>
    </a>
    
     </li>
  <? endforeach; ?>
</ul>
<?  }   ?>
