<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/26/2021
 * Time: 11:29 AM
 */

namespace Modules\CustomFields\Fields;


use Illuminate\Support\Facades\Blade;
use MicroweberPackages\View\View;
use Modules\CustomFields\Fields\Traits\TemplateLoader;

class DefaultField
{
    use TemplateLoader;

    public $hasShowLabelOptions = false;
    public $hasResponsiveOptions = false;
    public $hasErrorTextOptions = false;
    public $hasRequiredOptions = false;

    public $data;
    public $defaultData = [
        'id' => '',
        'help' => '',
        'error_text' => '',
        'name' => 'Textfield',
        'value' => '',
        'placeholder' => '',
    ];

    public $settings;
    public $defaultSettings = [
        'required' => false,
        'multiple' => '',
        'hide_label' => false,
        'show_placeholder' => false,
        'field_size' => 12,
        'field_size_desktop' => 12,
        'field_size_tablet' => 12,
        'field_size_mobile' => 12,
    ];

    public $defaultSettingsAll = [
        'required' => false,
        'multiple' => '',
        'hide_label' => false,
        'show_placeholder' => false,
        'as_text_area' => false,
        'field_size' => 12,
        'field_size_desktop' => 12,
        'field_size_tablet' => 12,
        'field_size_mobile' => 12,
    ];

    public $defaultDataOptions = [

    ];

    public $adminView = false;

    public $renderData = false;
    public $renderSettings = false;

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setAdminView($adminView)
    {
        $this->adminView = $adminView;
    }

    public function mergeRenderSettings($settings)
    {
        if (is_array($settings)) {
            $this->renderSettings = array_merge($this->renderSettings, $settings);
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

        if (isset($this->data['hide_label']) and !empty($this->data['hide_label'])) {
            $renderSettings['hide_label']  = $this->data['hide_label'];
            $renderSettings['show_label'] = false;
        }

        if (isset($this->data['required'])) {
            $renderSettings['required'] = $this->data['required'];
        }

        // Set default settings if not exists
        foreach ($this->defaultSettings as $defaultSettingsKey => $defaultSettingsValue) {
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

        if(!isset($renderData['value'])){
            $renderData['value'] = '';
        }

        if(isset($renderData['values']) and is_array($renderData['values']) && count($renderData['values']) > 0){
            $renderData['value'] = end($renderData['values']);

        } elseif (isset($renderData['values']) and !is_array($renderData['values'])){
            $renderData['value'] = $renderData['values'];
        }


            // Set default data if not exists
        foreach ($this->defaultData as $defaultDataKey => $defaultDataValue) {
            if (!isset($renderData[$defaultDataKey])) {
                $renderData[$defaultDataKey] = $defaultDataValue;
            }
        }
        // Set default data options if not exists
        foreach ($this->defaultDataOptions as $defaultDataOptionKey => $defaultDataOptionValue) {
            if (!isset($renderData['options'][$defaultDataOptionKey])) {
                $renderData['options'][$defaultDataOptionKey] = $defaultDataOptionValue;
            }
        }

        // Handle show_placeholder setting properly for both boolean and string values
        $showPlaceholder = false;

        // Check if there's a specific show_placeholder setting from the database options
        if (isset($this->data['options']['show_placeholder'])) {
            $placeholderSetting = $this->data['options']['show_placeholder'];
            if ($placeholderSetting === true || $placeholderSetting === 1 || $placeholderSetting === '1' || $placeholderSetting === 'true') {
                $showPlaceholder = true;
            }
        } elseif (isset($renderSettings['show_placeholder'])) {
            $placeholderSetting = $renderSettings['show_placeholder'];
            if ($placeholderSetting === true || $placeholderSetting === 1 || $placeholderSetting === '1' || $placeholderSetting === 'true') {
                $showPlaceholder = true;
            }
        }

        // Handle placeholder text priority: options.placeholder > data.placeholder > field name
        if (!$showPlaceholder) {
            $renderData['placeholder'] = '';
        } else {
            // First check for placeholder in options
            if (isset($this->data['options']['placeholder']) && !empty($this->data['options']['placeholder'])) {
                $renderData['placeholder'] = $this->data['options']['placeholder'];
            } // Then check for placeholder in data
            elseif (isset($this->data['placeholder']) && !empty($this->data['placeholder'])) {
                $renderData['placeholder'] = $this->data['placeholder'];
            } // Default to field name if no specific placeholder is set
            else {
                $renderData['placeholder'] = $this->data['name'] ?? '';
            }
        }

        $this->renderData = $renderData;
    }

    public function render()
    {
        $this->preparePreview();

        $file = $this->getTempalteFile();


        if (!$file) {

            return '';
        }
        if (!is_file($file)) {

            return '';
        }

        $bladeString = file_get_contents($file);

        // dump($this->getTempalteFile());
//$bladeView = new Blade();

        //  new View($this->getTempalteFile());
        /*$parseView = new View($this->getTempalteFile());




        $parseView->assign('data', $this->renderData);
        $parseView->assign('settings', $this->renderSettings);

        $customFieldHtml = $parseView->__toString();

        return $customFieldHtml;*/
        foreach ($this->defaultSettingsAll as $defaultSettingsKey => $defaultSettingsValue) {
            if (!isset($this->renderSettings[$defaultSettingsKey])) {
                $this->renderSettings[$defaultSettingsKey] = $defaultSettingsValue;
            }
        }
        $data = [];
        $data['data'] = $this->renderData;
        $data['settings'] = $this->renderSettings;

        return Blade::render($bladeString, $data);
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
