<?php
namespace Modules\Country\tests;

use Tests\TestCase;
use Modules\Country\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountryModelTest extends TestCase
{
    use RefreshDatabase;

    public function testCountryModelCanBeCreated()
    {
        $country = Country::create([
            'code' => 'US',
            'name' => 'United States',
            'phonecode' => 1,
        ]);

        $this->assertDatabaseHas('countries', [
            'code' => 'US',
            'name' => 'United States',
            'phonecode' => 1,
        ]);
    }

    public function testCountriesTableMigration()
    {
        $this->artisan('migrate');

        $this->assertTrue(\Schema::hasTable('countries'));
        $this->assertTrue(\Schema::hasColumn('countries', 'code'));
        $this->assertTrue(\Schema::hasColumn('countries', 'name'));
        $this->assertTrue(\Schema::hasColumn('countries', 'phonecode'));
    }
}
