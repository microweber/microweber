<? $v = ( url_param('action', true) );?>
<? if($v) {
	 
	 include("snippets/".$v.'.php');
	 
	 
 }  else { ?>
<? 
$modules_options = array();
$modules_options['skip_admin'] = true;
$modules_options['ui'] = true;
$CI = get_instance ();
if(is_callable($CI->template_model) == false){
 $CI->load->model ( 'Template_model', 'template_model' );
}
$modules = $CI->template_model->getModules($modules_options );


//

?>

<ul class="modules-list">
  <? foreach($modules as $module2): ?>
  <?
		 $module_group2 = explode(DIRECTORY_SEPARATOR ,$module2['module']);
		 $module_group2 = $module_group2[0];
		?>
  <? if($module_group2 != $module_group)  : ?>
  <? if($module_group2 != $module_group)  : ?>
  <? $module2['module'] = str_replace('\\','/',$module2['module']); ?>
  <? $module2['module_clean'] = str_replace('/','__',$module2['module']); ?>
  <? $module2['name_clean'] = str_replace('/','-',$module2['module']); ?>
  <? $module2['name_clean'] = str_replace(' ','-',$module2['name_clean']); ?>
  <li data-filter="<? print $module2['name'] ?>" class="module-item" alt="<? print addslashes($module2['description']) ?>">
    <? if($module2['icon']): ?>
    <img alt="<? print $module2['name'] ?>" title="<? print addslashes($module2['description']) ?>" class="module_draggable" data-module-name="<? print $module2['module'] ?>"  data-module-name-enc="<? print $module2['module_clean'] ?>|<? print $module2['name_clean'] ?>_<? print date("YmdHis") ?>" src="<? print $module2['icon'] ?>" height="32"  width="32" />
    <? endif; ?>
    <span class="module_name" alt="<? print addslashes($module2['description']) ?>"><? print $module2['name'] ?></span>
    
    <!--    <div class="description"><? print $module2['description'] ?></div>--> 
  </li>
  <? endif; ?>
  <? endif; ?>
  <? endforeach; ?>
  <? foreach($modules as $module): ?>
  <?
 $module_group = explode(DIRECTORY_SEPARATOR ,$module['module']);
 $module_group = $module_group[0];
 $showed_module_groups = array();

?>
  <? if(!in_array($module_group, $showed_module_groups))  : ?>
  <? endif; ?>
  <?  $showed_module_groups[] = $module_group; ?>
  <? endforeach; ?>
</ul>
<?  }   ?>
