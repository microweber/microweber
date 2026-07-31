<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Contracts;

/**
 * Contract for a single CSS file slot (live_edit, custom, per-page, …).
 */
interface CssFileHandlerInterface
{
    /**
     * Unique key for this file type (e.g. "live_edit", "custom", "page_12").
     */
    public function getKey(): string;

    /**
     * Absolute filesystem path for the CSS file, or null when option-only.
     */
    public function getPath(?string $template = null, bool $checkExists = true): ?string;

    /**
     * Public URL for the CSS file (with cache-busting query when available).
     */
    public function getUrl(?string $template = null): ?string;

    /**
     * Raw CSS content.
     */
    public function getContent(?string $template = null): string;

    /**
     * Persist CSS content. Returns rewritten content that was stored.
     *
     * @throws \MicroweberPackages\TemplateCustomCss\Exceptions\InvalidCssException
     */
    public function saveContent(string $css, ?string $template = null): string;

    /**
     * Remove / backup the CSS file (returns status array).
     *
     * @return array{success?: string, error?: string}
     */
    public function remove(?string $template = null, bool $restoreBackup = false): array;
}
