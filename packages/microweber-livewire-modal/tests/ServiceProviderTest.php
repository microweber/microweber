<?php

declare(strict_types=1);

namespace MicroweberPackages\LivewireModal\Tests;

use Livewire\Livewire;
use MicroweberPackages\LivewireModal\LivewireModalServiceProvider;
use MicroweberPackages\LivewireModal\Modal;
use PHPUnit\Framework\Attributes\Test;

class ServiceProviderTest extends TestCase
{
    #[Test]
    public function service_provider_is_registered(): void
    {
        $this->assertTrue(
            $this->app->providerIsLoaded(LivewireModalServiceProvider::class)
        );
    }

    #[Test]
    public function config_is_merged(): void
    {
        $this->assertNotNull(config('livewire-modal'));
        $this->assertSame('default', config('livewire-modal.skin'));
        $this->assertTrue(config('livewire-modal.component_defaults.show_close_button'));
        $this->assertTrue(config('livewire-modal.component_defaults.show_backdrop'));
        $this->assertTrue(config('livewire-modal.component_defaults.close_on_click_away'));
        $this->assertTrue(config('livewire-modal.component_defaults.close_on_escape'));
    }

    #[Test]
    public function livewire_aliases_resolve_to_modal_class(): void
    {
        foreach (['microweber-livewire-modal', 'livewire-ui-modal', 'wire-elements-modal'] as $alias) {
            $instance = Livewire::new($alias);
            $this->assertInstanceOf(Modal::class, $instance, "Alias {$alias} should resolve to Modal");
        }
    }

    #[Test]
    public function views_namespace_is_registered(): void
    {
        $this->assertTrue(view()->exists('livewire-modal::modal'));
        $this->assertTrue(view()->exists('livewire-modal::skins.default'));
    }
}
