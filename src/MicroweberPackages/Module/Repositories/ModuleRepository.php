<?php


namespace MicroweberPackages\Module\Repositories;


use Illuminate\Support\Facades\DB;
use MicroweberPackages\Module\Module;
use MicroweberPackages\Repository\Repositories\AbstractRepository;


class ModuleRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public $model = Module::class;

    public static $_getAllModules = [];

    public function getAllModules()
    {
        if (!empty(self::$_getAllModules)) {
            return self::$_getAllModules;
        }

        $modules = $this->cacheCallback(__FUNCTION__, func_get_args(), function () {

            $getModules = DB::table('modules')->get();
            $allModules = collect($getModules)->map(function ($item) {

                if (!empty($item->settings)) {
                    $item->settings = json_decode($item->settings, true);
                }

                return (array)$item;

            })->toArray();

            if(!empty($allModules)){
            $allModules = app()->url_manager->replace_site_url_back($allModules);
            }

            return $allModules;

        });

        self::$_getAllModules = $modules;
        return $modules;
    }

    public function getModulesByType($type)
    {
        $return = [];
        $all = $this->getAllModules();
        if ($all) {
            foreach ($all as $module_item) {
                if (isset($module_item['type']) and $module_item['type'] == $type) {
                    if (isset($module_item['installed']) and $module_item['installed'] == 1) {
                        $return [] = $module_item;
                    }
                }
            }
        }
        return $return;
    }

    public function getModule($module)
    {
        $all = $this->getAllModules();
        if ($all) {
            foreach ($all as $module_item) {
                if (isset($module_item['module']) and $module_item['module'] == $module) {
                    return $module_item;
                }
            }
        }
        return [];

    }

    public function getSystemLicenses()
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () {
            $data = DB::table('system_licenses')->get();
            if ($data === null) {
                return [];
            }
            $data = collect($data)->map(function ($option) {
                return (array)$option;
            })->toArray();

            if ($data === null) {
                return [];
            }
            return $data;
        });

     }



    public function clearCache()
    {
        self::$_getAllModules = [];
        parent::clearCache();
    }


}
