<?php


class Media extends BaseModel
{
    use \Conner\Tagging\Taggable;

    public $table = 'media';

    public static function boot()
    {
        parent::boot();
    }

    public function tags()
    {
        return $this->belongsToMany('Tag');
    }
}
