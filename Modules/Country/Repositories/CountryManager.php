<?php

namespace Modules\Country\Repositories;


class CountryManager
{
    public function getCountryName($codeOrId): ?string
    {
        if (empty($codeOrId)) {
            return null;
        }

        // Try to find by ID first
        $country = \Modules\Country\Models\Country::where('id', $codeOrId)->first();
        if ($country) {
            return $country->name;
        }

        // Try by name
        $country = \Modules\Country\Models\Country::where('name', $codeOrId)->first();
        if ($country) {
            return $country->name;
        }

        // Try by code
        $country = \Modules\Country\Models\Country::where('code', $codeOrId)->first();
        if ($country) {
            return $country->name;
        }

        // Fallback to the list from JSON
        $countries = $this->getCountriesWithCode();
        foreach ($countries as $key => $name) {
            if ($key === $codeOrId || $name === $codeOrId) {
                return $name;
            }
        }

        return $codeOrId;
    }

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
