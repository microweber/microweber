<?php


class Tag extends BaseModel
{

    public $table = 'tags';

    protected $guarded = array();

    public function content()
    {
        return $this->belongsToMany('Content');
    }
}

