<?
$modules_options = array();
$modules_options['skip_admin'] = true;
$modules_options['ui'] = true;
 
 if(!isset($modules ) ){
//$modules = get_modules($modules_options );
 }
//

?>
<?
$mod_obj_str = 'modules';
 if(isset($is_elements) and $is_elements == true) {
                              $mod_obj_str = 'elements';
                                         $modules = get_elements_from_db();
}     else {

 $modules = get_modules_from_db();
 
}

 ?>
<script type="text/javascript">

 Modules_List_<? print $mod_obj_str ?> = {}

</script>

<ul class="modules-list">
  <? foreach($modules as $module2): ?>
  <?
		//d($module2);
		 
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
			     if(!empty($module2['categories'])){

                  
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


  ?>

   <? $module_id = $module2['name_clean'].'_'.uniqid(); ?>
  <li  id="<?php print $module_id; ?>" data-module-name="<? print $module2['module'] ?>" data-filter="<? print $module2['name'] ?>" data-category="<? isset($module2['categories'])? print addslashes($module2['categories']) : ''; ?>"    class="module-item <? if(isset( $module2['as_element']) and intval($module2['as_element'] == 1)) : ?> module-as-element<? endif; ?>"> <span class="mw_module_hold">

  <script type="text/javascript">
      Modules_List_<? print $mod_obj_str ?>['<?php print($module_id); ?>'] = {
       id:'<?php print($module_id); ?>',
       name:'<? print $module2["module"] ?>',
       title:'<? print $module2["name"] ?>',
       description:'<? print addslashes($module2["description"]) ?>',
       category:'<? print $module2["categories"]; ?>'
     }



  </script>



   <? if($module2['icon']): ?>


    <span class="mw_module_image"> <span class="mw_module_image_shadow"></span>


    <img
                alt="<? print $module2['name'] ?>"
                title="<? isset($module2['description'])? print addslashes($module2['description']) : ''; ?>"
                class="module_draggable"
                data-module-name-enc="<? print $module2['module_clean'] ?>|<? print $module2['name_clean'] ?>_<? print date("YmdHis") ?>"
                src="<? print $module2['icon'] ?>"
                 /> </span>
    <? endif; ?>
    <span class="module_name" alt="<? isset($module2['description'])? print addslashes($module2['description']) : ''; ?>"><? _e($module2['name']); ?></span> </span> </li>
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
