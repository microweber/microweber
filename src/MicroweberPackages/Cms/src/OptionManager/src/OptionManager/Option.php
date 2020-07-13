<?php
namespace MicroweberPackages\Content\OptionManager;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    public $table = 'options';

    public static function boot()
    {
        parent::boot();
    }
}