<?php
use Illuminate\Database\Eloquent\Model as Eloquent;

class Option extends BaseModel
{
    public $table = 'options';

    public static function boot()
    {
        parent::boot();
    }
}