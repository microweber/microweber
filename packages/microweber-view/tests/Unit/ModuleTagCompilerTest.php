<?php

declare(strict_types=1);

namespace MicroweberPackages\View\Tests\Unit;

use MicroweberPackages\View\MicroweberModuleTagCompiler;
use MicroweberPackages\View\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ModuleTagCompilerTest extends TestCase
{
    private function compiler(): MicroweberModuleTagCompiler
    {
        return app(MicroweberModuleTagCompiler::class);
    }

    #[Test]
    public function compiles_self_closing_module_tag(): void
    {
        MicroweberModuleTagCompiler::enableModuleProcessing();
        $out = $this->compiler()->compile('<module type="logo" id="header-logo" />');

        $this->assertStringContainsString('@module([', $out);
        $this->assertStringContainsString("'type' => 'logo'", $out);
        $this->assertStringContainsString("'id' => 'header-logo'", $out);
    }

    #[Test]
    public function compiles_empty_attribute_values(): void
    {
        MicroweberModuleTagCompiler::enableModuleProcessing();
        $out = $this->compiler()->compile('<module type="btn" button_size="" />');

        $this->assertStringContainsString('@module([', $out);
        $this->assertStringContainsString("'type' => 'btn'", $out);
    }

    #[Test]
    public function can_disable_processing(): void
    {
        MicroweberModuleTagCompiler::disableModuleProcessing();
        $input = '<module type="logo" />';
        $out = $this->compiler()->compile($input);

        $this->assertSame($input, $out);
        MicroweberModuleTagCompiler::enableModuleProcessing();
    }

    #[Test]
    public function leaves_non_module_html_untouched(): void
    {
        MicroweberModuleTagCompiler::enableModuleProcessing();
        $input = '<div class="x">hello</div>';
        $this->assertSame($input, $this->compiler()->compile($input));
    }
}
