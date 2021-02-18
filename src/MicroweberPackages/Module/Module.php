<?php
namespace MicroweberPackages\Module;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public $table = 'modules';

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
        return app()->module_manager->register('order/list', 'MicroweberPackages\Order\Http\Controllers\OrdersController');;
    }



}
