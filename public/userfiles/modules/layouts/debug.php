<?php



?>
    <h1><?php _e("All layouts"); ?></h1>
    <hr>
<?php

$templates = module_templates('layouts');


foreach($templates as $template){


    print '<h3>'. _e($template['layout_file']).'</h3>';
    print '<module type="layouts"   template="'.$template['layout_file'].'"  id="layout-'.url_title($template['layout_file']).'" />';

}




?>
<h1>All modules</h1>
<hr>
<?php

$modules = mw()->module_manager->get('installed=1&ui=1');


foreach($modules as $module){


    print '<h3>'. _e($module['module']).'</h3>';
    print '<module type="'.$module['module'].'" id="mod-'.url_title($module['module']).'" />';

}




?>