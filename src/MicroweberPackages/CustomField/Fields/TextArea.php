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

    public function preparePreview()
    {
        parent::preparePreview();

        $this->renderSettings['required'] = false;
        $this->renderSettings['as_text_area'] = true;

        if (isset($this->data['required'])) {
            $this->renderSettings['required'] = $this->data['required'];
        }
    }
}
