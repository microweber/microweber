<?php
/**
 * Created by PhpStorm.
 * User: Bojidar Slaveykov
 * Date: 2/27/2020
 * Time: 12:50 PM
 */
namespace MicroweberPackages\Content\TranslateTables;

use MicroweberPackages\Content\Repositories\ContentRepository;
use MicroweberPackages\Multilanguage\TranslateTable;

class TranslateContentFields extends TranslateTable {

    protected $relId = 'id';
    protected $relType = 'content_fields';

    protected $columns = [
        'value'
    ];

    protected $repositoryClass = ContentRepository::class;
    protected $repositoryMethods = [
        'getEditField',
    ];

}
