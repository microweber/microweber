<?php
namespace Microweber;
class Ui
{


    var $admin_menu = array();
    var $custom_fields = array();
    var $admin_logo = '';
    var $logo_live_edit = '';
    var $brand_name = 'Microweber';

    var $powered_by = 'Create a website';
    var $powered_by_link = 'http://microweber.com/';
    function __construct()
    {

        //set admin logo
        $this->admin_logo = MW_INCLUDES_URL . 'img/logo_admin.png';
        $this->logo_live_edit = MW_INCLUDES_URL . 'img/logo_admin.png';

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

    function custom_fields()
    {
        return $this->custom_fields;
    }

    function add_custom_field($arr)
    {
        $this->custom_fields = array_merge($this->custom_fields, $arr);
        return $this->custom_fields;
    }


}