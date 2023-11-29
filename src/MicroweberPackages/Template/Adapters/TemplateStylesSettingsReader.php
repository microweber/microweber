<?php

namespace MicroweberPackages\Template\Adapters;

use MicroweberPackages\Template\Adapters\RenderHelpers\TemplateMetaTagsRenderer;
use MicroweberPackages\Template\Http\Livewire\Admin\StyleSettingsFirstLevelConvertor;
use MicroweberPackages\View\View;

class TemplateStylesSettingsReader
{
    private $templateDir;

    public function __construct($templateDir = false)
    {
        $this->templateDir = $templateDir ?: $this->getActiveTemplateDir();
    }

    public function getStyleSettings()
    {
        $settings = $this->getStyleSettingsFromFile($this->templateDir . 'style-settings.json');
        return $settings;
    }

    private function getStyleSettingsFromFile($filePath)
    {
        $settings = [];

        if (is_file($filePath)) {
            $settings = @file_get_contents($filePath);
            $settings = @json_decode($settings, true);

            if (isset($settings['settings']) and is_array($settings['settings']) and !empty($settings['settings'])) {
                foreach ($settings['settings'] as &$setting) {
                    $this->readStyleSettingsFromFiles($setting);
                    $this->readStyleSettingsFromFolders($setting);
                }
            }
        }

        return $settings;
    }

    private function readStyleSettingsFromFiles(&$setting)
    {
        if (isset($setting['fieldSettings']['readFromFiles']) and
            is_array($setting['fieldSettings']['readFromFiles']) and
            !empty($setting['fieldSettings']['readFromFiles'])) {

            $jsonFilesOnTemplate = $setting['fieldSettings']['readFromFiles'];

            foreach ($jsonFilesOnTemplate as $jsonFileColor) {
                $templateColorsFilePath = $this->templateDir . DS . $jsonFileColor;
                $templateColorsFilePath = $this->normalizePath($templateColorsFilePath, false);

                $settingsFromFile = $this->getStyleSettingsFromFile($templateColorsFilePath);

                if (is_array($settingsFromFile)) {
                    $setting['fieldSettings'] = array_merge($setting['fieldSettings'], $settingsFromFile);
                }
            }
        }
    }

    private function readStyleSettingsFromFolders(&$setting)
    {
        if (isset($setting['fieldSettings']['readFromFolders']) and $setting['fieldSettings']['readFromFolders']) {
            $folders = $setting['fieldSettings']['readFromFolders'];
            foreach ($folders as $folder) {
                $templateColorsFolderExists = $this->templateDir . DS . $folder;
                $templateColorsFolderExists = $this->normalizePath($templateColorsFolderExists);

                $settingsFromFolder = $this->getStyleSettingsFromFolder($templateColorsFolderExists);

                if (is_array($settingsFromFolder)) {
                    $setting['fieldSettings'] = array_merge($setting['fieldSettings'], $settingsFromFolder);
                }
            }
        }
    }

    private function getStyleSettingsFromFolder($folderPath)
    {
        $settings = [];

        if (is_dir($folderPath)) {
            $templateColorsFolderExistsContent = glob($folderPath . DS . '*.json');

            if (is_array($templateColorsFolderExistsContent)) {
                foreach ($templateColorsFolderExistsContent as $templateColorsFolderExistsContentItem) {
                    if (is_file($templateColorsFolderExistsContentItem)) {
                        $settingsFromFile = $this->getStyleSettingsFromFile($templateColorsFolderExistsContentItem);

                        if (is_array($settingsFromFile)) {
                            $settings = array_merge($settings, $settingsFromFile);
                        }
                    }
                }
            }
        }

        return $settings;
    }

    private function getActiveTemplateDir()
    {
        return app()->template->dir();
    }

    private function normalizePath($path, $isDirectory = true)
    {
        return normalize_path($path, $isDirectory);
    }
}
