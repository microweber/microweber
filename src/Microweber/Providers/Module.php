<?php




/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber LTD
 *
 * For full license information see
 * http://microweber.com/license/
 *
 */

namespace Microweber\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Microweber\Utils\Database;

class Module
{
    public $tables = array();


    function __construct($app = null)
    {
        $this->set_table_names();
    }

    public function install()
    {
        $this->db_init();

    }

    public function set_table_names($tables = false)
    {


        if (!is_array($tables)) {
            $tables = array();
        }
        if (!isset($tables['modules'])) {
            $tables['modules'] = 'modules';
        }
        if (!isset($tables['elements'])) {
            $tables['elements'] = 'elements';
        }
        if (!isset($tables['module_templates'])) {
            $tables['module_templates'] = 'module_templates';
        }
        if (!isset($tables['system_licenses'])) {
            $tables['system_licenses'] = 'system_licenses';
        }
        $this->tables['modules'] = $tables['modules'];
        $this->tables['elements'] = $tables['elements'];
        $this->tables['module_templates'] = $tables['module_templates'];
        $this->tables['system_licenses'] = $tables['system_licenses'];

    }

    public function db_init()
    {

        $table_name = $this->tables['modules'];
        $table_name2 = $this->tables['elements'];
        $table_name3 = $this->tables['module_templates'];
        $table_name4 = $this->tables['system_licenses'];

        $fields_to_add = array();
        $fields_to_add['updated_on'] = 'dateTime';
        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['expires_on'] = 'dateTime';

        $fields_to_add['created_by'] = 'integer';

        $fields_to_add['edited_by'] = 'integer';

        $fields_to_add['name'] = 'longText';
        $fields_to_add['parent_id'] = 'integer';
        $fields_to_add['module_id'] = 'longText';

        $fields_to_add['module'] = 'longText';
        $fields_to_add['description'] = 'longText';
        $fields_to_add['icon'] = 'longText';
        $fields_to_add['author'] = 'longText';
        $fields_to_add['website'] = 'longText';
        $fields_to_add['help'] = 'longText';
        $fields_to_add['type'] = 'longText';

        $fields_to_add['installed'] = 'integer';
        $fields_to_add['ui'] = 'integer';
        $fields_to_add['position'] = 'integer';
        $fields_to_add['as_element'] = 'integer';
        $fields_to_add['ui_admin'] = 'integer';
        $fields_to_add['ui_admin_iframe'] = 'integer';
        $fields_to_add['is_system'] = 'integer';

        $fields_to_add['version'] = 'string';

        $fields_to_add['notifications'] = 'integer';

        $db = new Database();
        $db->build_table($table_name, $fields_to_add);

        $fields_to_add['layout_type'] = 'string';
        $db->build_table($table_name2, $fields_to_add);


        $fields_to_add = array();
        $fields_to_add['updated_on'] = 'dateTime';

        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['created_by'] = 'integer';
        $fields_to_add['edited_by'] = 'integer';
        $fields_to_add['module_id'] = 'longText';
        $fields_to_add['name'] = 'longText';
        $fields_to_add['module'] = 'longText';
        $db->build_table($table_name3, $fields_to_add);


        $fields_to_add = array();
        $fields_to_add['updated_on'] = 'dateTime';
        $fields_to_add['created_on'] = 'dateTime';
        $fields_to_add['created_by'] = 'integer';
        $fields_to_add['edited_by'] = 'integer';
        $fields_to_add['rel'] = 'longText';
        $fields_to_add['rel_name'] = 'longText';
        $fields_to_add['local_key'] = 'longText';
        $fields_to_add['local_key_hash'] = 'longText';
        $fields_to_add['registered_name'] = 'longText';
        $fields_to_add['company_name'] = 'longText';
        $fields_to_add['domains'] = 'longText';
        $fields_to_add['status'] = 'longText';
        $fields_to_add['product_id'] = 'integer';
        $fields_to_add['service_id'] = 'integer';
        $fields_to_add['billing_cycle'] = 'longText';
        $fields_to_add['reg_on'] = 'dateTime';
        $fields_to_add['due_on'] = 'dateTime';

        $db->build_table($table_name4, $fields_to_add);


        return true;

    }


}