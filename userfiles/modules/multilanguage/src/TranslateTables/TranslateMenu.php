<?php

class TranslateMenu extends TranslateTable {

    protected $relId = 'id';
    protected $relType = 'menus';

    protected $columns = [
        'title',
        'description',
    ];

}