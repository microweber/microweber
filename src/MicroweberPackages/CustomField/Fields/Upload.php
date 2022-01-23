<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/26/2021
 * Time: 11:43 AM
 */

namespace MicroweberPackages\CustomField\Fields;


class Upload extends DefaultField
{
    public $hasResponsiveOptions = true;
    public $hasErrorTextOptions = true;
    public $hasRequiredOptions = true;
    public $hasShowLabelOptions = true;

    public function preparePreview()
    {
        parent::preparePreview();

        $this->renderSettings['rel_id'] = $this->data['rel_id'];
        $this->renderSettings['rel_type'] = $this->data['rel_type'];
        $this->renderSettings['required'] = false;
        $this->renderSettings['options']['file_types'] = [];

        if (isset($this->data['options']['file_types'])) {
            $this->renderSettings['options']['file_types'] = $this->data['options']['file_types'];
        }

        if (isset($this->data['required'])) {
            $this->renderSettings['required'] = $this->data['required'];
        }
    }
}
