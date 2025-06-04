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

            if (isset($settings['styleSettingsVars']) && is_array($settings['styleSettingsVars']) && !empty($settings['styleSettingsVars'])) {
                foreach ($settings['styleSettingsVars'] as $keySettings => $setting) {
                    // Process all nested settings
                    if (isset($setting['settings']) && is_array($setting['settings'])) {
                        foreach ($setting['settings'] as $subKey => $subSetting) {
                            // Process mergeFieldSettingsFromFiles for each nested setting
                            if (isset($subSetting['mergeFieldSettingsFromFiles']) &&
                                is_array($subSetting['mergeFieldSettingsFromFiles']) &&
                                !empty($subSetting['mergeFieldSettingsFromFiles'])) {
                                $this->processMergeFieldSettingsFromFiles($subSetting);
                                $settings['styleSettingsVars'][$keySettings]['settings'][$subKey] = $subSetting;
                            }
                        }
                    }

                    // Process readSettingsFromFiles and readSettingsFromFolders
                    $settingsFromFoldersAndFiles = $this->readStyleSettingsFromFilesAndFolders($setting);
                    if (!empty($settingsFromFoldersAndFiles['settings'])) {
                        $settings['styleSettingsVars'][$keySettings]['settings'] = isset($settings['styleSettingsVars'][$keySettings]['settings']) ?
                            $settings['styleSettingsVars'][$keySettings]['settings'] : [];
                        foreach ($settingsFromFoldersAndFiles['settings'] as $key => $value) {
                            $settings['styleSettingsVars'][$keySettings]['settings'][] = $value;
                        }
                    }
                }
            }

            if (isset($settings['settings']) && is_array($settings['settings']) && !empty($settings['settings'])) {
                foreach ($settings['settings'] as $keySettings => $setting) {
                    // Process recursive settings if they exist
                    if (isset($setting['settings']) && is_array($setting['settings']) && !empty($setting['settings'])) {
                        foreach ($setting['settings'] as $subKey => $subSetting) {
                            // Process mergeFieldSettingsFromFiles for each nested setting first
                            if (isset($subSetting['mergeFieldSettingsFromFiles']) &&
                                is_array($subSetting['mergeFieldSettingsFromFiles']) &&
                                !empty($subSetting['mergeFieldSettingsFromFiles'])) {
                                $this->processMergeFieldSettingsFromFiles($subSetting);
                                $settings['settings'][$keySettings]['settings'][$subKey] = $subSetting;
                            }

                            $settingsFromSubFiles = $this->readStyleSettingsFromFilesAndFolders($subSetting);
                            if (!empty($settingsFromSubFiles['settings'])) {
                                if (!isset($settings['settings'][$keySettings]['settings'][$subKey]['settings'])) {
                                    $settings['settings'][$keySettings]['settings'][$subKey]['settings'] = [];
                                }
                                foreach ($settingsFromSubFiles['settings'] as $key => $value) {
                                    $settings['settings'][$keySettings]['settings'][$subKey]['settings'][] = $value;
                                }
                            }
                        }
                    }

                    // Process mergeFieldSettingsFromFiles for the current setting
                    if (isset($setting['mergeFieldSettingsFromFiles']) &&
                        is_array($setting['mergeFieldSettingsFromFiles']) &&
                        !empty($setting['mergeFieldSettingsFromFiles'])) {
                        $this->processMergeFieldSettingsFromFiles($setting);
                        $settings['settings'][$keySettings] = $setting;
                    }

                    // Process this level's settings from files
                    $settingsFromFoldersAndFiles = $this->readStyleSettingsFromFilesAndFolders($setting);
                    if (!empty($settingsFromFoldersAndFiles['settings'])) {
                        if (!isset($settings['settings'][$keySettings]['settings'])) {
                            $settings['settings'][$keySettings]['settings'] = [];
                        }
                        foreach ($settingsFromFoldersAndFiles['settings'] as $key => $value) {
                            $settings['settings'][$keySettings]['settings'][] = $value;
                        }
                    }
                }
            }
        }
        return $settings;
    }

    private function makeInheritSelectors($settings, $parentSelectors = [])
    {
        // Search for 'inherit' key
        $mustInherit = $this->findElementsWithInheritSelectors($settings, 'inherit', true);

        if ($mustInherit) {
            dd($mustInherit);
        }

        return $settings;
    }

    function findElementsWithInheritSelectors($array, $keyToSearch, $valueToSearch) {
        $results = [];

        foreach ($array as $item) {
            if (is_array($item)) {
                // Check if the current item has the specified key with the desired value
                if (isset($item[$keyToSearch]) && $item[$keyToSearch] === $valueToSearch) {
                    $results[] = $item;
                }

                // Recursively search nested arrays
                $nestedResults = $this->findElementsWithInheritSelectors($item, $keyToSearch, $valueToSearch);
                $results = array_merge($results, $nestedResults);
            }
        }

        return $results;
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

        // Process readSettingsFromFiles
        if (isset($setting['readSettingsFromFiles']) &&
            is_array($setting['readSettingsFromFiles']) &&
            !empty($setting['readSettingsFromFiles'])) {

            $jsonFilesOnTemplate = $setting['readSettingsFromFiles'];

            foreach ($jsonFilesOnTemplate as $jsonFile) {
                $templateColorsFilePath = $this->templateDir . DS . $jsonFile;
                $templateColorsFilePath = $this->normalizePath($templateColorsFilePath, false);

                $settingsFromFile = @file_get_contents($templateColorsFilePath);
                $settingsFromFile = @json_decode($settingsFromFile, true);

                if (is_array($settingsFromFile)) {
                    if (isset($settingsFromFile['settings'])) {
                        // Before merging settings, process mergeFieldSettingsFromFiles for each setting
                        foreach ($settingsFromFile['settings'] as $key => $fileSetting) {
                            if (isset($fileSetting['mergeFieldSettingsFromFiles']) &&
                                is_array($fileSetting['mergeFieldSettingsFromFiles']) &&
                                !empty($fileSetting['mergeFieldSettingsFromFiles'])) {
                                $this->processMergeFieldSettingsFromFiles($fileSetting);
                                $settingsFromFile['settings'][$key] = $fileSetting;
                            }
                        }

                        $newSettings = array_merge($newSettings, $settingsFromFile['settings']);
                    }
                }
            }
        }

        // Process readSettingsFromFolders
        if (isset($setting['readSettingsFromFolders']) &&
            is_array($setting['readSettingsFromFolders']) &&
            !empty($setting['readSettingsFromFolders'])) {

            $folders = $setting['readSettingsFromFolders'];

            foreach ($folders as $folder) {
                $templateColorsFolderExists = $this->templateDir . DS . $folder;
                $templateColorsFolderExists = $this->normalizePath($templateColorsFolderExists);

                $settingsFromFolder = $this->getStyleSettingsFromFolder($templateColorsFolderExists);

                if (is_array($settingsFromFolder) && isset($settingsFromFolder['settings'])) {
                    // Process mergeFieldSettingsFromFiles for each setting from folder
                    foreach ($settingsFromFolder['settings'] as $key => $folderSetting) {
                        if (isset($folderSetting['mergeFieldSettingsFromFiles']) &&
                            is_array($folderSetting['mergeFieldSettingsFromFiles']) &&
                            !empty($folderSetting['mergeFieldSettingsFromFiles'])) {
                            $this->processMergeFieldSettingsFromFiles($folderSetting);
                            $settingsFromFolder['settings'][$key] = $folderSetting;
                        }
                    }

                    $newSettings = array_merge($newSettings, $settingsFromFolder['settings']);
                }
            }
        }

        // Process nested settings recursively
        if (isset($setting['settings']) && is_array($setting['settings'])) {
            foreach ($setting['settings'] as $key => $subSetting) {
                // Process mergeFieldSettingsFromFiles for each nested setting
                if (isset($subSetting['mergeFieldSettingsFromFiles']) &&
                    is_array($subSetting['mergeFieldSettingsFromFiles']) &&
                    !empty($subSetting['mergeFieldSettingsFromFiles'])) {
                    $this->processMergeFieldSettingsFromFiles($subSetting);
                    // Update the original setting
                    $setting['settings'][$key] = $subSetting;
                }

                // Process nested settings recursively
                $recursiveSettings = $this->readStyleSettingsFromFilesAndFolders($subSetting);
                if (!empty($recursiveSettings['settings'])) {
                    $newSettings = array_merge($newSettings, $recursiveSettings['settings']);
                }
            }
        }

        return ['settings' => $newSettings];
    }

    /**
     * Process mergeFieldSettingsFromFiles for a setting
     *
     * @param array &$setting The setting to process (passed by reference)
     */
    private function processMergeFieldSettingsFromFiles(&$setting)
    {
        if (!isset($setting['mergeFieldSettingsFromFiles']) ||
            !is_array($setting['mergeFieldSettingsFromFiles']) ||
            empty($setting['mergeFieldSettingsFromFiles'])) {
            return;
        }

        $jsonFilesForFieldSettings = $setting['mergeFieldSettingsFromFiles'];

        foreach ($jsonFilesForFieldSettings as $jsonFile) {
            $filePath = $this->templateDir . DS . $jsonFile;
            $filePath = $this->normalizePath($filePath, false);

            if (!file_exists($filePath)) {
                // Log file not found for debugging
                // error_log("File not found: " . $filePath);
                continue;
            }

            $settingsFromFile = @file_get_contents($filePath);
            if ($settingsFromFile === false) {
                // Log file read error for debugging
                // error_log("Failed to read file: " . $filePath);
                continue;
            }

            $settingsFromFile = @json_decode($settingsFromFile, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Log JSON parse error for debugging
                // error_log("JSON parse error for file " . $filePath . ": " . json_last_error_msg());
                continue;
            }

            if (!is_array($settingsFromFile) || !isset($settingsFromFile['settings'])) {
                continue;
            }

            foreach ($settingsFromFile['settings'] as $fileSetting) {
                // If we find fieldSettings in the loaded file, merge them into the current setting
                if (isset($fileSetting['fieldSettings'])) {
                    if (!isset($setting['fieldSettings'])) {
                        $setting['fieldSettings'] = [];
                    }

                    // Merge fieldSettings properties
                    foreach ($fileSetting['fieldSettings'] as $key => $value) {
                        if ($key === 'styleProperties' && is_array($value) && !empty($value)) {
                            // Special handling for styleProperties
                            if (!isset($setting['fieldSettings']['styleProperties'])) {
                                $setting['fieldSettings']['styleProperties'] = [];
                            }

                            // Append each styleProperty from the file to the current setting
                            foreach ($value as $styleProperty) {
                                if (!$this->stylePropertyExists($setting['fieldSettings']['styleProperties'], $styleProperty)) {
                                    $setting['fieldSettings']['styleProperties'][] = $styleProperty;
                                }
                            }
                        } elseif (isset($setting['fieldSettings'][$key]) && is_array($setting['fieldSettings'][$key]) && is_array($value)) {
                            // For other arrays, do a simple merge
                            $setting['fieldSettings'][$key] = array_merge($setting['fieldSettings'][$key], $value);
                        } else {
                            // For non-array values or keys that don't exist in current setting
                            $setting['fieldSettings'][$key] = $value;
                        }
                    }
                }
            }
        }

        // For debugging: Log the merged fieldSettings
        // file_put_contents('H:\debug\microweber\debug-merged-settings.json', json_encode($setting, JSON_PRETTY_PRINT));
    }

    /**
     * Check if a style property already exists in the styleProperties array based on label
     *
     * @param array $styleProperties Array of existing style properties
     * @param array $newProperty New style property to check
     * @return bool True if property already exists, false otherwise
     */
    private function stylePropertyExists($styleProperties, $newProperty)
    {
        if (!is_array($styleProperties)) {
            return false;
        }

        foreach ($styleProperties as $property) {
            if (isset($property['label']) && isset($newProperty['label']) && $property['label'] === $newProperty['label']) {
                return true;
            }
        }

        return false;
    }

    private function getStyleSettingsFromFolder($folderPath)
    {
        $settings = [];

        if (is_dir($folderPath)) {
            $templateColorsFolderExistsContent = glob($folderPath . DS . '*.json');

            if (is_array($templateColorsFolderExistsContent)) {
                foreach ($templateColorsFolderExistsContent as $templateColorsFolderExistsContentItem) {
                    if (is_file($templateColorsFolderExistsContentItem)) {
                        $settingsFromFile = @file_get_contents($templateColorsFolderExistsContentItem);
                        $settingsFromFile = @json_decode($settingsFromFile, true);
                        if (is_array($settingsFromFile)) {
                            if(isset($settingsFromFile['settings'])){
                                $settings = array_merge($settings, $settingsFromFile['settings']);
                            } else {
                                $settingsFromFileMergeSettings = [];
                                $settingsFromFileMergeSettings['settings'] = $settingsFromFile;
                                $settings = array_merge($settings, $settingsFromFileMergeSettings['settings']);
                            }

                        }
                    }
                }
            }
        }

        return ['settings' => $settings];
    }

    private function getActiveTemplateDir()
    {
        return app()->template_manager->dir();
    }

    private function normalizePath($path, $isDirectory = true)
    {
        return normalize_path($path, $isDirectory);
    }
}
