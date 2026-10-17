<?php

namespace MicroweberPackages\Module\Concerns;

use MicroweberPackages\Database\Facades\DatabaseManager;

/**
 * Extracted from ModuleManager to decompose the god class. Composed back via
 * `use`, so $this state and inter-method calls are unchanged.
 */
trait ManagesModuleTemplates
{
    public function get_saved_modules_as_template($params)
    {
        $params = parse_params($params);

        if ($this->app->user_manager->is_admin() == false) {
            return false;
        }

        $table = 'module_templates';

        $params['table'] = $table;
        $params['order_by'] = 'position asc';
        $data = DatabaseManager::get($params);

        return $data;
    }

    public function delete_module_as_template($data)
    {
        if ($this->app->user_manager->is_admin() == false) {
            return false;
        }

        $table = 'module_templates';
        $save = false;

        $adm = $this->app->user_manager->is_admin();
        if ($adm == false) {
            mw_error('Error: not logged in as admin.' . __FILE__ . __LINE__);
        }

        if (isset($data['id'])) {
            $c_id = intval($data['id']);
            DatabaseManager::delete_by_id($table, $c_id);
        }

        if (isset($data['ids']) and is_array($data['ids'])) {
            foreach ($data['ids'] as $value) {
                $c_id = intval($value);
                DatabaseManager::delete_by_id($table, $c_id);
            }
        }
        app()->module_repository->clearCache();

    }

    /* @deprecated */
    public function save_module_as_template($data_to_save)
    {
        if ($this->app->user_manager->is_admin() == false) {
            return false;
        }

        $table = 'module_templates';
        $save = false;

        if (!empty($data_to_save)) {
            $s = $data_to_save;

            $save = DatabaseManager::save($table, $s);
        }
        app()->module_repository->clearCache();

        return $save;
    }
}
