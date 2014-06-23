<?php
namespace Microweber;
class Ui
{


    public $admin_menu = array();
    public $create_content_menu = array(
        "post" => "Post",
        "page" => "Page",
        "category" => "Category"
    );
    public $admin_dashboard_menu = array();
    public $custom_fields = array();
    public $admin_logo = '';
    public $admin_logo_login = '';
    public $logo_live_edit = '';
    public $brand_name = 'Microweber';
    public $powered_by_link = false;
    public $admin_content_edit = array();
    public $admin_content_edit_text = array();

    function __construct()
    {

        $this->admin_logo = MW_INCLUDES_URL . 'img/logo_admin.png';
        $this->logo_live_edit = MW_INCLUDES_URL . 'img/logo_admin.png';
        $this->admin_logo_login = MW_INCLUDES_URL . 'img/sign_logo.png';

        $this->set_default();
    }

    function set_default()
    {

        $fields = array(
            "text" => "Text Field",
            "number" => "Number",
            "price" => "Price",
            "phone" => "Phone",
            "site" => "Web Site",
            "email" => "E-mail",
            "address" => "Address",
            "date" => "Date",
            "upload" => "File Upload",
            "radio" => "Single Choice",
            "dropdown" => "Dropdown",
            "checkbox" => "Multiple choices"
        );

        $this->custom_fields = $fields;


        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'content';
        $admin_dashboard_btn['text'] = _e("Manage Website", true);
        $admin_dashboard_btn['icon_class'] = 'mw-icon-website';
        $this->admin_dashboard_menu($admin_dashboard_btn);

        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'modules';
        $admin_dashboard_btn['text'] = _e("Manage Modules", true);
        $admin_dashboard_btn['icon_class'] = 'mw-icon-module';
        $this->admin_dashboard_menu($admin_dashboard_btn);

        $admin_dashboard_btn = array();
        $admin_dashboard_btn['view'] = 'files';
        $admin_dashboard_btn['text'] = _e("File Manager", true);
        $admin_dashboard_btn['icon_class'] = 'mw-icon-upload';
        $this->admin_dashboard_menu($admin_dashboard_btn);



    }

    function admin_dashboard_menu($arr = false)
    {

        if ($arr != false) {
            array_push($this->admin_dashboard_menu, $arr);
        }
        return $this->admin_dashboard_menu;
    }

    public function admin($menu_array)
    {


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

    public function admin_menu()
    {
        return $this->admin_menu;
    }

    function add_admin_menu($arr)
    {
        if ($arr != false) {
            $this->admin_menu = array_merge($this->admin_menu, $arr);
        }

        return $this->admin_menu;
    }

    function admin_content_edit($arr = false)
    {
        if ($arr != false) {
            array_push($this->admin_content_edit, $arr);
        }
        return $this->admin_content_edit;
    }

    function admin_content_edit_text($arr = false)
    {
        if ($arr != false) {
            array_push($this->admin_content_edit_text, $arr);
        }
        return $this->admin_content_edit_text;
    }

    function create_content_menu()
    {
        return $this->create_content_menu;
    }

    function add_create_content_menu($arr)
    {
        $this->create_content_menu = array_merge($this->create_content_menu, $arr);
        return $this->create_content_menu;
    }

    function custom_fields()
    {
        return $this->custom_fields;
    }

    function add_custom_field($arr)
    {
        $this->custom_fields = array_merge($this->custom_fields, $arr);
        return $this->custom_fields;
    }

    function powered_by_link()
    {
        $link = '<a href="https://microweber.com/" title="Create free Website &amp; Online Shop">Create Website</a> with <a href="https://microweber.com" target="_blank" title="Microweber CMS">Microweber</a>';
        if ($this->powered_by_link != false) {
            $link = $this->powered_by_link;
        }
        return $link;
    }

}