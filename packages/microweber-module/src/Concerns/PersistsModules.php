<?php

namespace MicroweberPackages\Module\Concerns;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Database\Facades\DatabaseManager;

/**
 * Extracted from ModuleManager to decompose the god class. Composed back via
 * `use`, so $this state and inter-method calls are unchanged.
 */
trait PersistsModules
{
    public function save($data_to_save)
    {
        if (app()->user_manager->is_admin() == false and $this->_install_mode == false) {
            return false;
        }
        if (isset($data_to_save['is_element']) and $data_to_save['is_element'] == true) {
        }

        $table = 'modules';
        $save = false;

        if (!empty($data_to_save)) {
            $s = $data_to_save;

            if (!isset($s['parent_id'])) {
                $s['parent_id'] = 0;
            }

            if (!isset($s['installed']) or $s['installed'] == 'auto') {
                $s['installed'] = 1;
            }

            if (isset($s['settings']) and is_array($s['settings'])) {
                $s['settings'] = json_encode($s['settings']);
            }

            $s['allow_html'] = true;

            if (!isset($s['id']) and isset($s['module'])) {
                $s['module'] = $data_to_save['module'];

                if (!isset($s['module_id'])) {
                    //$save = $this->get_modules('ui=any&no_cache=1&module=' . $s['module']);
                    $save = db_get('table=modules&no_cache=1&module=' . $s['module']);

                    if ($save != false and isset($save[0]) and is_array($save[0]) and isset($save[0]['id'])) {
                        $s['id'] = intval($save[0]['id']);
                        //   $s['position'] = intval($save[0]['position']);
                        $s['installed'] = intval($save[0]['installed']);

                        $save = DatabaseManager::save($table, $s);
                        // print_r($save);
                        $mname_clen = str_replace('\\', '/', $s['module']);
                        if ($s['id'] > 0) {

                            //$delid = $s["id"];
                            DB::table($table)->where('id', '!=', $s['id'])->where('module', $s['module'])->delete();
                            // $del = "DELETE FROM {$table} WHERE module='{$mname_clen}' AND id!={$delid} ";
                            //DatabaseManager::q($del);
                        }
                    } else {

                        $save = DatabaseManager::save($table, $s);
                    }
                }
            } else {
                $save = DatabaseManager::save($table, $s);
            }
        }
        return $save;
    }

    /* @deprecated */
    public function reorder_modules($data)
    {
        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }
        $table = 'modules';
        foreach ($data as $value) {
            if (is_array($value)) {
                $indx = array();
                $i = 0;
                foreach ($value as $value2) {
                    $indx[$i] = $value2;
                    ++$i;
                }
                DatabaseManager::update_position_field($table, $indx);
                app()->module_repository->clearCache();

                return $indx;
            }
        }
        // $this->db_init();
    }

    /* @deprecated */
    public function uninstall($params)
    {
        if (is_array($params) and isset($params['for_module'])) {
            $module_name = $params['for_module'];
        } else {
            $module_name = $params;
        }

        if (app()->bound('modules')) {
            $module = app()->modules->find($module_name);
            if ($module) {
                if ($module->isEnabled()) {
                    $module->disable();
                    app()->module_repository->clearCache();
                }
            }
        }

        $this->app->cache_manager->clear();
        app()->module_repository->clearCache();
    }

    public function set_installed($params)
    {

        if (is_array($params) and isset($params['for_module'])) {
            $module_name = $params['for_module'];
        } else {
            $module_name = $params;
        }

        if (app()->bound('modules')) {
            $module = app()->modules->find($module_name);
            if ($module) {
                if (!$module->isEnabled()) {
                    $module->enable();
                    app()->module_repository->clearCache();
                }
            }
        }

    }
}
