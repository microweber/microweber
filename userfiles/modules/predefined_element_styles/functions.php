<?php

api_expose_admin('predefined_element_styles_get_previews', function ($data) {

    //get all jpgs from the folder
    $dir = modules_path() . 'predefined_element_styles/css/scss/';
    $files = glob($dir . '*.jpg');
    $previews = [];
    foreach ($files as $file) {
        $previews[] = basename($file);
    }
    return $previews;
});


event_bind('mw.front', function ($params) {

    template_foot('<link predefined-element-stylesheet-classes="true" type="text/css" rel="stylesheet" href="' . modules_url() . 'predefined_element_styles/css/main-predefined-element-styles.css"/>');

});


