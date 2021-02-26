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

        return $file;

        $parseView = new View($file);
        $parseView->assign('data', $data);
        $parseView->assign('settings', $data);

        $customFieldHtml = $parseView->__toString();

        return $customFieldHtml;
    }
}