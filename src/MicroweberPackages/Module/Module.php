<?php
namespace MicroweberPackages\Module;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public $table = 'modules';

    protected $searchable = [
        'id',
        'name',
        'module',
        'type',
        'as_element',
        'installed',
        'ui',
        'ui_admin',
        'ui_admin_iframe',
        'is_system',
        'categories',
        'settings',
        'parent_id',
        'icon',
        'description',
    ];

    public static function boot()
    {
        // there is some logic in this method, so don't forget this!
        parent::boot();
    }

    public function notifications()
    {
        return $this->morphMany('Notifications', 'rel');
    }


    public function register($module)
    {
  //      return app()->module_manager->register('order/list', 'MicroweberPackages\Order\Http\Controllers\OrdersController');;
    }



}
