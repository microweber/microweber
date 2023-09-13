<?php

namespace MicroweberPackages\Module;

class ModuleDefaultSettingsApplier
{
    public $moduleId = null;
    public $moduleName = null;
    public $modulePath = null;

    public function apply()
    {
        $isDefaultSettingsApplied = get_option('default_settings_is_applied', $this->moduleId);
        if (!$isDefaultSettingsApplied) {
            $defaultSettingsFile = $this->modulePath . '/default_settings.json';

            if (is_file($defaultSettingsFile)) {
                $checkForDefaultSettings = file_get_contents($defaultSettingsFile);
                if ($checkForDefaultSettings) {
                    $defaultSettings = json_decode($checkForDefaultSettings, true);
                    if (!empty($defaultSettings) && is_array($defaultSettings)) {

                        foreach ($defaultSettings as $defaultSettingOptionKey => $defaultSettingOptionValue) {
                            if (is_array($defaultSettingOptionValue)) {
                                $defaultSettingOptionValue = json_encode($defaultSettingOptionValue);
                            }
                            save_option([
                                'option_value' => $defaultSettingOptionValue,
                                'option_key' => $defaultSettingOptionKey,
                                'option_group' => $this->moduleId,
                                'module'=> $this->moduleName
                            ]);
                        }

                        save_option('default_settings_is_applied', true, $this->moduleId);

                        return [
                            'success' => true,
                            'message' => 'Default settings applied'
                        ];
                    }
                }
            }
        }

        return [
            'success' => false,
            'message' => 'Allready applied'
        ];
    }
}
