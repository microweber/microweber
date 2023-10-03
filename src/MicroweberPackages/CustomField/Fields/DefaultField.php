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

    public $hasShowLabelOptions = false;
    public $hasResponsiveOptions = false;
    public $hasErrorTextOptions = false;
    public $hasRequiredOptions = false;

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
        'required'=>false,
        'multiple'=>'',
        'show_label'=>true,
        'show_placeholder'=>false,
        'field_size'=>12,
        'field_size_desktop'=>12,
        'field_size_tablet'=>12,
        'field_size_mobile'=>12,
    ];

    public $defaultSettingsAll = [
        'required'=>false,
        'multiple'=>'',
        'show_label'=>true,
        'show_placeholder'=>false,
        'as_text_area'=>false,
        'field_size'=>12,
        'field_size_desktop'=>12,
        'field_size_tablet'=>12,
        'field_size_mobile'=>12,
    ];

    public $defaultDataOptions = [

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

    public function mergeRenderSettings($settings){
        if(is_array($settings)){
            $this->renderSettings = array_merge($this->renderSettings,$settings);
        }
    }

    public function preparePreview()
    {
        // Render settings
        $renderSettings = [];
        if (!empty($this->settings)) {
            $renderSettings = array_merge($renderSettings, $this->settings);
        }
        if (!empty($this->data['options']) && is_array($this->data['options'])) {
            $renderSettings = array_merge($renderSettings, $this->data['options']);
        }

        if (isset($this->data['show_label'])) {
            $renderSettings['show_label'] = $this->data['show_label'];
        }

        if (isset($this->data['required'])) {
            $renderSettings['required'] = $this->data['required'];
        }

        // Set default settings if not exists
        foreach($this->defaultSettings as $defaultSettingsKey=>$defaultSettingsValue) {
            if (!isset($renderSettings[$defaultSettingsKey])) {
                $renderSettings[$defaultSettingsKey] = $defaultSettingsValue;
            }
        }

        // $renderSettings = $this->calculateFieldSize($renderSettings);

        $this->renderSettings = $renderSettings;

        // Render data
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
        // Set default data options if not exists
        foreach($this->defaultDataOptions as $defaultDataOptionKey=>$defaultDataOptionValue) {
            if (!isset($renderData['options'][$defaultDataOptionKey])) {
                $renderData['options'][$defaultDataOptionKey] = $defaultDataOptionValue;
            }
        }

        if (!isset($renderSettings['show_placeholder'])
            || $renderSettings['show_placeholder'] == false
            || (strpos($renderSettings['show_placeholder'], 'false') !== false)
            ){
            $renderData['placeholder'] = '';
        } else {
        }

        $this->renderData = $renderData;
    }

    public function render()
    {
        $this->preparePreview();

        $parseView = new View($this->getTempalteFile());

        foreach ($this->defaultSettingsAll as $defaultSettingsKey => $defaultSettingsValue) {
            if (!isset($this->renderSettings[$defaultSettingsKey])) {
                $this->renderSettings[$defaultSettingsKey] = $defaultSettingsValue;
            }
        }


        $parseView->assign('data', $this->renderData);
        $parseView->assign('settings', $this->renderSettings);

        $customFieldHtml = $parseView->__toString();

        return $customFieldHtml;
    }

    public function getTempalteFile()
    {
        $template = $this->getTemplateFiles($this->data);

        if ($this->adminView) {
            $file = $template['settings_file'];
        } else {
            $file = $template['preview_file'];
        }

        if (!is_file($file)) {
            return '';
        }

        return $file;
    }

}
