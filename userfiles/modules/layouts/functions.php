<?php
function mw_get_layout_css_classes($params)
{
    $template_config = mw()->template->get_config();
    if (isset($template_config['layouts_css_classes'])) {
        $css_classes = $template_config['layouts_css_classes'];
    } else {
        include('default_layout_classes.php');
    }

    $padding_top = get_option('padding-top', $params['id']);
    if ($padding_top === null OR $padding_top === false OR $padding_top == '') {
        $padding_top = false;
    }

    if ($padding_top AND $css_classes AND isset($css_classes['padding-top']) AND isset($css_classes['padding-top'][$padding_top])) {
        $padding_top = $css_classes['padding-top'][$padding_top];
    }

    $padding_bottom = get_option('padding-bottom', $params['id']);
    if ($padding_bottom === null OR $padding_bottom === false OR $padding_bottom == '') {
        $padding_bottom = false;
    }

    if ($padding_bottom AND $css_classes AND isset($css_classes['padding-bottom']) AND isset($css_classes['padding-bottom'][$padding_bottom])) {
        $padding_bottom = $css_classes['padding-bottom'][$padding_bottom];
    }


    $return = array('padding_top' => $padding_top, 'padding_bottom' => $padding_bottom);
    return $return;
}