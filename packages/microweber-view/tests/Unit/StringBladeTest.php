<?php

declare(strict_types=1);

namespace MicroweberPackages\View\Tests\Unit;

use MicroweberPackages\View\Facades\StringBlade as StringBladeFacade;
use MicroweberPackages\View\StringBlade;
use MicroweberPackages\View\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class StringBladeTest extends TestCase
{
    #[Test]
    public function renders_blade_string(): void
    {
        $blade = app(StringBlade::class);
        $html = $blade->render('Hello {{ $name }}', ['name' => 'Blade']);

        $this->assertSame('Hello Blade', trim($html));
    }

    #[Test]
    public function compiles_blade_string_to_php(): void
    {
        $blade = app(StringBlade::class);
        $php = $blade->compile('{{ $x }}');

        $this->assertStringContainsString('echo', $php);
        $this->assertStringContainsString('$x', $php);
    }

    #[Test]
    public function resolves_from_container_via_facade(): void
    {
        $this->assertInstanceOf(StringBlade::class, StringBladeFacade::getFacadeRoot());
        $this->assertSame(app(StringBlade::class), StringBladeFacade::getFacadeRoot());
    }
}
