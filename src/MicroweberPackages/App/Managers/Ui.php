<?php

namespace MicroweberPackages\App\Managers;

class Ui
{
    public $custom_fields = array();
    public $admin_logo = '';
    public $admin_logo_login = '';
    public $admin_logo_login_link = false;
    public $logo_live_edit = '';
    public $brand_name = 'Microweber';
    public $powered_by_link = false;
    public $disable_powered_by_link = false;
    public $disable_marketplace = false;
    public $package_manager_urls = false;
    public $marketplace_provider_id = false;
    public $marketplace_access_code = false;
    public $enable_service_links = true;
    public $custom_support_url = false;
    public $brand_favicon = '';

    public $modules_ui = array();

    public function __construct()
    {
        $this->admin_logo_login = modules_url() . 'microweber/api/libs/mw-ui/assets/img/logo.svg';
        $this->logo_live_edit = modules_url() . 'microweber/api/libs/mw-ui/assets/img/logo-mobile.svg';
        if (mw_is_installed()) {
            $this->defaults();
        }
    }

    public function defaults()
    {
        $btn = array();
        $btn['content_type'] = 'page';
        $btn['title'] = _e('Page', true);
        $btn['class'] = 'mai-page';
        $this->module('content.create.menu', $btn);

        $btn = array();
        $btn['content_type'] = 'post';
        $btn['title'] = _e('Post', true);
        $btn['class'] = 'mai-post';
        $this->module('content.create.menu', $btn);

        $btn = array();
        $btn['content_type'] = 'category';
        $btn['title'] = _e('Category', true);
        $btn['class'] = 'mai-category';
        $this->module('content.create.menu', $btn);

//        $notif_count = mw()->notifications_manager->get_unread_count();
//        $notif_count_html = false;
//        if (intval($notif_count) > 0) {
//            $notif_count_html = '<span class="badge badge-danger badge-sm badge-pill d-inline-block ml-2">' . $notif_count . '</span>';
//        }
//        $admin_dashboard_btn = array();
//        $admin_dashboard_btn['view'] = 'admin__notifications';
//        $admin_dashboard_btn['text'] = _e('Notifications', true) . $notif_count_html;
//        $admin_dashboard_btn['icon_class'] = 'mdi mdi-bell';
//        $this->module('admin.dashboard.menu', $admin_dashboard_btn);

        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'content';
        $admin_dashboard_btn['text'] = _e('Manage Website', true);
        $admin_dashboard_btn['icon_class'] = 'mdi mdi-earth';
        $this->module('admin.dashboard.menu.second', $admin_dashboard_btn);

        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'modules';
        $admin_dashboard_btn['text'] = _e('Manage Modules', true);
        $admin_dashboard_btn['icon_class'] = 'mdi mdi-view-grid-plus';
        $this->module('admin.dashboard.menu.second', $admin_dashboard_btn);

        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'files';
        $admin_dashboard_btn['text'] = _e('File Manager', true);
        $admin_dashboard_btn['icon_class'] = 'mdi mdi-file-check';
        $this->module('admin.dashboard.menu.second', $admin_dashboard_btn);

        /*$admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'upgrades';
        $admin_dashboard_btn['text'] = _e('Upgrades', true);
        $admin_dashboard_btn['icon_class'] = 'mw-icon-market';
        $this->module('admin.dashboard.menu.third', $admin_dashboard_btn);*/

        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'marketplace';
        $admin_dashboard_btn['text'] = _e('Go to Marketplace', true);
        $admin_dashboard_btn['icon_class'] = 'mdi mdi-fruit-cherries';
        $this->module('admin.dashboard.menu.third', $admin_dashboard_btn);


        $notif_count = 0;

        if (defined('MW_BACKEND') and MW_BACKEND) {
            $notif_count_updates_data = mw()->update->get_updates_notifications('limit=1');

            if (isset($notif_count_updates_data[0])
                and isset($notif_count_updates_data[0]['notification_data'])
                and !empty($notif_count_updates_data[0]['notification_data'])
            ) {
                $notif_data = $notif_count_updates_data[0]['notification_data'];

                if (isset($notif_data['core_update'])) {
                    $notif_count = $notif_count + 1;
                }
                $others_updates = array('modules', 'templates', 'elements');
                foreach ($others_updates as $item) {
                    if (isset($notif_data[$item]) and is_arr($notif_data[$item])) {
                        $notif_count = $notif_count + count($notif_data[$item]);
                    }
                }
//            if(isset($notif_data['popup']) and $notif_data['popup']) {
//                event_bind(
//                    'mw.admin.dashboard.main', function () use ($notif_data) {
//                    print load_module('updates/updates_popup',$notif_data);
//
//                 }
//                );
//            }

            }
        }


        $notif_count_html = false;
        if (intval($notif_count) > 0) {
            $notif_count_html = '<span class="badge badge-danger badge-sm badge-pill d-inline-block ml-2">' . $notif_count . '</span>';
        }
        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'updates';
        $admin_dashboard_btn['text'] = _e('Updates', true) . $notif_count_html;
        $admin_dashboard_btn['icon_class'] = 'mdi mdi-update';
        $this->module('admin.dashboard.menu.third', $admin_dashboard_btn);

        $admin_dashboard_btn = array();
        $admin_dashboard_btn['link'] = 'https://microweber.com/contact-us';
        $admin_dashboard_btn['text'] = _e('Suggest a feature', true);
        $admin_dashboard_btn['icon_class'] = 'mdi mdi-penguin';
        $this->module('admin.dashboard.menu.third', $admin_dashboard_btn);

        $fields = array(
            'price' => 'Price',
            'text' => 'Text Field',
            //'button' => 'Button',
            'radio' => 'Single Choice',
            'dropdown' => 'Dropdown',
            'checkbox' => 'Multiple choices',
            'number' => 'Number',
            'phone' => 'Phone',
            'site' => 'Web Site',
            'email' => 'E-mail',
            'address' => 'Address',
            'country' => 'Country',
            'date' => 'Date',
            'time' => 'Time',
            'upload' => 'File Upload',
            'property' => 'Property',
            'breakline' => 'Break Line',
            'hidden' => 'Hidden Field',
        );

        $this->custom_fields = $fields;
    }

    public function module($name = false, $arr = false)
    {
        if (!$name) {
            return;
        }
        if (!isset($this->modules_ui[$name])) {
            $this->modules_ui[$name] = array();
        }
        if ($arr != false and !empty($arr)) {
            array_push($this->modules_ui[$name], $arr);
        } else {
            $this->modules_ui[$name] = array_unique($this->modules_ui[$name], SORT_REGULAR);
        }

        return $this->modules_ui[$name];
    }

    public function brand_name()
    {
        return $this->brand_name;
    }

    public function live_edit_logo()
    {
        return $this->logo_live_edit;
    }

    public function admin_logo_login()
    {
        return $this->admin_logo_login;
    }

    public function admin_logo()
    {
        return $this->admin_logo;
    }


    public function brand_favicon()
    {
        return $this->brand_favicon;
    }

    public function create_content_menu()
    {
        return $this->create_content_menu;
    }

    public function service_links_enabled()
    {
        return intval($this->enable_service_links);
    }

    public function powered_by_link_enabled()
    {
        if (intval($this->disable_powered_by_link) != 0) {
            return false;
        }
        return true;
    }

    public function custom_fields()
    {
        return $this->custom_fields;
    }

    public function powered_by_link()
    {
        if ($this->disable_powered_by_link != false) {
            return;
        }
        $link = '<span class="mw-powered-by"><a href="https://microweber.org/" title="Website Builder">Website Builder</a> <span>by</span> <a href="https://microweber.org" target="_blank" title="Microweber CMS">Microweber</a></span>';
        if ($this->powered_by_link != false) {
            $link = $this->powered_by_link;
        }

        return $link;
    }
}