<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/26/2021
 * Time: 11:42 AM
 */

namespace MicroweberPackages\CustomField\Fields;


class Address extends DefaultField
{
    public $hasResponsiveOptions = true;
    public $hasErrorTextOptions = true;
    public $hasRequiredOptions = true;
    public $hasShowLabelOptions = true;

    public $fields = [
        'country' => 'Country',
        'city' => 'City',
        'zip' => 'Zip/Post code',
        'state' => 'State/Province',
        'address' => 'Address'
    ];

    public function preparePreview()
    {
        $this->renderData = $this->data;

        $addressFields = [];
        if (!empty($this->data['options'])) {
            foreach ($this->fields as $fieldKey=>$fieldValue) {
                if (isset($this->data['options'][$fieldKey])) {
                    $addressFields[$fieldKey] = $fieldValue;
                }
            }
        }

        $this->renderData['help'] = '';
        $this->renderData['countries'] = mw()->forms_manager->countries_list();
        $this->renderData['values'] = $addressFields;

        $this->renderSettings['required'] = true;
        $this->renderSettings['show_label'] = true;
       // $this->renderSettings = $this->calculateFieldSize($this->renderSettings);
    }
}
