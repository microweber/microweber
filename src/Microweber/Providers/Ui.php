<?php

namespace Microweber\Providers;


class Ui {


    public $custom_fields = array();
    public $admin_logo = '';
    public $admin_logo_login = '';
    public $admin_logo_login_link = false;
    public $logo_live_edit = '';
    public $brand_name = 'Microweber';
    public $powered_by_link = false;
    public $disable_marketplace = false;
    public $marketplace_provider_id = false;
    public $marketplace_access_code = false;
    public $enable_service_links = true;

    public $modules_ui = array();

    function __construct() {
        $this->admin_logo_login = mw_includes_url() . 'images/logo-login.svg';
        $this->defaults();
    }


    function defaults() {
        $btn = array();
        $btn['content_type'] = 'page';
        $btn['title'] = _e("Page", true);
        $btn['class'] = 'mw-icon-page';
        $this->module('content.create.menu', $btn);

        $btn = array();
        $btn['content_type'] = 'post';
        $btn['title'] = _e("Post", true);
        $btn['class'] = 'mw-icon-post';
        $this->module('content.create.menu', $btn);

        $btn = array();
        $btn['content_type'] = 'category';
        $btn['title'] = _e("Category", true);
        $btn['class'] = 'mw-icon-category';
        $this->module('content.create.menu', $btn);


        $notif_count = mw()->notifications_manager->get('is_read=0&count=1');
        $notif_count_html = false;
        if (intval($notif_count) > 0){
            $notif_count_html = '<sup class="mw-notification-count">' . $notif_count . '</sup>';
        }
        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'admin__notifications';
        $admin_dashboard_btn['text'] = _e("Notifications", true) . $notif_count_html;
        $admin_dashboard_btn['icon_class'] = 'mw-icon-notification';
        $this->module('admin.dashboard.menu', $admin_dashboard_btn);

        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'content';
        $admin_dashboard_btn['text'] = _e("Manage Website", true);
        $admin_dashboard_btn['icon_class'] = 'mw-icon-website';
        $this->module('admin.dashboard.menu.second', $admin_dashboard_btn);

        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'modules';
        $admin_dashboard_btn['text'] = _e("Manage Modules", true);
        $admin_dashboard_btn['icon_class'] = 'mw-icon-module';
        $this->module('admin.dashboard.menu.second', $admin_dashboard_btn);

        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'files';
        $admin_dashboard_btn['text'] = _e("File Manager", true);
        $admin_dashboard_btn['icon_class'] = 'mw-icon-upload';
        $this->module('admin.dashboard.menu.second', $admin_dashboard_btn);


        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'upgrades';
        $admin_dashboard_btn['text'] = _e("Upgrades", true);
        $admin_dashboard_btn['icon_class'] = 'mw-icon-market';
        $this->module('admin.dashboard.menu.third', $admin_dashboard_btn);

        $notif_count = 0;
        $notif_count_html = false;
        if (intval($notif_count) > 0){
            $notif_count_html = '<sup class="mw-notification-count">' . $notif_count . '</sup>';
        }
        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'updates';
        $admin_dashboard_btn['text'] = _e("Updates", true) . $notif_count_html;
        $admin_dashboard_btn['icon_class'] = 'mw-icon-updates';
        $this->module('admin.dashboard.menu.third', $admin_dashboard_btn);

        $admin_dashboard_btn = array();
        $admin_dashboard_btn['link'] = 'https://microweber.com/contact-us';
        $admin_dashboard_btn['text'] = _e("Suggest a feature", true);
        $admin_dashboard_btn['icon_class'] = 'mw-icon-suggest';
        $this->module('admin.dashboard.menu.third', $admin_dashboard_btn);


        $fields = array(
            "price"    => "Price",
            "text"     => "Text Field",
            "radio"    => "Single Choice",
            "dropdown" => "Dropdown",
            "checkbox" => "Multiple choices",
            "number"   => "Number",

            "phone"    => "Phone",
            "site"     => "Web Site",
            "email"    => "E-mail",
            "address"  => "Address",
            "date"     => "Date",
            "upload"   => "File Upload",
            "property" => "Property"

        );

        $this->custom_fields = $fields;

    }


    public function module($name = false, $arr = false) {
        if (!$name){
            return;
        }
        if (!isset($this->modules_ui[ $name ])){
            $this->modules_ui[ $name ] = array();
        }
        if ($arr!=false and !empty($arr)){
            array_push($this->modules_ui[ $name ], $arr);
        } else {
            $this->modules_ui[ $name ] = array_unique($this->modules_ui[ $name ], SORT_REGULAR);
        }

        return $this->modules_ui[ $name ];
    }

    public function brand_name() {
        return $this->brand_name;
    }

    public function live_edit_logo() {
        return $this->logo_live_edit;
    }

    public function admin_logo_login() {
        return $this->admin_logo_login;
    }

    public function admin_logo() {
        return $this->admin_logo;
    }

    function create_content_menu() {
        return $this->create_content_menu;
    }

    function custom_fields() {
        return $this->custom_fields;
    }


    function powered_by_link() {
        $link = '<a href="https://microweber.com/" title="Create free Website &amp; Online Shop">Create Website</a> with <a href="https://microweber.com" target="_blank" title="Microweber CMS">Microweber</a>';
        if ($this->powered_by_link!=false){
            $link = $this->powered_by_link;
        }

        return $link;
    }

}