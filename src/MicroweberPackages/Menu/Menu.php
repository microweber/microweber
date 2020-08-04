<?php
namespace MicroweberPackages\Menu;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    public $table = 'menus';

    public static function boot()
    {
        parent::boot();
    }
}
