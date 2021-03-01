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

    public $fields = [
        'country' => 'Country',
        'city' => 'City',
        'zip' => 'Zip/Post code',
        'state' => 'State/Province',
        'address' => 'Address'
    ];

    public $appendAdditionalData = [
       'countries' => ''
    ];

    public function __construct()
    {
        parent::__construct();

        $this->appendAdditionalData['countries'] = mw()->forms_manager->countries_list();
    }
}