<?php

only_admin_access();
$path = $path_here = $config['path_to_module'].'help'.DS;





$basepath_modules = MODULES_DIR;

$get_file = false;
$get_path = false;
if(isset($_GET['file'])){
	$get_file = $_GET['file'];
}
if(isset($_GET['path'])){
$get_path = $path.$_GET['path'];
} else if(isset($_GET['basepath'])){
	$bp = str_replace('..','',$_GET['basepath']);
$get_path = $basepath_modules.$bp;
}else if(isset($_GET['base'])){
	$bp = str_replace('..','',$_GET['base']);
$get_path = $basepath_modules.$bp;
} else  {

$get_path = $path;
}


$show_modules_help_nav = true;

mw_var('mw_help_file',$get_file);
mw_var('mw_help_path',$get_path);

 ?>
<script>mw.require('<?php print $config['url_to_module']; ?>help.css', true);</script>

<script>

$(document).ready(function(){
    var mainhelp = mwd.getElementById('main-help');
    var titles = mainhelp.querySelectorAll("h2,h3,h4,h5"), i=0, l=titles.length;


    for( ; i<l; i++){
        var item = titles[i];
        item.innerHTML = '<span class="help-plus"></span>' + item.innerHTML;
        item.nextElementSibling.className += ' mw-accordion-content';
        item.onclick = function(){
            mw.tools.accordion(item.parentNode);
        }
    }


});


</script>

<div id="mw_index_help">
  <div id="mw_edit_page_left" class="mw_edit_page_default">
    <div class="mw-admin-sidebar">
      <?php $info = module_info($config['module']);  ?>
      <?php module_ico_title($info['module']); ?>
    </div>
    <div class="manage-items" id="main-help">
      <?php
      // .. $help_pages = str_replace("directory_tree","ssshelp-nav", $help_pages);
         static_pages_tree('title_class=help-opener&class=help-nav&dir_name='.$path.'&url='.$config['url_base']);




      ?>
    </div>
    <?php if($show_modules_help_nav == true): ?>
    <?php $module_categories = mw('category')->get('rel=modules') ?>
      
    <?php $modules = get_modules_from_db('ui=1&parent_id=0&have=categories');
	$module_categories_ids = array();
 if(isarr($modules  )){
	 foreach($modules  as $item){
		 
		 
		$module_path  = module_path($item['module']);

		$module_path_help = $module_path.'help'.DS;
		 
		if(is_dir($module_path_help)){ 
			  $cats_for_this_module = mw('category')->get_items('rel=modules&rel_id='.$item['id']);
				if(isarr($cats_for_this_module  )){
					 foreach($cats_for_this_module  as $module_categories_id){
						//  d($module_categories_id);
						$module_categories_ids[] = $module_categories_id['parent_id'];
					 }
				}
			 }
	 }
	 
	 
	 
	 
 }
	 if(isarr($module_categories_ids  )){
		 $module_categories_ids = array_unique($module_categories_ids);
		 if(isarr($module_categories_ids  )){
			 $module_categories = array();
			 foreach($module_categories_ids  as $module_categories_id){
				
				$module_categories[] = get_category_by_id($module_categories_id);
			 }
		 }
		    
	 }
	 
	
	 ?>
    <?php if(isarr($module_categories  )): ?>
    <div class="mw-admin-sidebar">
      <h2>Modules</h2>
    </div>
    <ul class="help-nav">
    <?php // d($module_categories); ?>
      <?php foreach($module_categories  as $module_category): ?>
      
        <?php if(isarr($modules  )): ?>
        <li><strong onclick="mw.tools.accordion(this.parentNode);" class="help-opener"><span class="help-plus"></span>
        <?php  print($module_category['title']); ?>
        </strong>
        <ul class="mw-accordion-content">
          <?php foreach($modules  as $item): ?>
          
          <?php if(strtolower($item['module'])  != 'help'): ?>
          <?php $cats_for_this_module = mw('category')->get_items('count=1&parent_id='.$module_category['id'].'&rel=modules&rel_id='.$item['id']); ?>
          <?php  // d( $cats_for_this_module ); ?>
          <?php if($cats_for_this_module >0): ?>
          <?php $minfo = module_info($item['module']);  ?>
          <?php
				  $module_path  = module_path($item['module']);

				  $module_path_help = $module_path.'help'.DS;

				  $basepath_this_module=str_replace($basepath_modules,"", $module_path_help); ;
				  ?>
          <?php if(is_dir($module_path_help)): ?>
          <li> <strong onclick="mw.tools.accordion(this.parentNode);" class="help-opener"><img  src="<?php  print($minfo['icon']); ?>" alt="" /><span class="help-title">
            <?php  print($item['name']); ?>
            </span></strong>
            <?php // $module_help_pages = directory_tree( $module_path_help);

			  static_pages_tree('class=mw-accordion-content&dir_name='.$module_path_help.'&url='.$config['url_base'].'/module_help:'.module_name_encode($item['module']));


                //$module_help_pages = str_replace("directory_tree","mw-accordion-content", $module_help_pages);

					// $module_help_pages = str_replace("?file=","?basepath=". $basepath_this_module."&file=", $module_help_pages);
					//print $module_help_pages;
					 ?>
          </li>
          <?php endif; ?>
          <?php endif; ?>
          <?php endif; ?>
          <?php endforeach ; ?>
        </ul>
        <?php endif; ?>
      </li>
      <?php endforeach ; ?>
    </ul>
    <?php endif; ?>
    <?php endif; ?>
  </div>
</div>
<div class="mw_edit_page_right" id="help-content">

<?php

$page_path = $path_here;
$module_help_url = url_param('module_help');
if($module_help_url != false and $module_help_url != ''){
$module_help_url = module_name_decode($module_help_url);	
 $module_path  = module_path($module_help_url);
 $page_path = $module_path.'help'.DS;
}
 


 ?>
  <?php  print static_page_get('dir_name='.$page_path); ?>
</div>
</div>
