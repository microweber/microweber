<?php

declare(strict_types=1);

namespace MicroweberPackages\View\Tests\Unit;

use MicroweberPackages\View\Tests\TestCase;
use MicroweberPackages\View\ViewComponentName;
use PHPUnit\Framework\Attributes\Test;

class ViewComponentNameTest extends TestCase
{
    #[Test]
    public function stores_name_and_package(): void
    {
        $attr = new ViewComponentName('hero', 'components');
        $this->assertSame('hero', $attr->name);
        $this->assertSame('components', $attr->package);
    }
}
