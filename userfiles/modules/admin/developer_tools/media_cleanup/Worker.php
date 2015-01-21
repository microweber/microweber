<?php


namespace admin\developer_tools\media_cleanup;

use Illuminate\Cache\tags;
use Illuminate\Support\Facades\Cache;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;


api_expose_admin('admin/developer_tools/media_cleanup/Worker/create_batch');


class Worker
{
    public $app;
    public $cache_group = 'media_cleanup_worker';

    function __construct($app = null)
    {
        if (is_object($app)) {
            $this->app = $app;
        } else {
            $this->app = mw();
        }
    }

    public function create_batch()
    {
        $work = array();
        $work['total'] = mw()->media_manager->get_all('count=1');
        $work['remaining'] = mw()->media_manager->get_all('count=1');

        mw()->cache_manager->save($work, 'create_batch', $this->cache_group);
        return $work;

    }

    public function run()
    {
        $removed = 0;
        $value = mw()->cache_manager->get('create_batch', $this->cache_group);
        if (isset($value['total']) and $value['total'] > 0) {
            if (isset($value['remaining']) and $value['remaining'] > 0) {
                $batch = mw()->media_manager->get_all('limit=30000');
                if ($batch) {
                    foreach ($batch as $k => $v) {

                        if (isset($v['id']) and isset($v['filename']) and $v['filename'] != false) {

                            $process = false;
                            if (stristr($v['filename'], '{SITE_URL}')) {
                                $process = true;

                            } else if (stristr($v['filename'], site_url())) {
                                $process = true;
                            }
                            if ($process) {
                                $v['filename'] = str_ireplace('{SITE_URL}', '', $v['filename']);
                                $v['filename'] = str_ireplace(site_url(), '', $v['filename']);
                                $is_file = false;

                                $file1 = normalize_path(public_path() . DS . $v['filename'], false);
                                $file2 = normalize_path(base_path() . DS . $v['filename'], false);
                                $file3 = normalize_path(media_base_path() . DS . $v['filename'], false);
                                $file4 = normalize_path(userfiles_path() . DS . $v['filename'], false);
                                if (is_file($file1)) {
                                    $is_file = true;
                                } elseif (is_file($file2)) {
                                    $is_file = true;
                                } elseif (is_file($file3)) {
                                    $is_file = true;
                                } elseif (is_file($file4)) {
                                    $is_file = true;
                                }
                                if ($is_file == false) {
                                    mw()->media_manager->delete($v['id']);
                                    $removed++;
                                }
                            }
                        }


                    }
                }
            }

        }
        mw()->cache_manager->delete($this->cache_group);
        $resp = array('success' => "Removed " . $removed . ' items');
        return $resp;
    }


}
