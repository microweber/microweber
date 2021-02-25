<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class InsertCountries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('countries')) {

            $data = countries_list(true);

            foreach ($data as $country) {
                $findCountry = \MicroweberPackages\Country\Models\Country::where('code', $country[0])->where('name', $country[1])->first();
                if (!$findCountry) {
                    $newCountry = new \MicroweberPackages\Country\Models\Country();
                    $newCountry->code = $country[0];
                    $newCountry->name = $country[1];
                    $newCountry->phonecode = $country[2];
                    $newCountry->save();
                }
            }
        }
    }
}