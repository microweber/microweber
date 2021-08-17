<?php


namespace MicroweberPackages\Module\Repositories;


use MicroweberPackages\Content\Content;
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

            $item = $this->all();
            if ($item) {

                return $item->toArray();

            }
            return [];

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
}
