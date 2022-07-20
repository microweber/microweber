<?php

namespace MicroweberPackages\App\Managers;

use Illuminate\Support\Facades\Auth;

use MicroweberPackages\Notification\Models\Notification;
use MicroweberPackages\Notification\Notifications\LegacyNotification;
use MicroweberPackages\User\Models\User;
use Notifications;

class NotificationsManager
{
    public $app;
    public $table = 'notifications';

    public function __construct($app = null)
    {
        if (defined('INI_SYSTEM_CHECK_DISABLED') == false) {
            define('INI_SYSTEM_CHECK_DISABLED', ini_get('disable_functions'));
        }

        if (!is_object($this->app)) {
            if (is_object($app)) {
                $this->app = $app;
            } else {
                $this->app = mw();
            }
        }
    }

    /**
     * @deprecated
     *
     */
    public function read($id)
    {
       return [];

        if (defined('MW_API_CALL')) {
            $is_admin = $this->app->user_manager->is_admin();
            if ($is_admin == false) {
                return array('error' => 'You must be logged in as admin to perform: ' . __CLASS__ . '->' . __FUNCTION__);
            }
        }

        if (is_array($id)) {
            $id = array_pop($id);
        }

        $params = array();
        $params['id'] = trim($id);
        $params['one'] = true;

        $get = $this->get($params);

        if ($get) {
            $save = array();
            $save['id'] = $get['id'];
            $save['is_read'] = 1;
            $table = $this->table;
            $data = $this->app->database_manager->save($table, $save);
            $this->app->cache_manager->delete('notifications' . DIRECTORY_SEPARATOR . $data);
            $this->app->cache_manager->delete('notifications' . DIRECTORY_SEPARATOR . 'global');
        }

        return $get;
    }
    /**
     * @deprecated
     *
     */
    public function read_selected($params) {
        return [];
    	$ids = explode(',', $params['ids']);

    	if (!empty($ids)) {
    		foreach ($ids as $id) {
    			$this->read($id);
    		}
    	}

    }
    /**
     * @deprecated
     *
     */
    public function mark_as_read($module)
    {
        return [];
        if (($module) != false and $module != '') {
            $table = $this->table;
            $get_params = array();
            $get_params['table'] = $table;
            $get_params['is_read'] = 0;
            $get_params['fields'] = 'id';
            if ($module != 'all') {
                $get_params['module'] = $this->app->database_manager->escape_string($module);
            }
            $data = $this->get($get_params);

            if (is_array($data)) {
                foreach ($data as $value) {
                    $save['is_read'] = 1;
                    $save['id'] = $value['id'];
                    $save['table'] = 'notifications';
                    $this->app->database_manager->save('notifications', $save);
                }
            }

            $this->app->cache_manager->delete('notifications' . DIRECTORY_SEPARATOR . 'global');
            $this->app->cache_manager->delete('notifications');

            return $data;
        }
    }
    /**
     * @deprecated
     *
     */
    public function reset($id = false)
    {
        return [];
    	if ($id) {
    		$data = $this->get('is_read=1&no_cache=true&id=' . $id);
    	} else {
    		$data = $this->get('is_read=1&no_cache=true');
    	}

    	if (is_array($data)) {
			foreach ($data as $value) {
				$save = array();
				$save['is_read'] = 0;
				$save['id'] = $value['id'];
				$save['table'] = 'notifications';
				$this->app->database_manager->save('notifications', $save);
    		}
    	}

    	$this->app->cache_manager->delete('notifications' . DIRECTORY_SEPARATOR . 'global');
    	$this->app->cache_manager->delete('notifications');

    	return $data;
    }
    /**
     * @deprecated
     *
     */
    public function reset_selected($params) {
        return [];
    	$ids = explode(',', $params['ids']);

    	if (!empty($ids)) {
    		foreach ($ids as $id) {
    			$this->reset($id);
    		}
    	}

    }

    public function get_unread_count()
    {
        return Notification::count();
    }

    /**
     * @deprecated
     *
     */
    public function mark_all_as_read($params = false)
    {
        return [];
        $is_admin = $this->app->user_manager->is_admin();
        if (defined('MW_API_CALL') and $is_admin == false) {
            return array('error' => 'You must be logged in as admin to perform: ' . __CLASS__ . '->' . __FUNCTION__);
        }


        $params2 = array();

        if (is_string($params)) {
            $params = parse_str($params, $params2);
            $params = $params2;
        }

        $upd = $this->app->database_manager->table($this->table)
            ->where('is_read', '=', 0);

        if (isset($params['rel_type'])) {
            $upd = $upd->where('rel_type', $params['rel_type']);
        }
        if (isset($params['rel_id'])) {
            $upd = $upd->where('rel_id', $params['rel_id']);
        }

        $upd = $upd->update(['is_read' => 1]);
      //  $this->app->cache_manager->delete('notifications' . DIRECTORY_SEPARATOR . 'global');
        $this->app->cache_manager->delete('notifications');

        return true;
    }

    /**
     * @deprecated
     *
     */
    public function delete($id)
    {
        return [];
        $is_admin = $this->app->user_manager->is_admin();
        if (defined('MW_API_CALL') and $is_admin == false) {
            return array('error' => 'You must be logged in as admin to perform: ' . __CLASS__ . '->' . __FUNCTION__);
        }
        if (is_array($id)) {
            $id = array_pop($id);
        }

        $table = $this->table;
        if ($id == 'all') {
            $this->app->database_manager->table($table)->delete();
        } else {
            $this->app->database_manager->delete_by_id($table, intval($id), $field_name = 'id');
        }

        $this->app->cache_manager->delete('notifications' . DIRECTORY_SEPARATOR . intval($id));
        $this->app->cache_manager->delete('notifications' . DIRECTORY_SEPARATOR . 'global');

        return true;
    }
    /**
     * @deprecated
     *
     */
    public function delete_selected($params) {
        return [];
    	$ids = explode(',', $params['ids']);

    	if (!empty($ids)) {
    		foreach ($ids as $id) {
    			$this->delete($id);
    		}
    	}

    }
    /**
     * @deprecated
     *
     */
    public function delete_for_module($module)
    {
        return [];
        if (($module) != false and $module != '') {
            $table = $this->table;
            $get_params = array();
            $get_params['table'] = 'notifications';
            $get_params['fields'] = 'id';
            $get_params['module'] = $this->app->database_manager->escape_string($module);
            $data = $this->get($get_params);
            if (is_array($data)) {
                $ids = $this->app->format->array_values($data);
                foreach ($ids as $remove) {
                    $this->app->database_manager->table($table)->where('id', '=', $remove)->delete();
                }
            }
            $this->app->cache_manager->delete('notifications' . DIRECTORY_SEPARATOR . 'global');

            return true;
        }
    }


    /**
     * @deprecated
     *
     */

    public function save($params)
    {
        $notifyAdmin = User::where('is_admin', 1)->first();
        if ($notifyAdmin) {
            \Illuminate\Support\Facades\Notification::send($notifyAdmin, new LegacyNotification($params));
        }


        return [];
        $params = parse_params($params);

        $table = $table_orig = $this->table;


        if (!isset($params['rel_type']) or !isset($params['rel_id'])) {
            return 'Error: invalid data you must send rel and rel_id as params for $this->save function';
        }

        $old = date('Y-m-d H:i:s', strtotime('-30 days'));

        $this->app->database_manager->table($table)->where('created_at', '<', $old)->delete();


        if (isset($params['replace'])) {
            if (isset($params['module']) and isset($params['rel_type']) and isset($params['rel_id'])) {
                unset($params['replace']);
                $rel1 = $this->app->database_manager->escape_string($params['rel_type']);
                $module1 = $this->app->database_manager->escape_string($params['module']);
                $rel_id1 = $this->app->database_manager->escape_string($params['rel_id']);
                $this->app->database_manager->table($table)
                    ->where('rel_type', '=', $rel1)
                    ->where('rel_id', '=', $rel_id1)
                    ->where('module', '=', $module1)
                    ->delete();
            }
        }
        if (!isset($params['is_read'])) {
            $params['is_read'] = 0;
        }

        if (isset($params['notification_data'])) {
            $params['notification_data'] = ($params['notification_data']);
        }


        $this->app->cache_manager->delete('notifications' . DIRECTORY_SEPARATOR . 'global');

        $data = $this->app->database_manager->save($table_orig, $params);

        return $data;
    }

    /**
     * @deprecated
     *
     */
    public function get_by_id($id)
    {
        return [];

        $params = array();

        if ($id != false) {
            $params['id'] = $id;
            $params['one'] = true;

            $get = $this->get($params);

            return $get;
        }
    }

    public function get($params = false)
    {
        $params = parse_params($params);
        if (isset($params['count'])) {
           return $this->get_unread_count($params);
        }


        $readyNotifications = [];

        $admin = Auth::user();
dd(debug_backtrace(1));
        if (isset($admin->unreadNotifications) and !empty($admin->unreadNotifications)) {
            foreach ($admin->unreadNotifications as $notification) {

                if (!class_exists($notification->type)) {
                    continue;
                }

                $messageType = new $notification->type($notification->data);

                if (!method_exists($messageType, 'message')) {
                    continue;
                }

                if (method_exists($messageType, 'setNotification')) {
                    $messageType->setNotification($notification);
                }

                $readyNotifications[] = $messageType->message($notification);
            }
        }
        return $readyNotifications;

/*


        $return = array();
        $is_sys_log = false;
        if (isset($params['id'])) {
            $is_log = substr(strtolower($params['id']), 0, 4);
            if ($is_log == 'log_') {
                $is_sys_log = 1;
                $is_log_id = str_ireplace('log_', '', $params['id']);
                $log_entr = $this->app->log_manager->get_entry_by_id($is_log_id);
                if ($log_entr != false and isset($params['one'])) {
                    return $log_entr;
                } elseif ($log_entr != false) {
                    $return[] = $log_entr;
                }
            }
        }

        if (isset($params['rel'])) {
            $params['rel_type'] = $params['rel'];
        }

        if ($is_sys_log == false) {
            $table = $this->table;
            $params['table'] = $table;
            $params['order_by'] = 'id desc';
            $return = $this->app->database_manager->get($params);

        }


        if ($return and is_array($return)) {
            foreach ($return as $k => $v) {
                if (isset($v['notification_data']) and is_string($v['notification_data'])) {
                    $v['notification_data'] = @json_decode($v['notification_data'], true);
                    $return[$k] = $v;

                }

                if (get_option('skip_saving_emails') == 'y') {
	                if (isset($v['content']) and is_string($v['content'])) {
	                	$return[$k]['content'] = $v['description'] . _e(' check your email adress.', true);
	                }
                }
            }
        }


        return $return;*/
    }
}
