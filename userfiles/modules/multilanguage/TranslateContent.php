<?php
require_once 'TranslateTable.php';

class TranslateContent extends TranslateTable {

    protected $primaryId = 'id';
    protected $recognitionIds = array('id'=>'content_id');
    protected $table = 'content';

    protected $columns = [
        'title',
        'description',
        'content_meta_title',
        'content_meta_keywords'
    ];

}