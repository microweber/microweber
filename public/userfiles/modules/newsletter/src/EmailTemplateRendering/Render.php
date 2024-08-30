<?php
namespace MicroweberPackages\Modules\Newsletter\EmailTemplateRendering;
class Render
{
    public $strucutre = [];

    public function html($strucutre = [])
    {
        $this->strucutre = $strucutre;

        $html = '';

        if (isset($this->strucutre['root'])) {
            $elementType = app()->make('MicroweberPackages\\Modules\\Newsletter\\EmailTemplateRendering\Elements\\'.$this->strucutre['root']['type']);
            $elementType->setStructure($this->strucutre);
            $html .= $elementType->render($this->strucutre['root']);
        }

        return $html;
    }
}
