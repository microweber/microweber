<?php
/**
 * Created by PhpStorm.
 * User: Bojidar Slaveykov
 * Date: 2/27/2020
 * Time: 12:50 PM
 */
namespace Modules\Content\TranslateTables;

use MicroweberPackages\Multilanguage\TranslateTable;
use Modules\Content\Repositories\ContentRepository;

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
