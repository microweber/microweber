<?php

namespace Modules\Country\Support;

class CountriesHelper
{
    public static function countriesListFromJson()
    {
         $countries_file = normalize_path(dirname(__DIR__) . '/resources/country.json', false);
        $countries_file = json_decode(file_get_contents($countries_file), true);

        return $countries_file;
    }

    public static function countriesList($full = false)
    {
        static $data = array();

        if (empty($data)) {
            $countries_file_userfiles = normalize_path(userfiles_path() . 'country.csv', false);
            $countries_file = normalize_path(dirname(__DIR__) . '/resources/country.csv', false);

            if (is_file($countries_file_userfiles)) {
                $countries_file = $countries_file_userfiles;
            }

            if (is_file($countries_file)) {
                $data = array_map('str_getcsv', file($countries_file));

                if (isset($data[0])) {
                    unset($data[0]);
                }
            }
        }

        if ($full == false and !empty($data)) {
            $res = array();
            foreach ($data as $item) {
                $res[] = $item[1];
            }

            return $res;
        }

        return $data;
    }
}
