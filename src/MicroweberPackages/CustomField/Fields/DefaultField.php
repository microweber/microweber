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
        'id'=> '',
        'help'=> '',
        'error_text'=> '',
        'name'=> 'Textfield',
        'value'=> '',
        'placeholder'=> '',
    ];
    public $settings;
    public $defaultSettings = [
        'as_text_area'=>'',
        'required'=>true,
        'multiple'=>'',
        'show_label'=>true,
        'field_size'=>12,
        'field_size_desktop'=>12,
        'field_size_tablet'=>12,
        'field_size_mobile'=>12,
    ];
    public $adminView = false;

    public $renderData = false;
    public $renderSettings = false;

    public function setData($data){
        $this->data = $data;
    }

    public function setAdminView($adminView){
        $this->adminView = $adminView;
    }

    public function preparePreview()
    {
        $renderData = [];
        if (!empty($this->data)) {
            $renderData = array_merge($renderData, $this->data);
        }
        // Set default data if not exists
        foreach($this->defaultData as $defaultDataKey=>$defaultDataValue) {
            if (!isset($renderData[$defaultDataKey])) {
                $renderData[$defaultDataKey] = $defaultDataValue;
            }
        }
        $this->renderData = $renderData;

        $renderSettings = [];
        if (!empty($this->settings)) {
            $renderSettings = array_merge($renderSettings, $this->settings);
        }
        if (!empty($this->data['options'])) {
            $renderSettings = array_merge($renderSettings, $this->data['options']);
        }

        if (isset($this->data['show_label'])) {
            $renderSettings['show_label'] = $this->data['show_label'];
        }

        // Set default settings if not exists
        foreach($this->defaultSettings as $defaultSettingsKey=>$defaultSettingsValue) {
            if (!isset($renderSettings[$defaultSettingsKey])) {
                $renderSettings[$defaultSettingsKey] = $defaultSettingsValue;
            }
        }

        $this->renderSettings = $renderSettings;
    }

    public function render()
    {
       $templateFiles = $this->getTemplateFiles($this->data);

        $file = $templateFiles['preview_file'];
        if ($this->adminView) {
            $file = $templateFiles['settings_file'];
        }

        if (!is_file($file)) {
            return $file;
        }

        $this->preparePreview();

        $parseView = new View($file);
        $parseView->assign('data', $this->renderData);
        $parseView->assign('settings', $this->renderSettings);

        $customFieldHtml = $parseView->__toString();

        return $customFieldHtml;
    }
}