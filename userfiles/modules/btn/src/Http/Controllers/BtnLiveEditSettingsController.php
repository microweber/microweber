<?php

namespace MicroweberPackages\Modules\Btn\Http\Controllers;

use Illuminate\Http\Request;

class BtnLiveEditSettingsController
{
    public function index(Request $request)
    {
        $params = $request->all();

        $style = get_module_option('button_style', $params['id']);
        $size = get_module_option('button_size', $params['id']);
        $action = get_module_option('button_action', $params['id']);
        $align = get_module_option('align', $params['id']);

        $onclick = false;
        if (isset($params['button_onclick'])) {
            $onclick = $params['button_onclick'];
        }


        $url = $url_display = get_module_option('url', $params['id']);
        $url_to_content_id = get_module_option('url_to_content_id', $params['id']);
        $url_to_category_id = get_module_option('url_to_category_id', $params['id']);

        $popupcontent = get_module_option('popupcontent', $params['id']);
        $text = get_module_option('text', $params['id']);
        $url_blank = get_module_option('url_blank', $params['id']);
        $icon = get_module_option('icon', $params['id']);

        $backgroundColor = get_module_option('backgroundColor', $params['id']);
        $color = get_module_option('color', $params['id']);
        $borderColor = get_module_option('borderColor', $params['id']);
        $borderWidth = get_module_option('borderWidth', $params['id']);
        $borderRadius = get_module_option('borderRadius', $params['id']);
        $shadow = get_module_option('shadow', $params['id']);
        $customSize = get_module_option('customSize', $params['id']);


        $hoverbackgroundColor = get_module_option('hoverbackgroundColor', $params['id']);
        $hovercolor = get_module_option('hovercolor', $params['id']);
        $hoverborderColor = get_module_option('hoverborderColor', $params['id']);



        $link_to_content_by_id = 'content:';
        $link_to_category_by_id = 'category:';


        if (substr($url, 0, strlen($link_to_content_by_id)) === $link_to_content_by_id) {
            $link_to_content_by_id = substr($url, strlen($link_to_content_by_id));
            if ($link_to_content_by_id) {
                $url_display = content_link($link_to_content_by_id);
            }
        } else if (substr($url, 0, strlen($link_to_category_by_id)) === $link_to_category_by_id) {
            $link_to_category_by_id = substr($url, strlen($link_to_category_by_id));

            if ($link_to_category_by_id) {
                $url_display = category_link($link_to_category_by_id);
            }
        }

        return view('modules.btn::live_edit.settings');
    }
}
