<?php

namespace MicroweberPackages\Template\Http\Livewire\Admin;

class StyleSettingsFirstLevelConvertor
{
    public $firtsLevelSettings = [];

    public function getFirstLevelSettings($settings) {

        $fixedSettingsUrls = $this->fixedUrl($settings);

        $this->firtsLevelSettings = [];
        foreach ($fixedSettingsUrls as $setting) {

            $appendSetting = $setting;
            $appendSetting['main'] = true;
            if (isset($appendSetting['settings'])) {
                unset($appendSetting['settings']);
            }
            $this->firtsLevelSettings[] = $appendSetting;

            $this->appendToFirstLevelSettings($setting);
        }

        return $this->firtsLevelSettings;
    }

    public function fixedUrl($settings, $parentUrl = '/')
    {
        if (is_array($settings) && !empty($settings)) {
            foreach ($settings as &$setting) {
                $setting['url'] = $parentUrl . str_slug($setting['title']);
                $setting['backUrl'] = substr($parentUrl, 0, -1);
                if (empty($setting['backUrl'])) {
                    $setting['backUrl'] = '/';
                }

                if (isset($setting['settings'])) {
                    $setting['settings'] = $this->fixedUrl($setting['settings'], $setting['url'] . '/');
                }
            }
        }
        return $settings;
    }

    public function appendToFirstLevelSettings($setting)
    {
        if (isset($setting['settings'])) {
            $appendSetting = $setting;

            $this->firtsLevelSettings[] = $appendSetting;

            if (isset($setting['settings'])) {
                foreach ($setting['settings'] as $insideSetting) {
                    $this->appendToFirstLevelSettings($insideSetting);
                }
            }
        }
    }
}
