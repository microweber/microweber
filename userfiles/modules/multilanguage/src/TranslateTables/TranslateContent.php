<?php

class TranslateContent extends TranslateTable {

    protected $relId = 'id';
    protected $relType = 'content';

    protected $columns = [
        'title',
        'url',
        'description',
        'content_meta_title',
        'content_meta_keywords'
    ];

}
