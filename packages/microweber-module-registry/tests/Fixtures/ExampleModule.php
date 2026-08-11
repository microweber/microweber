<?php

declare(strict_types=1);

namespace MicroweberPackages\ModuleRegistry\Tests\Fixtures;

use MicroweberPackages\ModuleRegistry\Abstract\BaseModule;

class ExampleModule extends BaseModule
{
    public static string $name = 'Example Module';

    public static string $module = 'example';

    public static string $icon = 'modules.example-icon';

    public static string $categories = 'content';

    public static int $position = 10;

    public static string $templatesNamespace = 'module-registry-test::templates';

    /** @var list<string> */
    public static array $translatableOptions = ['title'];
}
