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
                if (!isset($setting['title'])) {
                    $setting['title'] = 'Main';
                }
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

    public function appendToFirstLevelSettings($setting,$parentSettings = [])
    {
        if (isset($setting['settings'])) {
            $appendSetting = $setting;
            if($parentSettings){
                $appendSetting['parent'] = $parentSettings;
            }

            $this->firtsLevelSettings[] = $appendSetting;

            if (isset($setting['settings'])) {
                foreach ($setting['settings'] as $insideSetting) {
                    $this->appendToFirstLevelSettings($insideSetting , $setting);
                }
            }
        }
    }
}
