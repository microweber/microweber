<?php
namespace Composer\Installers;

class MicroweberInstaller extends BaseInstaller
{
    protected $locations = array(
        'core' => 'src/Microweber/',
        'module' => 'userfiles/modules/{$name}/',
        'module-skin' => 'userfiles/modules/{$name}/templates/',
        'template' => 'userfiles/templates/{$name}/',
        'element' => 'userfiles/elements/{$name}/',
    );

    /**
     * Format package name.
     *
     * For package type microweber-extension, cut off a trailing '-extension' if present and transform
     * to CamelCase keeping existing uppercase chars.
     *
     * For package type microweber-skin, cut off a trailing '-skin' if present.
     *
     */
    public function inflectPackageVars($vars)
    {

        if ($vars['type'] === 'microweber-template') {
            return $this->inflectTemplateVars($vars);
        }
        if ($vars['type'] === 'microweber-module') {
            return $this->inflectModuleVars($vars);
        }

        if ($vars['type'] === 'microweber-skin') {
            return $this->inflectSkinVars($vars);
        }
        if ($vars['type'] === 'microweber-element') {
            return $this->inflectElementVars($vars);
        }
        return $vars;
    }

    protected function inflectTemplateVars($vars)
    {
        $vars['name'] = preg_replace('/-template$/', '', $vars['name']);
        $vars['name'] = preg_replace('/template-$/', '', $vars['name']);

        return $vars;
    }

    protected function inflectModuleVars($vars)
    {
        $vars['name'] = preg_replace('/-module$/', '', $vars['name']);
        $vars['name'] = preg_replace('/module-$/', '', $vars['name']);

        return $vars;
    }

    protected function inflectSkinVars($vars)
    {
        $vars['name'] = preg_replace('/-skin$/', '', $vars['name']);
        $vars['name'] = preg_replace('/skin-$/', '', $vars['name']);

        return $vars;
    }

    protected function inflectElementVars($vars)
    {
        $vars['name'] = preg_replace('/-element$/', '', $vars['name']);
        $vars['name'] = preg_replace('/element-$/', '', $vars['name']);

        return $vars;
    }
}
