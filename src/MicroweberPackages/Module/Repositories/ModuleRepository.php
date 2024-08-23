<?php


namespace MicroweberPackages\Module\Repositories;


use Illuminate\Support\Facades\DB;
use MicroweberPackages\Module\Models\Module;
use MicroweberPackages\Repository\Repositories\AbstractRepository;


class ModuleRepository extends AbstractRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public $model = \MicroweberPackages\Module\Models\Module::class;

    public static $_getAllModules = [];

    /**
     * Get all modules
     *
     * @return array
     */
    public function getAllModules(): array
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

            if (!empty($allModules)) {
                $allModules = app()->url_manager->replace_site_url_back($allModules);
            }

            return $allModules;

        });

        self::$_getAllModules = $modules;
        return $modules;
    }

    /**
     * @deprecated
     */
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

    public function setUninstalled($module)
    {
        $module = $this->getModule($module);
        if ($module) {
            $module['installed'] = 0;
            $module['settings'] = '';
            $this->getModel()->where('module', $module['module'])->update($module);
            $this->clearCache();

        }
        return true;
    }

    public function installLaravelModule($scannedModule)
    {
        $module = $scannedModule['alias'];
        $moduleData =$this->modelInstance->newInstance()->where('module', $module)->first();
        if ($moduleData) {
            $moduleData = $moduleData->toArray();
        }


        if ($moduleData and isset($moduleData['installed']) and $moduleData['installed'] == 0) {
            // module is uninstalled
            return;
        }


        $data = [
            'module' => $scannedModule['alias'],
            'installed' => 1,
            'settings' => $scannedModule
        ];

        if (isset($scannedModule['name'])) {
            $data['name'] = $scannedModule['name'];
        }
        if (isset($scannedModule['description'])) {
            $data['description'] = $scannedModule['description'];
        }
        if (isset($scannedModule['priority'])) {
            $data['position'] = $scannedModule['priority'];
        }
//
//        if (isset($scannedModule['composer']['autoload'])) {
//            $data['position'] = $scannedModule['priority'];
//        }


        if (!$moduleData) {

            $module = new Module();
            $module->fill($data);
            $module->save();


        } else {
            $this->modelInstance->newInstance()->where('module', $module)->update($data);
        }

        $this->clearCache();
    }

    public function setInstalled($module, $config = [])
    {

        $checkIfExist = $this->getModel()->where('module', $module)->first();
        if ($checkIfExist == null) {
            $moduleData = [
                'module' => $module,
                'installed' => 1,
                'settings' => json_encode($config)
            ];
            $this->getModel()->create($moduleData);

        } else {
            $checkIfExist->installed = 1;
            $checkIfExist->settings = json_encode($config);
            $checkIfExist->save();
        }
        $this->clearCache();


        return true;
    }


    public function clearCache()
    {
        self::$_getAllModules = [];
        parent::clearCache();
    }


    public function generateCacheTags()
    {
        $tag = parent::generateCacheTags();
        $tag[] = 'modules';

        return $tag;
    }


}
