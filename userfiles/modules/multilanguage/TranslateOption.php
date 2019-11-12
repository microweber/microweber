<?php
require_once 'TranslateTable.php';

class TranslateOption extends TranslateTable {

    protected $relId = 'id';
    protected $relType = 'options';

    protected $columns = [
        'option_value'
    ];

}