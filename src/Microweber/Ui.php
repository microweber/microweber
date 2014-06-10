<?php
namespace Microweber;
class Ui
{


    var $admin_menu = array();
    var $create_content_menu = array(
        "post" => "Post",
        "page" => "Page",
        "category" => "Category"
    );
    var $custom_fields = array();
    var $admin_logo = '';
    var $admin_logo_login = '';
    var $logo_live_edit = '';
    var $brand_name = 'Microweber';
    var $powered_by_link = false;

    function __construct()
    {

        $this->admin_logo = MW_INCLUDES_URL . 'img/logo_admin.png';
        $this->logo_live_edit = MW_INCLUDES_URL . 'img/logo_admin.png';
        $this->admin_logo_login = MW_INCLUDES_URL . 'img/sign_logo.png';
        $this->set_default_custom_fields();
        $this->set_default_custom_fields();
    }

    function set_default_custom_fields()
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
//        foreach ($fields as $item) {
//            $this->add_custom_field($item);
//        }
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
        $this->admin_menu = array_merge($this->admin_menu, $arr);
        return $this->admin_menu;
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