<?php


namespace MicroweberPackages\LiveEdit;


use MicroweberPackages\Template\Traits\HasScriptsAndStylesTrait;

class LiveEditManager
{
    use HasScriptsAndStylesTrait;

    public function __construct()
    {
        $allModules = get_modules('installed=1');
        if ($allModules) {
            foreach ($allModules as $module) {
                $modFile = normalize_path(module_dir($module['module']) . '/live_edit.js', false);
                if (is_file($modFile)) {
                    $scriptUrl = module_url($module['module']) . 'live_edit.js';

                    $this->addScript('mw-module-' . url_title($module['module']) . '-settings', $scriptUrl);
                }
            }
        }
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
