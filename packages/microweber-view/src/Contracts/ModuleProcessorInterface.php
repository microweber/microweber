<?php

declare(strict_types=1);

namespace MicroweberPackages\View\Contracts;

/**
 * Contract for processing Microweber <module /> markup.
 *
 * The CMS binds its Parser against this interface. Standalone apps can bind
 * a custom implementation (or rely on the NullModuleProcessor no-op).
 */
interface ModuleProcessorInterface
{
    /**
     * Process HTML that may contain <module ... /> tags and return rendered HTML.
     *
     * @param  array<string, mixed>  $options
     */
    public function process(string $html, array $options = []): string;
}
