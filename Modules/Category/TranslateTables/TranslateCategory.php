<?php
/**
 * Created by PhpStorm.
 * User: Bojidar Slaveykov
 * Date: 2/27/2020
 * Time: 12:50 PM
 */
namespace Modules\Category\TranslateTables;

use MicroweberPackages\Multilanguage\TranslateTable;
use Modules\Category\Repositories\CategoryRepository;

class TranslateCategory extends TranslateTable {

    protected $relId = 'id';
    protected $relType = 'categories';

    protected $columns = [
        'url',
        'title',
        'description',
        'category_meta_title',
        'category_meta_keywords',
        'category_meta_description'
    ];

    protected $repositoryClass = CategoryRepository::class;
    protected $repositoryMethods = [
        'getById',
        'getByColumnNameAndColumnValue',
    ];
}
