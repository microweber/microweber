<?php

namespace Modules\Components\Tests\Unit\Components;

use PHPUnit\Framework\Attributes\Test;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class PricingRowTest extends TestCase
{
    #[Test]
    public function it_renders_a_plan_card_with_name_price_and_features(): void
    {
        $output = Blade::render(
            '<x-pricing-row plan-name="Free" price="$0" period="/mo" :features="[\'10 users\',\'2 GB\']" button-text="Sign up" />'
        );

        $this->assertStringContainsString('class="card', $output);
        $this->assertStringContainsString('pricing-card-title', $output);
        $this->assertStringContainsString('Free', $output);
        $this->assertStringContainsString('$0', $output);
        $this->assertStringContainsString('10 users', $output);
        $this->assertStringContainsString('2 GB', $output);
        $this->assertStringContainsString('Sign up', $output);
    }

    #[Test]
    public function it_applies_the_highlighted_accent(): void
    {
        $output = Blade::render('<x-pricing-row plan-name="Pro" price="$15" :highlighted="true" />');

        $this->assertStringContainsString('border-primary', $output);
        $this->assertStringContainsString('bg-primary', $output);
    }

    #[Test]
    public function it_uses_the_button_style_when_not_highlighted(): void
    {
        $output = Blade::render('<x-pricing-row plan-name="Free" button-style="btn btn-outline-primary" />');

        $this->assertStringContainsString('btn btn-outline-primary', $output);
    }

    #[Test]
    public function it_renders_a_custom_actions_slot_over_the_default_button(): void
    {
        $output = Blade::render(
            '<x-pricing-row plan-name="Pro"><x-slot name="actions"><a class="my-cta">Go</a></x-slot></x-pricing-row>'
        );

        $this->assertStringContainsString('my-cta', $output);
    }
}
