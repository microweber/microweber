<?php

namespace MicroweberPackages\LiveEdit\tests;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;
use MicroweberPackages\LiveEdit\Http\Livewire\ItemsEditor\ModuleSettingsItemsEditorEditItemComponent;
use MicroweberPackages\Option\Models\Option;
use MicroweberPackages\User\Models\User;

// php artisan test --filter ModuleSettingsItemsEditorTest

class ModuleSettingsItemsEditorTest extends TestCase
{
    #[Test]
    public function it_items_editor(): void {
        if (!defined('MW_SITE_URL')) {
            define('MW_SITE_URL', 'http://localhost/');
        }

        Option::truncate();

        $user = User::where('is_admin', '=', '1')->first();
        Auth::login($user);

        $instance = Livewire::test(ModuleSettingsItemsEditorEditItemComponent::class)
            ->set('moduleId', '1')
            ->set('moduleType', 'test')
            ->set('itemState', [
                'itemId' => '1',
                'name' => 'test',
                'value' => 'testValue',
                'url' => site_url() . 'many-other-things/new-url'
            ])
            ->call('submit')
            ->assertDispatched('onItemChanged');

        $this->assertNotNull($instance);

        $findOption = DB::table('options')
            ->where('option_group', 1)
            ->where('option_key', 'settings')->first();

        $getOption = json_decode($findOption->option_value, true);

        foreach ($getOption as $item) {
            foreach ($item as $optionKey => $optionValue) {
                if (str_contains($optionValue, site_url())) {
                    $this->assertTrue(false, 'Option has not replaced site urls.');
                }
            }
        }

    }
}
