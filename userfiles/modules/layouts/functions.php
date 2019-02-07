<?php
function mw_get_layout_css_classes($params)
{
    $template_config = mw()->template->get_config();
    if (isset($template_config['layouts_css_classes'])) {
        $css_classes = $template_config['layouts_css_classes'];
    } else {
        $css_classes = [];
        $css_classes['padding-top'][1] = 'mw-p-t-10';
        $css_classes['padding-top'][2] = 'mw-p-t-20';
        $css_classes['padding-top'][3] = 'mw-p-t-30';
        $css_classes['padding-top'][4] = 'mw-p-t-40';
        $css_classes['padding-top'][5] = 'mw-p-t-50';
        $css_classes['padding-top'][6] = 'mw-p-t-60';
        $css_classes['padding-top'][7] = 'mw-p-t-70';
        $css_classes['padding-top'][8] = 'mw-p-t-80';
        $css_classes['padding-top'][9] = 'mw-p-t-90';
        $css_classes['padding-top'][10] = 'mw-p-t-100';

        $css_classes['padding-bottom'][1] = 'mw-b-t-10';
        $css_classes['padding-bottom'][2] = 'mw-b-t-20';
        $css_classes['padding-bottom'][3] = 'mw-b-t-30';
        $css_classes['padding-bottom'][4] = 'mw-b-t-40';
        $css_classes['padding-bottom'][5] = 'mw-b-t-50';
        $css_classes['padding-bottom'][6] = 'mw-b-t-60';
        $css_classes['padding-bottom'][7] = 'mw-b-t-70';
        $css_classes['padding-bottom'][8] = 'mw-b-t-80';
        $css_classes['padding-bottom'][9] = 'mw-b-t-90';
        $css_classes['padding-bottom'][10] = 'mw-b-t-100';
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