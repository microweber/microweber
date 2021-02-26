<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/26/2021
 * Time: 11:29 AM
 */

namespace MicroweberPackages\CustomField\Fields;


use MicroweberPackages\CustomField\Fields\Traits\TemplateLoader;
use MicroweberPackages\View\View;

class DefaultField
{
    use TemplateLoader;

    public $data;
    public $defaultData = [
        'help'=> ''
    ];
    public $settings;
    public $defaultSettings = [

    ];

    public function setData($data){
        $this->data = $data;
    }

    public function render()
    {

       $templateFiles = $this->getTemplateFiles($this->data);

        $settings = false;
        if ($settings || isset($data['settings'])) {
            $file = $templateFiles['settings_file'];
        } else {
            $file = $templateFiles['preview_file'];
        }

        if (!is_file($file)) {
            return false;
        }

        $renderData = $this->data;
        $renderSettings = $this->settings;

        // Set default data if not exists
        if (!empty($renderData) && is_array($renderData)) {
            foreach($this->defaultData as $defaultDataKey=>$defaultDataValue) {
                if (!isset($renderData[$defaultDataKey])) {
                    $renderData[$defaultDataKey] = $defaultDataValue;
                }
            }
        }

        // Set default settings if not exists
        if (!empty($renderSettings) && is_array($renderSettings)) {
            foreach($this->defaultSettings as $defaultSettingsKey=>$defaultSettingsValue) {
                if (!isset($renderSettings[$defaultSettingsKey])) {
                    $renderSettings[$defaultSettingsKey] = $defaultSettingsValue;
                }
            }
        }

        $parseView = new View($file);
        $parseView->assign('data', $renderData);
        $parseView->assign('settings', $renderSettings);

        $customFieldHtml = $parseView->__toString();

        return $customFieldHtml;
    }
}