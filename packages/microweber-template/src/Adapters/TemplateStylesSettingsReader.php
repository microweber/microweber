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

    /**
     * Process mergeFieldSettingsStylePropertiesFromFiles for a style property
     *
     * @param array &$styleProperty The style property to process (passed by reference)
     * @return void
     */
    private function processMergeFieldSettingsStylePropertiesFromFiles(&$styleProperty)
    {
        if (!isset($styleProperty['mergeFieldSettingsStylePropertiesFromFiles']) ||
            !is_array($styleProperty['mergeFieldSettingsStylePropertiesFromFiles']) ||
            empty($styleProperty['mergeFieldSettingsStylePropertiesFromFiles'])) {
            return;
        }

        $filesToMerge = $styleProperty['mergeFieldSettingsStylePropertiesFromFiles'];

        foreach ($filesToMerge as $jsonFile) {
            $filePath = $this->templateDir . DS . $jsonFile;
            $filePath = $this->normalizePath($filePath, false);

            if (!file_exists($filePath)) {
                continue;
            }

            $settingsFromFile = @file_get_contents($filePath);
            if ($settingsFromFile === false) {
                continue;
            }

            $settingsFromFile = @json_decode($settingsFromFile, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                continue;
            }

            // Look for style properties in the file's structure
            if (isset($settingsFromFile['settings'][0]['fieldSettings']['styleProperties'][0]['properties'])) {
                $propertiesToMerge = $settingsFromFile['settings'][0]['fieldSettings']['styleProperties'][0]['properties'];
                
                // Initialize properties array if it doesn't exist
                if (!isset($styleProperty['properties'])) {
                    $styleProperty['properties'] = [];
                }
                
                // Merge the properties
                $styleProperty['properties'] = array_merge($styleProperty['properties'], $propertiesToMerge);
            }
        }
    }

    /**
     * Process mergeFieldSettingsStylePropertiesFromFolders for a style property
     *
     * @param array &$styleProperty The style property to process (passed by reference)
     * @return void
     */
    private function processMergeFieldSettingsStylePropertiesFromFolders(&$styleProperty)
    {
        if (!isset($styleProperty['mergeFieldSettingsStylePropertiesFromFolders']) ||
            !is_array($styleProperty['mergeFieldSettingsStylePropertiesFromFolders']) ||
            empty($styleProperty['mergeFieldSettingsStylePropertiesFromFolders'])) {
            return;
        }

        $foldersToMerge = $styleProperty['mergeFieldSettingsStylePropertiesFromFolders'];

        foreach ($foldersToMerge as $folderPath) {
            $fullFolderPath = $this->templateDir . DS . $folderPath;
            $fullFolderPath = $this->normalizePath($fullFolderPath);

            if (!is_dir($fullFolderPath)) {
                continue;
            }

            $jsonFiles = glob($fullFolderPath . DS . '*.json');
            if (!is_array($jsonFiles)) {
                continue;
            }

            foreach ($jsonFiles as $jsonFilePath) {
                if (!is_file($jsonFilePath)) {
                    continue;
                }

                $settingsFromFile = @file_get_contents($jsonFilePath);
                if ($settingsFromFile === false) {
                    continue;
                }

                $settingsFromFile = @json_decode($settingsFromFile, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    continue;
                }

                // Look for style properties in the file's structure
                if (isset($settingsFromFile['settings'][0]['fieldSettings']['styleProperties'][0]['properties'])) {
                    $propertiesToMerge = $settingsFromFile['settings'][0]['fieldSettings']['styleProperties'][0]['properties'];
                    
                    // Initialize properties array if it doesn't exist
                    if (!isset($styleProperty['properties'])) {
                        $styleProperty['properties'] = [];
                    }
                    
                    // Merge the properties
                    $styleProperty['properties'] = array_merge($styleProperty['properties'], $propertiesToMerge);
                }
            }
        }
    }

    private function processStyleProperties(&$settings)
    {
        // Process mergeFieldSettingsStylePropertiesFromFolders at the fieldSettings level
        if (isset($settings['fieldSettings']['mergeFieldSettingsStylePropertiesFromFolders']) &&
            is_array($settings['fieldSettings']['mergeFieldSettingsStylePropertiesFromFolders']) &&
            !empty($settings['fieldSettings']['mergeFieldSettingsStylePropertiesFromFolders'])) {
            
            $foldersToMerge = $settings['fieldSettings']['mergeFieldSettingsStylePropertiesFromFolders'];
            
            foreach ($foldersToMerge as $folderPath) {
                $fullFolderPath = $this->templateDir . DS . $folderPath;
                $fullFolderPath = $this->normalizePath($fullFolderPath);

                if (!is_dir($fullFolderPath)) {
                    continue;
                }

                $jsonFiles = glob($fullFolderPath . DS . '*.json');
                if (!is_array($jsonFiles)) {
                    continue;
                }

                foreach ($jsonFiles as $jsonFilePath) {
                    if (!is_file($jsonFilePath)) {
                        continue;
                    }

                    $settingsFromFile = @file_get_contents($jsonFilePath);
                    if ($settingsFromFile === false) {
                        continue;
                    }

                    $settingsFromFile = @json_decode($settingsFromFile, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        continue;
                    }

                    // Look for style properties in the file's structure
                    if (isset($settingsFromFile['settings'][0]['fieldSettings']['styleProperties'][0]['properties'])) {
                        $propertiesToMerge = $settingsFromFile['settings'][0]['fieldSettings']['styleProperties'][0]['properties'];
                        
                        // Initialize styleProperties array if it doesn't exist
                        if (!isset($settings['fieldSettings']['styleProperties'])) {
                            $settings['fieldSettings']['styleProperties'] = [];
                        }
                        
                        // Create a new style property entry with the merged properties
                        $filename = basename($jsonFilePath, '.json');
                        $label = ucwords(str_replace('-', ' ', $filename));
                        
                        $newStyleProperty = [
                            'label' => $label,
                            'properties' => $propertiesToMerge
                        ];
                        
                        // Check if style property with same label already exists
                        if (!$this->stylePropertyExists($settings['fieldSettings']['styleProperties'], $newStyleProperty)) {
                            $settings['fieldSettings']['styleProperties'][] = $newStyleProperty;
                        }
                    }
                }
            }
        }

        // Process mergeFieldSettingsStylePropertiesFromFiles at the fieldSettings level
        if (isset($settings['fieldSettings']['mergeFieldSettingsStylePropertiesFromFiles']) &&
            is_array($settings['fieldSettings']['mergeFieldSettingsStylePropertiesFromFiles']) &&
            !empty($settings['fieldSettings']['mergeFieldSettingsStylePropertiesFromFiles'])) {
            
            $filesToMerge = $settings['fieldSettings']['mergeFieldSettingsStylePropertiesFromFiles'];
            
            foreach ($filesToMerge as $jsonFile) {
                $filePath = $this->templateDir . DS . $jsonFile;
                $filePath = $this->normalizePath($filePath, false);

                if (!file_exists($filePath)) {
                    continue;
                }

                $settingsFromFile = @file_get_contents($filePath);
                if ($settingsFromFile === false) {
                    continue;
                }

                $settingsFromFile = @json_decode($settingsFromFile, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    continue;
                }

                // Look for style properties in the file's structure
                if (isset($settingsFromFile['settings'][0]['fieldSettings']['styleProperties'][0]['properties'])) {
                    $propertiesToMerge = $settingsFromFile['settings'][0]['fieldSettings']['styleProperties'][0]['properties'];
                    
                    // Initialize styleProperties array if it doesn't exist
                    if (!isset($settings['fieldSettings']['styleProperties'])) {
                        $settings['fieldSettings']['styleProperties'] = [];
                    }
                    
                    // Create a new style property entry with the merged properties
                    $filename = basename($jsonFile, '.json');
                    $label = ucwords(str_replace('-', ' ', $filename));
                    
                    $newStyleProperty = [
                        'label' => $label,
                        'properties' => $propertiesToMerge
                    ];
                    
                    // Check if style property with same label already exists
                    if (!$this->stylePropertyExists($settings['fieldSettings']['styleProperties'], $newStyleProperty)) {
                        $settings['fieldSettings']['styleProperties'][] = $newStyleProperty;
                    }
                }
            }
        }

        // Process existing styleProperties
        if (isset($settings['fieldSettings']['styleProperties']) && 
            is_array($settings['fieldSettings']['styleProperties'])) {
            
            foreach ($settings['fieldSettings']['styleProperties'] as &$styleProperty) {
                $this->processMergeFieldSettingsStylePropertiesFromFiles($styleProperty);
                $this->processMergeFieldSettingsStylePropertiesFromFolders($styleProperty);
            }
        }

        // Process nested settings recursively
        if (isset($settings['settings']) && is_array($settings['settings'])) {
            foreach ($settings['settings'] as &$setting) {
                $this->processStyleProperties($setting);
            }
        }
    }

    public function getStyleSettings()
    {
        $settings = $this->getStyleSettingsFromFile($this->templateDir . 'style-settings.json');
        
        // Process style properties after loading settings
        $this->processStyleProperties($settings);
        
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

                            // Process mergeFieldSettingsFromFolders for each nested setting
                            if (isset($subSetting['mergeFieldSettingsFromFolders']) &&
                                is_array($subSetting['mergeFieldSettingsFromFolders']) &&
                                !empty($subSetting['mergeFieldSettingsFromFolders'])) {
                                $this->processMergeFieldSettingsFromFolders($subSetting);
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

                            // Process mergeFieldSettingsFromFolders for each nested setting
                            if (isset($subSetting['mergeFieldSettingsFromFolders']) &&
                                is_array($subSetting['mergeFieldSettingsFromFolders']) &&
                                !empty($subSetting['mergeFieldSettingsFromFolders'])) {
                                $this->processMergeFieldSettingsFromFolders($subSetting);
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

                    // Process mergeFieldSettingsFromFolders for the current setting
                    if (isset($setting['mergeFieldSettingsFromFolders']) &&
                        is_array($setting['mergeFieldSettingsFromFolders']) &&
                        !empty($setting['mergeFieldSettingsFromFolders'])) {
                        $this->processMergeFieldSettingsFromFolders($setting);
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

            $jsonFilesOnTemplate = $setting['readSettingsFromFiles'];            foreach ($jsonFilesOnTemplate as $jsonFile) {
                $templateColorsFilePath = $this->templateDir . DS . $jsonFile;
                $templateColorsFilePath = $this->normalizePath($templateColorsFilePath, false);

                // Use getStyleSettingsFromFile to recursively process all directives
                $settingsFromFile = $this->getStyleSettingsFromFile($templateColorsFilePath);

                if (is_array($settingsFromFile) && isset($settingsFromFile['settings'])) {
                    $newSettings = array_merge($newSettings, $settingsFromFile['settings']);
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
                $templateColorsFolderExists = $this->normalizePath($templateColorsFolderExists);                $settingsFromFolder = $this->getStyleSettingsFromFolder($templateColorsFolderExists);

                if (is_array($settingsFromFolder) && isset($settingsFromFolder['settings'])) {
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

                // Process mergeFieldSettingsFromFolders for each nested setting
                if (isset($subSetting['mergeFieldSettingsFromFolders']) &&
                    is_array($subSetting['mergeFieldSettingsFromFolders']) &&
                    !empty($subSetting['mergeFieldSettingsFromFolders'])) {
                    $this->processMergeFieldSettingsFromFolders($subSetting);
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
                // Process style properties for the loaded setting first
                $this->processStyleProperties($fileSetting);

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
     * Process mergeFieldSettingsFromFolders for a setting
     *
     * @param array &$setting The setting to process (passed by reference)
     */
    private function processMergeFieldSettingsFromFolders(&$setting)
    {
        if (!isset($setting['mergeFieldSettingsFromFolders']) ||
            !is_array($setting['mergeFieldSettingsFromFolders']) ||
            empty($setting['mergeFieldSettingsFromFolders'])) {
            return;
        }

        $foldersForFieldSettings = $setting['mergeFieldSettingsFromFolders'];

        foreach ($foldersForFieldSettings as $folderPath) {
            $fullFolderPath = $this->templateDir . DS . $folderPath;
            $fullFolderPath = $this->normalizePath($fullFolderPath);

            if (!is_dir($fullFolderPath)) {
                // Log folder not found for debugging
                // error_log("Folder not found: " . $fullFolderPath);
                continue;
            }

            $jsonFiles = glob($fullFolderPath . DS . '*.json');
            if (!is_array($jsonFiles)) {
                continue;
            }

            foreach ($jsonFiles as $jsonFilePath) {
                if (!is_file($jsonFilePath)) {
                    continue;
                }

                $settingsFromFile = @file_get_contents($jsonFilePath);
                if ($settingsFromFile === false) {
                    // Log file read error for debugging
                    // error_log("Failed to read file: " . $jsonFilePath);
                    continue;
                }

                $settingsFromFile = @json_decode($settingsFromFile, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Log JSON parse error for debugging
                    // error_log("JSON parse error for file " . $jsonFilePath . ": " . json_last_error_msg());
                    continue;
                }

                // Handle direct settings format or nested 'settings' key
                $settingsToProcess = [];
                if (isset($settingsFromFile['settings']) && is_array($settingsFromFile['settings'])) {
                    $settingsToProcess = $settingsFromFile['settings'];
                } else if (is_array($settingsFromFile)) {
                    $settingsToProcess = [$settingsFromFile];
                }

                foreach ($settingsToProcess as $fileSetting) {
                    // Process style properties for the loaded setting first
                    $this->processStyleProperties($fileSetting);

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
        }

        // For debugging: Log the merged fieldSettings
        // file_put_contents('H:\debug\microweber\debug-merged-folder-settings.json', json_encode($setting, JSON_PRETTY_PRINT));
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
            $templateColorsFolderExistsContent = glob($folderPath . DS . '*.json');            if (is_array($templateColorsFolderExistsContent)) {
                foreach ($templateColorsFolderExistsContent as $templateColorsFolderExistsContentItem) {
                    if (is_file($templateColorsFolderExistsContentItem)) {
                        // Use getStyleSettingsFromFile to recursively process all directives
                        $settingsFromFile = $this->getStyleSettingsFromFile($templateColorsFolderExistsContentItem);
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
