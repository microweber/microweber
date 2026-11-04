<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateCustomCss\Support;

use MicroweberPackages\TemplateCustomCss\Contracts\SnapshotStoreInterface;

/** No-op snapshot store for standalone use (no database available). */
class NullSnapshotStore implements SnapshotStoreInterface
{
    public function snapshot(string $template, string $type, string $content): void {}

    public function undo(string $template, string $type): ?string
    {
        return null;
    }

    public function count(string $template, string $type): int
    {
        return 0;
    }
}
