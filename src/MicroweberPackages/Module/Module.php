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
}
