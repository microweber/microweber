<?php


$modules = mw()->modules_manager->get('installed=1&ui=1');


foreach($modules as $module){


    print '<h1>'.$module['module'].'</h1>';
    print '<module type="'.$module['module'].'/admin" id="mod-'.$module['module'].'" />';

}



