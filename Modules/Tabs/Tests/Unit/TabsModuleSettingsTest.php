<?php

namespace Modules\Tabs\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;

use Livewire\Livewire;
use Modules\Tabs\Filament\TabsModuleSettings;
use Tests\TestCase;

class TabsModuleSettingsTest extends TestCase
{

    #[Test]

    public function it_settings_page_renders(): void {
        Livewire::test(TabsModuleSettings::class)
            ->assertStatus(200);
    }
}
