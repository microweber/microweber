<?php
/**
 * Created by PhpStorm.
 * User: Bojidar Slaveykov
 * Date: 2/27/2020
 * Time: 12:50 PM
 */
namespace MicroweberPackages\Multilanguage\TranslateTables;

use MicroweberPackages\Multilanguage\TranslateTable;

class TranslateCustomFields extends TranslateTable {

    protected $relId = 'id';
    protected $relType = 'custom_fields';

    protected $columns = [
        'name',
        'placeholder'
    ];

}
