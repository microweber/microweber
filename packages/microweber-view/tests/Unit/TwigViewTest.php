<?php

declare(strict_types=1);

namespace MicroweberPackages\View\Tests\Unit;

use MicroweberPackages\View\Facades\TwigView as TwigViewFacade;
use MicroweberPackages\View\Tests\TestCase;
use MicroweberPackages\View\TwigView;
use PHPUnit\Framework\Attributes\Test;

class TwigViewTest extends TestCase
{
    #[Test]
    public function renders_twig_string(): void
    {
        $twig = new TwigView();
        $html = $twig->render('Hello {{ name }}', ['name' => 'Twig']);

        $this->assertSame('Hello Twig', $html);
    }

    #[Test]
    public function resolves_from_container(): void
    {
        $this->assertInstanceOf(TwigView::class, app(TwigView::class));
        $this->assertSame(app(TwigView::class), TwigViewFacade::getFacadeRoot());
    }

    #[Test]
    public function supports_filters(): void
    {
        $twig = app(TwigView::class);
        $html = $twig->render('{{ name|upper }}', ['name' => 'abc']);

        $this->assertSame('ABC', $html);
    }
}
