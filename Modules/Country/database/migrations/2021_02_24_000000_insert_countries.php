<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
                $findCountry = \Modules\Country\Models\Country::where('code', $country[0])->first();
                if (!$findCountry) {
                    $newCountry = new \Modules\Country\Models\Country();
                    $newCountry->code = $country[0];
                    $newCountry->name = $country[1];
                    $newCountry->phonecode = $country[2];
                    $newCountry->save();
                }
            }
        }
    }
};
