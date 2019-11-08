<?php
require_once 'TranslateTable.php';

class TranslateContentFields extends TranslateTable {

    protected $relId = 'id';
    protected $relType = 'content_fields';

    protected $columns = [
        'value'
    ];

}