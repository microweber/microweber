<?php


namespace MicroweberPackages\LiveEdit;


use MicroweberPackages\Template\Traits\HasScriptsAndStylesTrait;

class LiveEditManager
{
    use HasScriptsAndStylesTrait;


    public function headTags()
    {

        $loadModulesLiveEditJsFiles = [];

        $allModules = mw()->module_manager->get('ui=1&installed=1');

        if ($allModules) {
            foreach ($allModules as $module) {
                $modFile = normalize_path(module_dir($module['module']) . '/live_edit.js', false);
                if (is_file($modFile)) {
                    $scriptUrl = module_url($module['module']) . '/live_edit.js';
                    $this->addScript('mw-module-btn-'.url_title($module['module']).'settings', $scriptUrl );
                }
            }
        }

        $tags = [];

        $tags[] = $this->styles();
        $tags[] = $this->scripts();
        $tags[] = $this->customHeadTags();

        return implode("\r\n", $tags);
    }
}
