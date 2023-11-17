<?php
namespace MicroweberPackages\Module\Models;

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

    public function icon()
    {
        $icon = $this->icon;
        $icon = str_replace( '{SITE_URL}',site_url(), $icon);

        return $icon;
    }

    public function getIconInline()
    {
        $iconUrl = $this->icon;
        $icon = str_replace('{SITE_URL}', '', $this->icon);
        $icon = url2dir($icon);

        if (file_exists($icon)) {

            if (get_file_extension($icon) == 'svg') {
                $content = file_get_contents($icon);
                $content = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $content);
                return $content;
            } else {
                return '<img src="' . $iconUrl . '" />';
            }
        }
    }

    public function register($module)
    {
  //      return app()->module_manager->register('order/list', 'MicroweberPackages\Order\Http\Controllers\OrdersController');;
    }

}
