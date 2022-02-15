<?php

/*
 * This file is part of Microweber
 *
 * (c) Microweber CMS LTD
 *
 * For full license information see
 * https://github.com/microweber/microweber/blob/master/LICENSE
 *
 */

namespace MicroweberPackages\Option;

use DB;
use Cache;
use MicroweberPackages\Helper\HTMLClean;
use MicroweberPackages\Option\Models\ModuleOption;
use MicroweberPackages\Option\Models\Option;
use MicroweberPackages\Option\Traits\ModuleOptionTrait;

class OptionManager
{
    public $app;
    public $options_memory = array(); //internal array to hold options in cache
    public $override_memory = array(); //array to hold options values that are not persistent in DB and changed on runtime
    public $tables = array();
    public $table_prefix = false;
    public $adapters_dir = false;

    use ModuleOptionTrait;

    public function __construct($app = null)
    {
        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }
        $this->set_table_names();
    }

    public function set_table_names($tables = false)
    {
        if (!is_array($tables)) {
            $tables = array();
        }
        if (!isset($tables['content'])) {
            $tables['options'] = 'options';
        }
        $this->tables = $tables;
        if (!defined('MW_DB_TABLE_OPTIONS')) {
            define('MW_DB_TABLE_OPTIONS', $tables['options']);
        }
    }

    public function get_all($params = '')
    {
        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }


        $data = $params;
        $table = $this->tables['options'];
        //  $data['debug'] = 1000;
        if (!isset($data['limit'])) {
            $data['limit'] = 1000;
        }
        //   $data['cache_group'] = 'options/global';
        $data['table'] = $table;

        $get = $this->app->database_manager->get($data);

        if (!empty($get)) {
            foreach ($get as $key => $value) {
                if (isset($get[$key]['field_values']) and $get[$key]['field_values'] != false) {
                    $get[$key]['field_values'] = unserialize(base64_decode($get[$key]['field_values']));
                }
                if (isset($get[$key]['option_value']) and strval($get[$key]['option_value']) != '') {
                    $get[$key]['option_value'] = $this->app->url_manager->replace_site_url_back($get[$key]['option_value']);
                }
            }
        }

        return $get;
    }

    public function get_groups($is_system = false)
    {
        $table = $this->tables['options'];
        //$query = $this->app->database_manager->table($table);

        $query = new \MicroweberPackages\Option\Models\Option();
        $query = $query->select('option_group');

        $query = $query->whereNull('module');
        $query = $query->whereNotNull('option_group');
        $query = $query->groupBy('option_group');
        if ($is_system != false) {
            $query = $query->where('is_system', '=', 1);
        } else {
            $query = $query->where('is_system', '=', 0);
            $query = $query->orWhere('is_system', '=', null);
        }

        $res = $query->get();


        if ($res and !empty($res)) {
            $res = $res->toArray();
            $res1 = array();
            foreach ($res as $item) {
                $item = (array)$item;

                $res1[] = $item['option_group'];
            }
        }

        return $res1;
    }

    public function delete($key, $option_group = false, $module_id = false)
    {

        $key = $this->app->database_manager->escape_string($key);

        $query = Option::query();
        $query = $query->where('option_key', '=', $key);

        if ($option_group != false) {
            $query = $query->where('option_group', '=', $option_group);
        }

        if ($module_id != false) {
            $query = $query->where('module', '=', $module_id);
        }

        $query->delete();

        $this->clear_memory();

        return true;
    }


    public function set_default($data)
    {
        $changes = false;

        if (is_array($data)) {
            if (!isset($data['option_group'])) {
                $data['option_group'] = 'other';
            }
            if (isset($data['option_key'])) {
                $check = $this->get($data['option_key'], $option_group = $data['option_group'], $return_full = false, $orderby = false);
                if ($check == false) {
                    $changes = $this->save($data);
                }
            }
        } else {
            return false;
        }

        return $changes;
    }


    private $is_use = true;

    public function setUseCache($is_use = false)
    {
        $this->is_use = $is_use;
    }

    /**
     * Getting options from the database.
     *
     * @param $optionKey array|string - if array it will replace the db params
     * @param $optionGroup string - your option group
     * @param $returnFull bool - if true it will return the whole db row as array rather then just the value
     * @param $module string - if set it will store option for module
     * Example usage:
     * $this->get('my_key', 'my_group');
     */
    public $memoryOptionGroupNew = [];
    public function get($optionKey, $optionGroup = false, $returnFull = false, $orderBy = false, $module = false)
    {
        if (!mw_is_installed()) {
            return false;
        }

        if (isset($this->memoryOptionGroupNew[$optionGroup])) {
            return $this->getOptionFromOptionsArray($optionKey, $this->memoryOptionGroupNew[$optionGroup], $returnFull);
        }

        if ($optionGroup) {
            $allOptions = app()->option_repository->getOptionsByGroup($optionGroup);
            $this->memoryOptionGroup[$optionGroup] = $allOptions;
            return $this->getOptionFromOptionsArray($optionKey, $allOptions, $returnFull);
        }




//        if ($optionGroup) {
//
//            $allOptions = [];
//              $getAllOptions = DB::table('options')->where('option_group', $optionGroup)->get();
//              if ($getAllOptions != null) {
//                  $allOptions = collect($getAllOptions)->map(function($x){
//                      return (array) $x;
//                  })->toArray();
//              }
//
//            $this->memoryOptionGroupNew[$optionGroup] = $allOptions;
//            return $this->getOptionFromOptionsArray($optionKey, $allOptions, $returnFull);
//        }

    }

    public function _____get($optionKey, $optionGroup = false, $returnFull = false, $orderBy = false, $module = false)
    {

        if (!mw_is_installed()) {
            return false;
        }

        // old variant
    /*    if (isset($this->memoryOptionGroup[$optionGroup])) {
            return $this->getOptionFromOptionsArray($optionKey, $this->memoryOptionGroup[$optionGroup], $returnFull);
        }

        if ($optionGroup) {

            //  $allOptions = Option::where('option_group', $optionGroup)->get()->toArray();

            $allOptions = app()->option_repository->getByParams(['option_group'=>$optionGroup]);
//dd($allOptions);

        //    $allOptions = app()->database_manager->get('table=options&option_group=' . $optionGroup);
            //   dd($allOptions);
            $this->memoryOptionGroup[$optionGroup] = $allOptions;
            return $this->getOptionFromOptionsArray($optionKey, $allOptions, $returnFull);
        }*/


        if(isset($this->options_memory['allOptionGroups'])){
            $allOptionGroups = $this->options_memory['allOptionGroups'];
        } else {
            $this->options_memory['allOptionGroups'] =  $allOptionGroups = app()->option_repository->getByParams('no_limit=1&fields=option_group&group_by=option_group');

        }

        if($allOptionGroups and is_array($allOptionGroups)){
            $allOptionGroups = array_flatten($allOptionGroups);
            $allOptionGroups = array_flip($allOptionGroups);
        }

        // variant 2 repo
        if ($optionGroup) {
            if($allOptionGroups){
                if(!isset($allOptionGroups[$optionGroup])){
                    return false;
                }
            }


            if(isset($this->options_memory[$optionGroup])){
                $allOptions = $this->options_memory[$optionGroup];
            } else {
                $this->options_memory[$optionGroup] =  $allOptions =  app()->option_repository->getOptionsByGroup($optionGroup);


            }




            //   $startmb = memory_get_usage();
         //  $allOptions = app()->option_repository->getByParams('no_limit=1&fields=id,option_key,option_group,option_value&option_group='.$optionGroup);

           //$allOptions = app()->option_repository->getByParams('fields=id,option_key,option_group,option_value');
           // var_dump($this->formatBytes((memory_get_usage()-$startmb)));die();

            $groupedOptions = [];
            if (!empty($allOptions) && is_array($allOptions)) {
                foreach ($allOptions as $option) {
                    $groupedOptions[$option['option_group']][] = $option;
                }
            }

            if (isset($groupedOptions[$optionGroup])) {
                return $this->getOptionFromOptionsArray($optionKey, $groupedOptions[$optionGroup], $returnFull);
            }
        }

        return false;
    }
//
//    private function formatBytes($size, $precision = 2)
//    {
//        $base = log($size, 1024);
//        $suffixes = array('', 'K', 'M', 'G', 'T');
//
//        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
//    }

    private function getOptionFromOptionsArray($key, $options, $returnFull)
    {
        if ($options) {
            foreach ($options as $option) {
                if ($option['option_key'] == $key) {
                    $option['option_value'] = $this->app->url_manager->replace_site_url_back($option['option_value']);
                    if ($returnFull) {
                        return $option;
                    }
                    return $option['option_value'];
                }
            }
        }
    }

    /**
     * You can use this function to store options in the database.
     *
     * @param $data array|string
     * Example usage:
     *
     * $option = array();
     * $option['option_value'] = 'my value';
     * $option['option_key'] = 'my_option';
     * $option['option_group'] = 'my_option_group';
     * mw()->option_manager->save($option);
     */
    public function save($data)
    {

        if (defined('MW_API_CALL')) {
            $is_admin = $this->app->user_manager->is_admin();
            if ($is_admin == false) {
                return false;
            }
        }

        if (is_string($data)) {
            $data = parse_params($data);
        }

    /*    $xssClean = new HTMLClean();
        $data = $xssClean->cleanArray($data);*/

        $this->clear_memory();
        app()->option_repository->clearCache();

        $option_group = false;
        if (is_array($data)) {
            if (isset($data['option_key']) and strval($data['option_key']) != '') {
                if (strstr($data['option_key'], '|for_module|')) {
                    $option_key_1 = explode('|for_module|', $data['option_key']);
                    if (isset($option_key_1[0])) {
                        $data['option_key'] = $option_key_1[0];
                    }
                    if (isset($option_key_1[1])) {
                        $data['module'] = $option_key_1[1];
                        if (isset($data['id']) and intval($data['id']) > 0) {
                            $chck = $this->get('limit=1&module=' . $data['module'] . '&option_key=' . $data['option_key']);
                            if (isset($chck[0]) and isset($chck[0]['id'])) {
                                $data['id'] = $chck[0]['id'];
                            } else {
                                $table = $this->tables['options'];
                                $copy = $this->app->database_manager->copy_row_by_id($table, $data['id']);
                                $data['id'] = $copy;
                                $this->clear_memory();

                            }
                        }
                    }
                }
            }

            $delete_content_cache = false;
            if (!isset($data['id']) or intval($data['id']) == 0) {
                if (isset($data['option_key']) and isset($data['option_group']) and trim($data['option_group']) != '') {
                    $option_group = $data['option_group'];
                    $this->clear_memory();

                    $existing = $this->get($data['option_key'], $data['option_group'], $return_full = true);

                    if ($existing == false) {
                        //
                    } elseif (isset($existing['id'])) {
                        $data['id'] = $existing['id'];
                    }
                }
            }

            $table = $this->tables['options'];
            if (isset($data['field_values']) and $data['field_values'] != false) {
                $data['field_values'] = base64_encode(serialize($data['field_values']));
            }
            if (isset($data['module'])) {
                $data['module'] = str_ireplace('/admin', '', $data['module']);
            }

            if (isset($data['option_key']) and strval($data['option_key']) != '') {
                if ($data['option_key'] == 'current_template') {
                    $delete_content_cache = true;
                }
                if (isset($data['option_group']) and strval($data['option_group']) == '') {
                    unset($data['option_group']);
                }

                if (isset($data['option_value']) and $data['option_value'] != false) {
                    $data['option_value'] = $this->app->url_manager->replace_site_url($data['option_value']);
                }

                $data['allow_html'] = true;
                $data['allow_scripts'] = true;
                $data['table'] = $this->tables['options'];

                // $this->app->event_manager->trigger('option.before.save', $data);

                if (!empty($data['module'])) {
                    $findModuleOption = ModuleOption::where('option_key', $data['option_key'])->where('option_group', $data['option_group'])->first();
                    if ($findModuleOption == null) {
                        $findModuleOption = new ModuleOption();
                        $findModuleOption->option_key = $data['option_key'];
                        $findModuleOption->option_group = $data['option_group'];
                    }

                    if (isset($data['lang'])) {
                        $findModuleOption->lang = $data['lang'];
                    } else {
                        $findModuleOption->lang = app()->getLocale();
                    }

                    $findModuleOption->module = $data['module'];
                    $findModuleOption->option_value = $data['option_value'];
                    $save = $findModuleOption->save();

                    // Remove dublicates
                    ModuleOption::where('id', '!=', $findModuleOption->id)->where('option_key', $data['option_key'])->where('option_group', $data['option_group'])->delete();

                    $this->memoryModuleOptionGroup = [];
                } else {
                    $findOption = Option::where('option_key', $data['option_key'])->where('option_group', $data['option_group'])->first();
                    if ($findOption == null) {
                        $findOption = new Option();
                        $findOption->option_key = $data['option_key'];
                        $findOption->option_group = $data['option_group'];
                    }
                    if (isset($data['lang'])) {
                        $findOption->lang = $data['lang'];
                    }
                    $findOption->option_value = $data['option_value'];
                    $save = $findOption->save();

                    // Remove dublicates
                    Option::where('id', '!=', $findOption->id)->where('option_key', $data['option_key'])->where('option_group', $data['option_group'])->delete();

                    $this->memoryOptionGroup = [];
                }

                if ($option_group != false) {
                    $cache_group = 'options/' . $option_group;
                    $this->app->cache_manager->delete($cache_group);
                } else {
                    $cache_group = 'options/' . 'global';
                    $this->app->cache_manager->delete($cache_group);
                }
                if ($save != false) {
                    $cache_group = 'options/' . $save;
                    $this->app->cache_manager->delete($cache_group);
                }

                if ($delete_content_cache != false) {
                    $cache_group = 'content/global';
                    $this->app->cache_manager->delete($cache_group);
                }

                if (isset($data['id']) and intval($data['id']) > 0) {
                    $opt = $this->get_by_id($data['id']);
                    if (isset($opt['option_group'])) {
                        $cache_group = 'options/' . $opt['option_group'];
                        $this->app->cache_manager->delete($cache_group);
                    }
                    $cache_group = 'options/' . intval($data['id']);
                    $this->app->cache_manager->delete($cache_group);
                }

                $this->app->cache_manager->delete('options');
                $this->app->cache_manager->delete('content');
                $this->app->cache_manager->delete('repositories');
                $this->clear_memory();

                return $save;
            }
        }
    }

    public function get_by_id($id)
    {
        $id = intval($id);
        if ($id == 0) {
            return false;
        }

        $params = array();
        $params['id'] = $id;
        $params['single'] = true;

        return $this->get_all($params);
    }

    public function get_items_per_page($group = 'website')
    {
        if (!isset($this->options_memory['items_per_page'])) {
            $this->options_memory = array();
        }
        if (isset($this->options_memory['items_per_page'][$group])) {
            return $this->options_memory['items_per_page'][$group];
        }

        if (mw_is_installed() == true) {
            $table = $this->tables['options'];
            $ttl = now()->addYear(1);

            $cache_key = $table . '_items_per_page_' . $group;
            $items_per_page = Cache::tags($table)->remember($cache_key, $ttl, function () use ($table, $group) {
                $items_per_page = DB::table($table)->where('option_key', 'items_per_page')
                    ->where('option_group', $group)
                    ->first();

                return $items_per_page;
            });

            if (!empty($items_per_page)) {
                $items_per_page = (array)$items_per_page;
                if (isset($items_per_page['option_value'])) {
                    $result = $items_per_page['option_value'];
                    $this->options_memory['items_per_page'][$group] = $result;

                    return $result;
                }
            }
        }
    }


    public function override($option_group, $key, $value)
    {
        if (!isset($this->override_memory[$option_group])) {
            $this->override_memory[$option_group] = array();
        }
        $this->override_memory[$option_group][$key] = $value;
    }

    public function clear_memory()
    {


        $this->options_memory = array();
        $this->memoryOptionGroupNew = array();
        $this->override_memory = array();
        if (isset($this->memoryOptionGroup)) {
            $this->memoryOptionGroup = array();
        }
        if (isset($this->memoryModuleOptionGroup)) {

            $this->memoryModuleOptionGroup = array();
        }

        app()->option_repository->clearCache();
    }
}
