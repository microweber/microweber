<?php

namespace MicroweberPackages\Module\Models;

use Illuminate\Database\Eloquent\Model;
/** @deprecated */
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
    protected $fillable = [
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
    protected $casts = [
        'settings' => 'array',
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
        if (!$this->icon and isset($this->type)) {
            if ($this->type == 'laravel-module') {
                $iconUrlLocationFromConfig = config($this->module . '.icon');
                if ($iconUrlLocationFromConfig) {
                    $this->icon = $iconUrlLocationFromConfig;
                    $iconUrl = $iconUrlLocationFromConfig;
                    return $iconUrl;
                } else {
                    return '';
                }
            }
        }
        $icon = $this->icon;
        $icon = str_replace('{SITE_URL}', site_url(), $icon);

        return $icon;
    }

    public function getIconInline()
    {
        $iconUrl = $this->icon;
        if (!$this->icon and isset($this->type)) {
            if ($this->type == 'laravel-module') {
                /*$iconUrlLocationFromConfig = config($this->module . '.icon');

                if ($iconUrlLocationFromConfig) {
                    $this->icon = $iconUrlLocationFromConfig;
                    $iconUrl = $iconUrlLocationFromConfig;
                    return '<img src="' . $iconUrl . '" />';
                } else {
                    return '';
                }*/
            }
        }

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

    public function adminUrl()
    {
        return module_admin_url($this->module);
    }

}
