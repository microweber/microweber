<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/26/2021
 * Time: 2:12 PM
 */

namespace MicroweberPackages\CustomField\Fields;

class Price extends DefaultField
{
    public $hasResponsiveOptions = false;
    public $hasErrorTextOptions = false;
    public $hasRequiredOptions = false;
    public $hasShowLabelOptions = false;

    public $defaultSettings = [
        'required'=>false,
        'make_select'=>false,

    ];

    public $defaultDataOptions = [
        'old_price'=>false
    ];


    public function render()
    {
        $outputHtml = parent::render();

        // This will append special offers module to price custom fields in admin
        if ($this->adminView) {
            $outputHtml = mw()->parser->process($outputHtml, $options = false);
        }

        return $outputHtml;
    }
}
