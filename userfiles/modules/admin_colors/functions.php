<?php



api_expose('mw_admin_colors/get_main_stylesheet_url', function ($params){

    $compiled_output_path = userfiles_path() . 'css/admin-css/';
    $compiled_css_output_path_file_css = normalize_path($compiled_output_path . '__compiled_main.css', false);

    if(is_file($compiled_css_output_path_file_css)){
        @unlink($compiled_css_output_path_file_css);
    }

    $main_css_url = app()->template->get_admin_system_ui_css_url();

    return $main_css_url;
 } );

