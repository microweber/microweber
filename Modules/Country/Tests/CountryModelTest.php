<?php
namespace Modules\Country\Tests;

use PHPUnit\Framework\Attributes\Test;

use Tests\TestCase;
use Modules\Country\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CountryModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]

    public function it_country_model_can_be_created(): void {
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

    #[Test]

    public function it_countries_table_migration(): void {
        $this->artisan('migrate');

        $this->assertTrue(\Schema::hasTable('countries'));
        $this->assertTrue(\Schema::hasColumn('countries', 'code'));
        $this->assertTrue(\Schema::hasColumn('countries', 'name'));
        $this->assertTrue(\Schema::hasColumn('countries', 'phonecode'));
    }
}
