<?php

only_admin_access();


$modules = mw()->modules->get('installed=1&ui=1');


foreach($modules as $module){


    print '<h1>'.$module['module'].'</h1>';
    print '<module type="'.$module['module'].'/admin" id="mod-'.$module['module'].'" />';

}



$module_layouts = module_templates('layouts');
  foreach($module_layouts as $module){


    print '<module type="layouts"  template="'.($module['layout_file']).'" id="mod-'.md5($module['name']).'" />';

}

