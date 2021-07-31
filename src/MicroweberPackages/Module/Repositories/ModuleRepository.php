<?php


namespace MicroweberPackages\Module\Repositories;


use MicroweberPackages\Content\Content;
use MicroweberPackages\Module\Module;
use MicroweberPackages\Repository\Repositories\AbstractRepository;


class ModuleRepository extends AbstractRepository
{


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


    /**
     * Specify Model class name
     *
     * @return string
     */
    public $model = Module::class;

    public function getAllModules()
    {


        return $this->cacheCallback(__FUNCTION__, func_get_args(), function ()  {

            $item = $this->all();
            if ($item) {

                return $item->toArray();

            }
            return [];

        });


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

    }
}