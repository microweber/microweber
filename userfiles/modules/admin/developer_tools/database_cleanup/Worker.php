<?php


namespace admin\developer_tools\database_cleanup;


use Illuminate\Support\Facades\Cache;
use DB;


class Worker {
    public $app;
    public $cache_group = 'database_cleanup_worker';

    function __construct($app = null) {
        if (is_object($app)){
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }


    public function run() {

        $all = DB::table('media')
            ->where('rel_type', 'content')
            ->whereNotIn('rel_id', function ($query) {
                $query->select('id')->from('content');
            })
            ->get();

        if (!empty($all)){
            foreach ($all as $item) {
                mw()->media_manager->delete($item->id);
            }
        }
        $all = DB::table('custom_fields')
            ->where('rel_type', 'content')
            ->whereNotIn('rel_id', function ($query) {
                $query->select('id')->from('content');
            })
            ->get();
        if (!empty($all)){
            foreach ($all as $item) {
                mw()->fields_manager->delete($item->id);
            }
        }
        DB::table('custom_fields_values')
            ->whereNotIn('custom_field_id', function ($query) {
                $query->select('id')->from('custom_fields');
            })
            ->delete();
        DB::table('categories_items')
            ->where('rel_type', 'content')
            ->whereNotIn('rel_id', function ($query) {
                $query->select('id')->from('content');
            })
            ->delete();


        DB::table('categories_items')
            ->whereNotIn('parent_id', function ($query) {
                $query->select('id')->from('categories');
            })
            ->toSql();


        $all = DB::table('content_data')
            ->where('rel_type', 'content')
            ->whereNotIn('rel_id', function ($query) {
                $query->select('id')->from('content');
            })
            ->get();

        if (!empty($all)){
            foreach ($all as $item) {
                mw()->data_fields_manager->delete($item->id);
            }
        }


        return array('success' => 'Cleanup completed');
    }


}
