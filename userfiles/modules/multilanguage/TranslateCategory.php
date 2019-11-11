<?php
require_once 'TranslateTable.php';

class TranslateCategory extends TranslateTable {

    protected $relId = 'id';
    protected $relType = 'categories';

    protected $columns = [
        'title',
        'description'
    ];

}