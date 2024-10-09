<?php



if (!function_exists('countries_list')) {
    function countries_list($param = false)
    {
        return \Modules\Country\Support\CountriesHelper::countriesList($param);
    }
}
