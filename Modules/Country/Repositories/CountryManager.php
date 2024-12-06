<?php

namespace Modules\Country\Repositories;


class CountryManager
{
    public function getCountriesWithCode()
    {
        return \Modules\Country\Support\CountriesHelper::countriesListFromJson();
    }

    public function getCountries()
    {

        $vals = $this->getCountriesWithCode();
        $countries = [];
        foreach ($vals as $key => $val) {
            $countries[$val] = $val;
        }
        return $countries;

    }

}
