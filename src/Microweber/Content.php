<?php


class Content extends BaseModel
{

    public $table = 'content';

    public function notifications()
    {
        return $this->morphMany('Notifications', 'rel');
    }

    public function comments()
    {
        return $this->morphMany('Comments', 'rel');
    }

    public function data_fields()
    {
        return $this->morphMany('ContentData', 'rel');
    }

}

