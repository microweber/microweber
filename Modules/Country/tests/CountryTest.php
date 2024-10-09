<?php
namespace Modules\Country\Tests;

use Tests\TestCase;
use Modules\Country\Support\CountriesHelper;

class CountryTest extends TestCase
{
    public function testCountriesList()
    {
        $countries = CountriesHelper::countriesList();
        $this->assertIsArray($countries);
        $this->assertNotEmpty($countries);
        $this->assertContains('Afghanistan', $countries);
    }

    public function testCountriesListFromJson()
    {
        $countries = CountriesHelper::countriesListFromJson();
        $this->assertIsArray($countries);
        $this->assertNotEmpty($countries);
        $this->assertArrayHasKey('AF', $countries);
        $this->assertEquals('Afghanistan', $countries['AF']);
    }
}
