<?php

namespace Modules\Tabs\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Modules\Tabs\Filament\TabsModuleSettings;
use Tests\TestCase;

class TabsModuleSettingsTest extends TestCase
{

    public function testSettingsPageRenders()
    {
        Livewire::test(TabsModuleSettings::class)
            ->assertStatus(200);
    }
}
