<?php
/**
 * Created by PhpStorm.
 * User: Bojidar Slaveykov
 * Date: 2/27/2020
 * Time: 12:50 PM
 */
namespace Modules\Menu\TranslateTables;

use MicroweberPackages\Multilanguage\TranslateTable;
use Modules\Menu\Repositories\MenuRepository;

class TranslateMenu extends TranslateTable {

    protected $relId = 'id';
    protected $relType = 'menus';

    protected $columns = [
        'title',
        'description',
    ];

    protected $repositoryClass = MenuRepository::class;
    protected $repositoryMethods = [
        'getById',
        'getMenusByParentId',
    ];
}
