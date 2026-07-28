<?php

declare(strict_types=1);

namespace MicroweberPackages\View\Tests\Unit;

use MicroweberPackages\View\Tests\TestCase;
use MicroweberPackages\View\View;
use PHPUnit\Framework\Attributes\Test;

class ViewTest extends TestCase
{
    private function fixturePath(string $name): string
    {
        return __DIR__ . '/../Fixtures/' . $name;
    }

    #[Test]
    public function constructor_throws_for_missing_file(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new View('/path/to/nonexistent/file.php');
    }

    #[Test]
    public function renders_php_template_with_assigned_vars(): void
    {
        $view = new View($this->fixturePath('hello.php'));
        $view->assign('name', 'Microweber');

        $this->assertStringContainsString('Hello Microweber', (string) $view);
    }

    #[Test]
    public function set_assigns_multiple_variables(): void
    {
        $view = new View($this->fixturePath('hello.php'));
        $view->set(['name' => 'World']);

        $this->assertSame('Hello World', trim((string) $view));
    }

    #[Test]
    public function display_returns_content_when_return_true(): void
    {
        $view = new View($this->fixturePath('hello.php'));
        $view->assign('name', 'X');

        $this->assertSame('Hello X', trim($view->display(true)));
    }

    #[Test]
    public function render_implements_renderable(): void
    {
        $view = new View($this->fixturePath('hello.php'));
        $view->assign('name', 'Y');

        $this->assertSame('Hello Y', trim($view->render()));
    }

    #[Test]
    public function get_vars_returns_defined_variables(): void
    {
        $view = new View($this->fixturePath('hello.php'));
        $view->assign('name', 'Vars');

        $vars = $view->__get_vars();
        $this->assertArrayHasKey('name', $vars);
        $this->assertSame('Vars', $vars['name']);
    }

    #[Test]
    public function fluent_set_and_assign(): void
    {
        $view = (new View($this->fixturePath('hello.php')))
            ->set(['name' => 'Fluent'])
            ->assign('extra', 1);

        $this->assertStringContainsString('Fluent', (string) $view);
    }
}
