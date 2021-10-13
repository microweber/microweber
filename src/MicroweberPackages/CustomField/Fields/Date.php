<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/26/2021
 * Time: 11:42 AM
 */

namespace MicroweberPackages\CustomField\Fields;


class Date extends DefaultField {

    public $hasResponsiveOptions = true;
    public $hasErrorTextOptions = true;
    public $hasRequiredOptions = true;
    public $hasShowLabelOptions = true;

    public $defaultSettings = [
        'required'=>false,
        'multiple'=>'',
        'show_label'=>true,
        'show_placeholder'=>false,
        'date_format'=> 'yyyy-mm-dd',
        'field_size'=>12,
        'field_size_desktop'=>12,
        'field_size_tablet'=>12,
        'field_size_mobile'=>12,
    ];
}
