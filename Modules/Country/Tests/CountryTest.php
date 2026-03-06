<?php
namespace Modules\Country\Tests;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use Modules\Country\Support\CountriesHelper;

class CountryTest extends TestCase
{
    #[Test]
    public function it_countries_list(): void {
        $countries = CountriesHelper::countriesList();
        $this->assertIsArray($countries);
        $this->assertNotEmpty($countries);
        $this->assertContains('Afghanistan', $countries);
    }

    #[Test]

    public function it_countries_list_from_json(): void {
        $countries = CountriesHelper::countriesListFromJson();
        $this->assertIsArray($countries);
        $this->assertNotEmpty($countries);
        $this->assertArrayHasKey('AF', $countries);
        $this->assertEquals('Afghanistan', $countries['AF']);
    }
}
