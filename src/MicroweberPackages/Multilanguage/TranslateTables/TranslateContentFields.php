<?php
/**
 * Created by PhpStorm.
 * User: Bojidar Slaveykov
 * Date: 2/27/2020
 * Time: 12:50 PM
 */

class TranslateContentFields extends TranslateTable {

    protected $relId = 'id';
    protected $relType = 'content_fields';

    protected $columns = [
        'value'
    ];

    protected $repositoryClass = MicroweberPackages\Content\Repositories\ContentRepository::class;
    protected $repositoryMethods = [
        'getEditField',
    ];

}
