<?
$path = $config['path_to_module'].'help'.DS;
$help_pages = directory_tree( $path);
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



<div id="mw_index_help">
  <div id="mw_edit_page_left" class="help-sidebar">
      <?php $info = module_info($config['module']);  ?>
      <?php module_ico_title($info['module']); ?>
      <div class="vSpace"></div>
      <div class="manage-items">

       

      <?   $help_pages = str_replace("directory_tree","mw-accordion-content", $help_pages);


       print $help_pages; ?>
 


 

        <? if($show_modules_help_nav == true): ?>
        <? $module_categories = get_categories('rel=modules') ?>
        <? //d($module_categories); ?>
        <? $modules = get_modules_from_db('ui=any&parent_id=0') ?>
        <? if(isarr($module_categories  )): ?>

        <h3>Modules</h3>
        <ul class="help-nav">
          <? foreach($module_categories  as $module_category): ?>
          <li><strong onclick="mw.tools.accordion(this.parentNode);" class="help-opener"><span class="help-plus"></span><?  print($module_category['title']); ?></strong>


            <? if(isarr($modules  )): ?>
            <ul class="mw-accordion-content">
              <? foreach($modules  as $item): ?>
              <? $cats_for_this_module = get_category_items('count=1&parent_id='.$module_category['id'].'&rel=modules&rel_id='.$item['id']); ?>
              <?  // d( $cats_for_this_module ); ?>
              <? if($cats_for_this_module >0): ?>
              <li>
                    <?php $minfo = module_info($item['module']);  ?>

                <strong onclick="mw.tools.accordion(this.parentNode);" class="help-opener"><img  src="<?  print($minfo['icon']); ?>" alt="" /><span class="help-title"><?  print($item['name']); ?></span></strong>
                <?
				  $module_path  = module_path($item['module']);

				  $module_path_help = $module_path.'help'.DS;

				  $basepath_this_module=str_replace($basepath_modules,"", $module_path_help); ;
				  ?>
                <? if(is_dir($module_path_help)): ?>
                <? $module_help_pages = directory_tree( $module_path_help);
                $module_help_pages = str_replace("directory_tree","mw-accordion-content", $module_help_pages);

					 $module_help_pages = str_replace("?file=","?basepath=". $basepath_this_module."&file=", $module_help_pages);
					print $module_help_pages;
					 ?>
                <? endif; ?>
              </li>
              <? endif; ?>
              <? endforeach ; ?>
            </ul>
            <? endif; ?>
          </li>
          <? endforeach ; ?>
        </ul>
        <? endif; ?>
        <? endif; ?>
      </div>
  </div>
  <div class="mw_edit_page_right" id="help-content">
    <module type="help/browser">
  </div>
</div>
