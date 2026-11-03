<?php

namespace Composer\Installers;

class MicroweberInstaller extends BaseInstaller
{
    /** @var array<string, string> */
    /** @var array<string, string> */
    protected $locations = array(
        'module' => 'Modules/{$target_dir}/',
        'template' => 'Templates/{$target_dir}/'
    );

    /**
     * Format package name.
     *
     * For package type microweber-module, cut off a trailing '-plugin' if present.
     *
     * For package type microweber-template, cut off a trailing '-template' if present.
     */
    public function inflectPackageVars(array $vars): array
    {

        $target_dir = $this->package->getTargetDir();
        if ($target_dir) {
            $vars['target_dir'] = $target_dir;
        }
        if ($vars['type'] === 'microweber-module') {
            return $this->inflectModuleVars($vars);
        }
        if ($vars['type'] === 'microweber-template') {
            return $this->inflectThemeVars($vars);
        }

        return $vars;
    }

    /**
     * @param array<string, string> $vars
     * @return array<string, string>
     */
    protected function inflectModuleVars(array $vars): array
    {
        if(isset($vars['target_dir']) and !empty($vars['target_dir'])){
            return $vars;
        }
        $vars['name'] = $this->pregReplace('/-module$/', '', $vars['name']);
        $vars['name'] = str_replace(array('-', '_'), ' ', $vars['name']);
        $vars['name'] = str_replace(' ', '', ucwords($vars['name']));
        $vars['target_dir'] = $vars['name'];
        return $vars;
    }

    /**
     * @param array<string, string> $vars
     * @return array<string, string>
     */
    protected function inflectThemeVars(array $vars): array
    {
        if(isset($vars['target_dir']) and !empty($vars['target_dir'])){
            return $vars;
        }


        $vars['name'] = $this->pregReplace('/-template$/', '', $vars['name']);
        $vars['name'] = str_replace(array('-', '_'), ' ', $vars['name']);
        $vars['name'] = str_replace(' ', '', ucwords($vars['name']));
        $vars['target_dir'] = $vars['name'];

        return $vars;
    }
}
