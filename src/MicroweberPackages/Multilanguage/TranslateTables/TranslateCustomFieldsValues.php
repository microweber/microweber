<?php
/**
 * Created by PhpStorm.
 * User: Bojidar Slaveykov
 * Date: 2/27/2020
 * Time: 12:50 PM
 */

class TranslateCustomFieldsValues extends TranslateTable {

    protected $relId = 'id';
    protected $relType = 'custom_fields_values';

    protected $columns = [
        'value'
    ];

}