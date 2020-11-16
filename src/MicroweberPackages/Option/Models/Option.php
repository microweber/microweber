<?php
namespace MicroweberPackages\Option\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    public function getValue($key, $group = false)
    {
        if ($group) {
            $value = get_option($key, $group);
        } else {
            $value = get_option($key);
        }

        switch ($value) {
            case "y":
            case "yes":
                $value = true;
                break;
            case "n":
            case "no":
                $value = false;
                break;
        }

        if (is_numeric($value)) {
            $value = intval($value);
        }

        if (is_string($value)) {
            $value = trim($value);
        }

        return $value;
    }

    public function setValue($key, $value, $group = false)
    {
        $saveOption = [];
        $saveOption['option_key'] = $key;
        $saveOption['option_value'] = $value;

        if ($group) {
            $saveOption['option_group'] = $group;
        }

        return save_option($saveOption);
    }

}