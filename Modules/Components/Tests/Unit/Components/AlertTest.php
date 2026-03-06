<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class AlertTest extends TestCase
{
 
    #[Test]
 
    public function it_renders_alert_component_with_default_properties(): void {
        $view = Blade::render('<x-alert>Your alert message!</x-alert>');

        $this->assertStringContainsString('alert alert-primary', $view);
        $this->assertStringContainsString('Your alert message!', $view);
        $this->assertStringNotContainsString('alert-dismissible', $view);
    }

    #[Test]

    public function it_renders_alert_component_with_dismissible_property(): void {
        $view = Blade::render('<x-alert dismissible>Your alert message!</x-alert>');

        $this->assertStringContainsString('alert alert-primary alert-dismissible fade show', $view);
        $this->assertStringContainsString('Your alert message!', $view);
        $this->assertStringContainsString('btn-close', $view);
    }

    #[Test]

    public function it_renders_alert_component_with_custom_type(): void {
        $view = Blade::render('<x-alert type="danger">Danger alert!</x-alert>');

        $this->assertStringContainsString('alert alert-danger', $view);
        $this->assertStringContainsString('Danger alert!', $view);
    }

    #[Test]

    public function it_renders_a_card_with_dark_theme(): void {
        $bladeString = '<x-card theme="dark"></x-card>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('bg-dark text-white', $output);
    }
}
