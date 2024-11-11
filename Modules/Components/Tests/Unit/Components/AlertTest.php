<?php

namespace Modules\Components\Tests\Unit\Components;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class AlertTest extends TestCase
{
 
    public function testRendersAlertComponentWithDefaultProperties()
    {
        $view = Blade::render('<x-alert>Your alert message!</x-alert>');

        $this->assertStringContainsString('alert alert-primary', $view);
        $this->assertStringContainsString('Your alert message!', $view);
        $this->assertStringNotContainsString('alert-dismissible', $view);
    }

    public function testRendersAlertComponentWithDismissibleProperty()
    {
        $view = Blade::render('<x-alert dismissible>Your alert message!</x-alert>');

        $this->assertStringContainsString('alert alert-primary alert-dismissible fade show', $view);
        $this->assertStringContainsString('Your alert message!', $view);
        $this->assertStringContainsString('btn-close', $view);
    }

    public function testRendersAlertComponentWithCustomType()
    {
        $view = Blade::render('<x-alert type="danger">Danger alert!</x-alert>');

        $this->assertStringContainsString('alert alert-danger', $view);
        $this->assertStringContainsString('Danger alert!', $view);
    }

    public function testRendersACardWithDarkTheme()
    {
        $bladeString = '<x-card theme="dark"></x-card>';
        $output = Blade::render($bladeString);
        $this->assertStringContainsString('bg-dark text-white', $output);
    }
}
