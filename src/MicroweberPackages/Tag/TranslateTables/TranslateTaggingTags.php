<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 2/27/2020
 * Time: 1:13 PM
 */
namespace MicroweberPackages\Tag\TranslateTables;

use MicroweberPackages\Multilanguage\TranslateTable;

class TranslateTaggingTags extends TranslateTable
{
    protected $relId = 'id';
    protected $relType = 'tagging_tags';

    protected $columns = [
        'name',
       // 'slug',
        'description'
    ];
}
