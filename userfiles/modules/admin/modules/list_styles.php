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
$styles = $CI->template_model->getDesignStyles($params);

 
//

?>


<ul class="mw-styles-list">
  <? foreach($styles as $style): ?>
  
 
  
  <li data-style-name="<? print $style['style_name'] ?>" data-style-url="<? print $style['style_url'] ?>"  class="style-item" alt="<? print addslashes($style['style_name']) ?>">
    <? if($style['icon']): ?>
    <img alt="<? print $style['style_name'] ?>" title="<? print addslashes($module2['style_name']) ?>"   data-element-name="<? print $module2['style_name'] ?>"   src="<? print $style['icon'] ?>" height="48"     />
    <? endif; ?>

   <span alt="<? print addslashes($style['description']) ?>"><? print $style['name'] ?></span>   
    
    <!--    <div class="description"><? print $module2['description'] ?></div>--> 
  </li>
   
  <? endforeach; ?>
 
</ul>
<?  }   ?>
