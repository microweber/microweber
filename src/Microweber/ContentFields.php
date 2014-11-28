<?php


class ContentFields extends BaseModel
{

    public $table = 'content_fields';
    public $table_drafts = 'content_fields_drafts';

    public static function boot()
    {
        parent::boot();
    }


}

