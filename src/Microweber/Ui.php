<?php
namespace Microweber;
class Ui
{


    var $admin_menu = array();
    var $custom_fields = array();

    function __construct()
    {
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