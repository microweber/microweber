<?php
namespace MicroweberPackages\Option\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    public function get($key, $group = false)
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

        return $value;
    }

}