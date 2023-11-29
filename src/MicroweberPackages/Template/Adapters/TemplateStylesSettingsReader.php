<?php

namespace MicroweberPackages\Template\Adapters;

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
                foreach ($settings['settings'] as $setting) {
                    $settingsFromFile = $this->readStyleSettingsFromFile($setting);
                    $settingsFromFolders = $this->readStyleSettingsFromFilesAndFolders($setting);


                    $settings['settings'] = array_merge($settings['settings'], $settingsFromFile['settings'], $settingsFromFolders['settings']);



                }
            }
        }

        return $settings;
    }

    private function readStyleSettingsFromFile($setting)
    {
        $newSettings = [];

        if (isset($setting['readSettingsFromFiles']) and
            is_array($setting['readSettingsFromFiles']) and
            !empty($setting['readSettingsFromFiles'])) {

            $jsonFilesOnTemplate = $setting['readSettingsFromFiles'];

            foreach ($jsonFilesOnTemplate as $jsonFile) {
                $templateColorsFilePath = $this->templateDir . DS . $jsonFile;
                $templateColorsFilePath = $this->normalizePath($templateColorsFilePath, false);

                $settingsFromFile = $this->getStyleSettingsFromFile($templateColorsFilePath);

                if (is_array($settingsFromFile)) {
                    $newSettings = array_merge($newSettings, $settingsFromFile['settings']);
                }
            }
        }

        return ['settings' => $newSettings];
    }

    private function readStyleSettingsFromFilesAndFolders($setting)
    {
        $newSettings = [];

        if (isset($setting['readSettingsFromFolders']) and
            is_array($setting['readSettingsFromFolders']) and
            !empty($setting['readSettingsFromFolders'])) {

            $folders = $setting['readSettingsFromFolders'];

            foreach ($folders as $folder) {
                $templateColorsFolderExists = $this->templateDir . DS . $folder;
                $templateColorsFolderExists = $this->normalizePath($templateColorsFolderExists);

                $settingsFromFolder = $this->getStyleSettingsFromFolder($templateColorsFolderExists);

                if (is_array($settingsFromFolder)) {
                    //add to array insead of merge
                    $newSettings =  array_merge($newSettings, $settingsFromFolder['settings']);

                }
            }
        }

        return ['settings' => $newSettings];
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
                            $settings = array_merge($settings, $settingsFromFile['settings']);
                        }
                    }
                }
            }
        }

        return ['settings' => $settings];
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
