<?php

namespace Modules\Country\Repositories;


class CountryManager
{
    public function get_countries()
    {
        return \Modules\Country\Support\CountriesHelper::countriesListFromJson();
    }
}
