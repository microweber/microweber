
<?php
if (isset($params['name'])) {
    $params['menu-name'] = $params['name'];
} elseif (isset($params['menu-name'])) {
    $params['menu-name'] = $params['menu-name'];
} else {
    $params['menu-name'] = 'header_menu';
}
$menu_name = get_option('menu_name', $params['id']);
if ($menu_name != false) {
    $params['menu-name'] = $menu_name;
}


if (isset($params['menu-name'])) {
    $menu = get_menus('make_on_not_found=1&one=1&limit=1&title=' . $params['menu-name']);

    if (is_array($menu)) {
        $menu_filter = $params;
        if (!isset($params['ul_class'])) {
            $menu_filter['ul_class'] = 'nav';
        }
        $menu_filter['menu_id'] = intval($menu['id']);
        $module_template = get_option('template', $params['id']);
        if ($module_template == false and isset($params['template'])) {
            $module_template = $params['template'];
        }

        if ($module_template != false and trim($module_template) != '' and trim(strtolower($module_template) != 'none')) {
            $template_file = module_templates($config['module'], $module_template);
        } else {
            $template_file = module_templates($config['module'], 'default');
        }

        if (isset($template_file) and $template_file != '' and is_file($template_file) != false) {
            include($template_file);
        } else {
            $template_file = module_templates($config['module'], 'default');
            include($template_file);
        }

    } else {
        print lnotif(_e("Click on settings to edit this menu", true));
    }

} else {
    print lnotif(_e("Click on settings to edit this menu", true));
}



