<?php
/**
 * Created by PhpStorm.
 * User: Bojidar Slaveykov
 * Date: 2/27/2020
 * Time: 12:50 PM
 */
namespace MicroweberPackages\Multilanguage\TranslateTables;


class TranslateTaggingTagged extends TranslateTable
{
    protected $relId = 'id';
    protected $relType = 'tagging_tagged';

    protected $columns = [
        'tag_name',
       // 'tag_slug',
        'tag_description'
    ];
}
