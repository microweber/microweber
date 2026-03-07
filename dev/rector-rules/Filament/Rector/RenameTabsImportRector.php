<?php

declare(strict_types=1);

namespace Dev\Rector\Filament\Rector;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\UseUse;
use Rector\Rector\AbstractRector;

/**
 * Rector rule to rename Filament Tabs imports from v3 to v5.
 *
 * @changelog https://filamentphp.com/docs/5.x/upgrade-guide#tabs-moved-to-schemas
 */
final class RenameTabsImportRector extends AbstractRector
{
    /**
     * Mapping of old v3 imports to new v5 imports.
     */
    private const IMPORT_MAPPING = [
        'Filament\Forms\Components\Tabs' => 'Filament\Schemas\Components\Tabs',
        'Filament\Forms\Components\Tabs\Tab' => 'Filament\Schemas\Components\Tabs\Tab',
        'Filament\Resources\Components\Tab' => 'Filament\Schemas\Components\Tabs\Tab',
    ];

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [UseUse::class];
    }

    /**
     * @param UseUse $node
     */
    public function refactor(Node $node): ?Node
    {
        if (! $node->name instanceof FullyQualified) {
            return null;
        }

        $fqn = $node->name->toString();

        if (! isset(self::IMPORT_MAPPING[$fqn])) {
            return null;
        }

        // Replace with new namespace
        $node->name = new FullyQualified(self::IMPORT_MAPPING[$fqn]);

        return $node;
    }
}
