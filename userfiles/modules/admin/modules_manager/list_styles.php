<?php $v = ( url_param('action', true) );?>
<?php if($v) {
	 
	 
	 
	 
 }  else { ?>
<?php 
$modules_options = array();
$modules_options['skip_admin'] = true;
$modules_options['ui'] = true;
$CI = get_instance ();
if(is_callable($CI->template_model) == false){
 $CI->load->model ( 'Template_model', 'template_model' );
}
$styles = $CI->template_model->getDesignStyles($params);

 
//

?>


<ul class="mw-styles-list">
  <?php foreach($styles as $style): ?>
  
 
  
  <li data-style-name="<?php print $style['style_name'] ?>" data-style-url="<?php print $style['style_url'] ?>"  class="style-item" alt="<?php print addslashes($style['style_name']) ?>">
    <?php if($style['icon']): ?>
    <img alt="<?php print $style['style_name'] ?>" title="<?php print addslashes($module2['style_name']) ?>"   data-element-name="<?php print $module2['style_name'] ?>"   src="<?php print $style['icon'] ?>" height="48"     />
    <?php endif; ?>

   <span alt="<?php print addslashes($style['description']) ?>"><?php print $style['name'] ?></span>   
    
    <!--    <div class="description"><?php print $module2['description'] ?></div>--> 
  </li>
   
  <?php endforeach; ?>
 
</ul>
<?php  }   ?>
