<?
$modules_options = array();
$modules_options['skip_admin'] = true;
$modules_options['ui'] = true;

 if(!isset($modules ) ){
//$modules = scan_for_get_modules($modules_options );
 }
//

?>
<?
$mod_obj_str = 'modules';
 if(isset($is_elements) and $is_elements == true) {
                              $mod_obj_str = 'elements';
                              $el_params = array();
if(isset($params['layout_type'])){
  $el_params['layout_type'] = $params['layout_type'];
}
                                         $modules = get_elements_from_db($el_params);
										//
}     else {

 $modules = get_modules_from_db();

}

 ?>
   <? foreach($modules as $moduleddasdas2): ?>
  <? //d($moduleddasdas2); ?>
    <? endforeach; ?>
<script type="text/javascript">

 Modules_List_<? print $mod_obj_str ?> = {}

</script>

<ul class="modules-list list-<? print $mod_obj_str ?>">
  <? foreach($modules as $module2): ?>

   <? if(isset($module2['module'])): ?>

  <?


		 $module_group2 = explode(DIRECTORY_SEPARATOR ,$module2['module']);
		 $module_group2 = $module_group2[0];
		?>
  <? $module2['module'] = str_replace('\\','/',$module2['module']);

  $module2['module'] = rtrim($module2['module'],'/');
  $module2['module'] = rtrim($module2['module'],'\\');
                 $module2['categories'] =    get('fields=parent_id,id&limit=100&what=category_items&for='.$mod_obj_str.'&to_table_id='.$module2['id']);
                              //d($module2['categories']);
               $temp = array();
			   // $temp2 = array();
			     if(is_array($module2['categories']) and !empty($module2['categories'])){


                   foreach($module2['categories'] as $it){
                    $temp[]            = $it['parent_id'];
					//  $temp2[]            = $it['id'];
                   }
                   $module2['categories'] = implode(',',$temp);
				 //   $module2['categories_ids'] = implode(',',$temp2);
				//   d( $module2['categories']);
                 }

   ?>
  <? $module2['module_clean'] = str_replace('/','__',$module2['module']); ?>
  <? $module2['name_clean'] = str_replace('/','-',$module2['module']); ?>
  <? $module2['name_clean'] = str_replace(' ','-',$module2['name_clean']);
if(is_array($module2['categories'])){
$module2['categories'] = implode(',',$module2['categories']);
}

  ?>

   <? $module_id = $module2['name_clean'].'_'.uniqid(); ?>
  <li  id="<?php print $module_id; ?>" data-module-name="<? print $module2['module'] ?>" data-filter="<? print $module2['name'] ?>" data-category="<? isset($module2['categories'])? print addslashes($module2['categories']) : ''; ?>"    class="module-item <? if(isset( $module2['as_element']) and intval($module2['as_element'] == 1) or (isset($is_elements) and $is_elements == true)) : ?> module-as-element<? endif; ?>"> <span class="mw_module_hold">

  <script type="text/javascript">
      Modules_List_<? print $mod_obj_str ?>['<?php print($module_id); ?>'] = {
       id:'<?php print($module_id); ?>',
       name:'<? print $module2["module"] ?>',
       title:'<? print $module2["name"] ?>',
       description:'<? print addslashes($module2["description"]) ?>',
       category:'<? print addslashes($module2["categories"]); ?>'
     }



  </script>



   <? if($module2['icon']): ?>


    <span class="mw_module_image">


    <span class="mw_module_image_holder"><img
                alt="<? print $module2['name'] ?>"
                title="<? isset($module2['description'])? print addslashes($module2['description']) : ''; ?>"
                class="module_draggable"
                data-module-name-enc="<? print $module2['module_clean'] ?>|<? print $module2['name_clean'] ?>_<? print date("YmdHis") ?>"
                src="<? print $module2['icon'] ?>"
                 /> <s class="mw_module_image_shadow"></s></span></span>
    <? endif; ?>
    <span class="module_name" alt="<? isset($module2['description'])? print addslashes($module2['description']) : ''; ?>"><? _e($module2['name']); ?></span>  </span> </li>



     <? endif; ?>
  <? endforeach; ?>

</ul>
