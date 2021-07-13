<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/26/2021
 * Time: 11:29 AM
 */

namespace MicroweberPackages\CustomField\Fields;

class TextArea extends DefaultField
{
    public $hasResponsiveOptions = true;
    public $hasErrorTextOptions = true;
    public $hasRequiredOptions = true;
    public $hasShowLabelOptions = true;

    public $defaultSettings = [
        'required'=>false,
        'rows'=> 3,
        'multiple'=>'',
        'show_label'=>true,
        'show_placeholder'=>false,
        'field_size'=>12,
        'field_size_desktop'=>12,
        'field_size_tablet'=>12,
        'field_size_mobile'=>12,
        'rows'=>3,
        'cols'=>2,
    ];

    public function preparePreview()
    {
        parent::preparePreview();

        $this->renderSettings['required'] = false;
        $this->renderSettings['as_text_area'] = true;

        if (isset($this->data['required'])) {
            $this->renderSettings['required'] = $this->data['required'];
        }

        if (isset($this->data['options']['rows'])) {
            $this->renderSettings['rows'] = $this->data['options']['rows'];
        }

        if (isset($this->data['options']['cols'])) {
            $this->renderSettings['cols'] = $this->data['options']['cols'];
        }
    }
}
