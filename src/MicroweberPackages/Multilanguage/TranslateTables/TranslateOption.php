<?php
/**
 * Created by PhpStorm.
 * User: Bojidar Slaveykov
 * Date: 2/27/2020
 * Time: 12:50 PM
 */
namespace MicroweberPackages\Multilanguage\TranslateTables;


class TranslateOption extends TranslateTable {

    protected $relId = 'id';
    protected $relType = 'options';

    protected $columns = [
        'option_value'
    ];

}
