<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/26/2021
 * Time: 11:29 AM
 */

namespace MicroweberPackages\CustomField\Fields;


class Country extends DefaultField
{
    public $hasResponsiveOptions = true;
    public $hasErrorTextOptions = true;
    public $hasRequiredOptions = true;
    public $hasShowLabelOptions = true;

    public function preparePreview()
    {
        parent::preparePreview();

        $this->renderSettings['required'] = false;

        if (isset($this->data['required'])) {
            $this->renderSettings['required'] = $this->data['required'];
        }

        $this->renderData['values'] =  mw()->forms_manager->countries_list();
    }
}
