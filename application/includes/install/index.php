<?php

defined('T') or die();
$installed = c('installed');
if ($installed != false) {

    exit('Microweber seems to be already installed!');
}


$to_save = $_GET;
if (isset($to_save)) {
    if (isset($to_save['submit'])) {
        $f = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'config.base.php';
        $save_config = file_get_contents($f);
        foreach ($to_save as $k => $v) {
            $save_config = str_ireplace('{' . $k . '}', $v, $save_config);
        }
        var_dump($save_config);
    }



    $f = INCLUDES_PATH . 'install' . DIRECTORY_SEPARATOR . 'main.php';
    include($f);
}
 