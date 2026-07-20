<?php


namespace MicroweberPackages\Module\Repositories;


use MicroweberPackages\Module\Models\Module;
use MicroweberPackages\Repository\Repositories\AbstractRepository;

/** @deprecated */
class ModuleRepository extends AbstractRepository
{
    /**
     * Specify Models class name
     *
     * @return string
     */
    public $model = \MicroweberPackages\Module\Models\Module::class;

    /**
     * Get all modules
     *
     * @return array
     */
    public function getAllModules(): array
    {

        return app()->modules->all();
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

                if (isset($module_item->name) and strtolower($module_item->name) == strtolower($module)) {

                    return $module_item;
                }
            }
        }
        return [];

    }

    public function getSystemLicenses()
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () {
            // License persistence is owned by the microweber-system-licenses package;
            // the repository is the single seam to it (the manager already guards the
            // missing-table case and returns row-arrays).
            return app()->system_licenses_manager->getActiveLicenses();
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

//    public function installLaravelModule($scannedModule)
//    {
//        $module = $scannedModule['module'];
//        $moduleData = Module::where('module', $module)->first();
//        if ($moduleData) {
//            $moduleData = $moduleData->toArray();
//        }
//
//
//        if ($moduleData and isset($moduleData['installed']) and $moduleData['installed'] == 0) {
//            // module is uninstalled
//            return;
//        }
//
//
//        $data = $scannedModule;
//
//
//        if (!$moduleData) {
//            $module = new Module();
//            $module->fill($data);
//            $module->save();
//        } else {
//            Module::where('module', $module)->update($data);
//        }
//        $this->clearCache();
//    }

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
        parent::clearCache();
    }


    public function generateCacheTags(): array
    {
        $tag = parent::generateCacheTags();
        $tag[] = 'modules';

        return $tag;
    }


}
