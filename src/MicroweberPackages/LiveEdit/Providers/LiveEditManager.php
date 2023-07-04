<?php


namespace MicroweberPackages\LiveEdit\Providers;


use MicroweberPackages\Admin\MenuBuilder\Menu;
use MicroweberPackages\Template\Traits\HasMenus;
use MicroweberPackages\Template\Traits\HasScriptsAndStylesTrait;


class LiveEditManager
{
    use HasMenus;
    use HasScriptsAndStylesTrait;

    public function __construct()
    {
        $allModules = get_modules('installed=1');
        if ($allModules) {
            foreach ($allModules as $module) {
                $modFile = normalize_path(module_dir($module['module']) . '/live_edit.js', false);
                if (is_file($modFile)) {
                    $scriptUrl = module_url($module['module']) . 'live_edit.js';

                    $this->addScript('mw-module-' . url_title($module['module']) . '-settings', $scriptUrl, ['type' => 'module', 'defer' => true]);
                }
            }
        }

        $this->initMenus();
    }

    public function initMenus()
    {
        $this->menus['top_right_menu'] = new Menu();
    }

    public function headTags()
    {
        $tags = [];
        $tags[] = $this->styles();
        $tags[] = $this->scripts();
        $tags[] = $this->customHeadTags();

        return implode("\r\n", $tags);
    }
}
