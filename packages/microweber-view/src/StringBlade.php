<?php

declare(strict_types=1);

namespace MicroweberPackages\View;

use Illuminate\Support\Facades\Blade;

/**
 * Render Blade template strings without writing files.
 *
 * Uses Laravel's native Blade::render() API. Safe for standalone apps.
 */
class StringBlade
{
    /**
     * Render a Blade template string with the given data.
     *
     * @param  array<string, mixed>  $data
     */
    public function render(string $bladeString, array $data = []): string
    {
        return Blade::render($bladeString, $data);
    }

    /**
     * Compile a Blade string to PHP source without executing it.
     */
    public function compile(string $bladeString): string
    {
        return Blade::compileString($bladeString);
    }
}
