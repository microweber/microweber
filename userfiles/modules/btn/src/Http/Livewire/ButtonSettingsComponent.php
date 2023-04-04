<?php

namespace MicroweberPackages\Modules\Btn\Http\Livewire;

use MicroweberPackages\LiveEdit\Http\Livewire\ModuleSettingsComponent;

class ButtonSettingsComponent extends ModuleSettingsComponent
{

    public array $settings = [
        'button_style' => '',
        'button_size' => '',
        'button_action' => '',
        'align' => '',
        'url' => '',
        'url_to_content_id' => '',
        'url_to_category_id' => '',
        'popupcontent' => '',
        'text' => '',
        'url_blank' => '',
        'icon' => '',
        'backgroundColor' => '',
        'color' => '',
        'borderColor' => '',
        'borderWidth' => '',
        'borderRadius' => '',
        'padding' => '',
        'margin' => '',
        'fontSize' => '',
        'shadow' => '',
        'customSize' => '',
        'hoverbackgroundColor' => '',
        'hovercolor' => '',
        'hoverborderColor' => '',
    ];

    public function render()
    {


        //$options = get_module_options($this->moduleId);

        //  if($options){
        foreach ($this->settings as $key => $option) {
            $val = get_module_option($key, $this->moduleId);
            $this->settings[$key] = $val;
        }
        //  }

        $style = get_module_option('button_style', $this->moduleId);
        $size = get_module_option('button_size', $this->moduleId);
        $action = get_module_option('button_action', $this->moduleId);
        $align = get_module_option('align', $this->moduleId);

        $onclick = false;
        if (isset($params['button_onclick'])) {
            $onclick = $params['button_onclick'];
        }


        $url = $url_display = get_module_option('url', $this->moduleId);
        $url_to_content_id = get_module_option('url_to_content_id', $this->moduleId);
        $url_to_category_id = get_module_option('url_to_category_id', $this->moduleId);

        $popupcontent = get_module_option('popupcontent', $this->moduleId);
        $text = get_module_option('text', $this->moduleId);
        $url_blank = get_module_option('url_blank', $this->moduleId);
        $icon = get_module_option('icon', $this->moduleId);

        $backgroundColor = get_module_option('backgroundColor', $this->moduleId);
        $color = get_module_option('color', $this->moduleId);
        $borderColor = get_module_option('borderColor', $this->moduleId);
        $borderWidth = get_module_option('borderWidth', $this->moduleId);
        $borderRadius = get_module_option('borderRadius', $this->moduleId);
        $shadow = get_module_option('shadow', $this->moduleId);
        $customSize = get_module_option('customSize', $this->moduleId);


        $hoverbackgroundColor = get_module_option('hoverbackgroundColor', $this->moduleId);
        $hovercolor = get_module_option('hovercolor', $this->moduleId);
        $hoverborderColor = get_module_option('hoverborderColor', $this->moduleId);


        $link_to_content_by_id = 'content:';
        $link_to_category_by_id = 'category:';


        if (substr($url, 0, strlen($link_to_content_by_id)) === $link_to_content_by_id) {
            $link_to_content_by_id = substr($url, strlen($link_to_content_by_id));
            if ($link_to_content_by_id) {
                $url_display = content_link($link_to_content_by_id);
            }
        } else if (substr($url, 0, strlen($link_to_category_by_id)) === $link_to_category_by_id) {
            $link_to_category_by_id = substr($url, strlen($link_to_category_by_id));

            if ($link_to_category_by_id) {
                $url_display = category_link($link_to_category_by_id);
            }
        }


        return view('modules.btn::livewire.index');
    }


    public function setAlign($align)
    {
        $this->settings['align'] = $align;
        $this->updatedSettings($this->settings);

    }
}
