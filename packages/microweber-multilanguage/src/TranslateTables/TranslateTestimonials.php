<?php
/**
 * Created by PhpStorm.
 * User: Bojidar Slaveykov
 * Date: 2/27/2020
 * Time: 12:50 PM
 */
namespace MicroweberPackages\Multilanguage\TranslateTables;

use MicroweberPackages\Multilanguage\TranslateTable;

class TranslateTestimonials extends TranslateTable {

    protected $relId = 'id';
    protected $relType = 'testimonials';

    protected $columns = [
        'name',
        'content',
        'project_name',
        'client_company',
        'client_role'
    ];

}
