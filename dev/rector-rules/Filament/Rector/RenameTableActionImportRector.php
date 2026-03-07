<?php

declare(strict_types=1);

namespace Dev\Rector\Filament\Rector;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\UseUse;
use Rector\Rector\AbstractRector;

/**
 * Rector rule to rename Filament Table Actions imports from v3 to v5.
 *
 * @changelog https://filamentphp.com/docs/5.x/upgrade-guide#filament-tables-actions-moved
 */
final class RenameTableActionImportRector extends AbstractRector
{
    /**
     * Mapping of old v3 imports to new v5 imports.
     */
    private const IMPORT_MAPPING = [
        'Filament\Tables\Actions\EditAction' => 'Filament\Actions\EditAction',
        'Filament\Tables\Actions\DeleteAction' => 'Filament\Actions\DeleteAction',
        'Filament\Tables\Actions\ViewAction' => 'Filament\Actions\ViewAction',
        'Filament\Tables\Actions\CreateAction' => 'Filament\Actions\CreateAction',
        'Filament\Tables\Actions\ReplicateAction' => 'Filament\Actions\ReplicateAction',
        'Filament\Tables\Actions\RestoreAction' => 'Filament\Actions\RestoreAction',
        'Filament\Tables\Actions\ForceDeleteAction' => 'Filament\Actions\ForceDeleteAction',
        'Filament\Tables\Actions\ActionGroup' => 'Filament\Actions\ActionGroup',
        'Filament\Tables\Actions\BulkActionGroup' => 'Filament\Actions\BulkActionGroup',
        'Filament\Tables\Actions\DeleteBulkAction' => 'Filament\Actions\DeleteBulkAction',
        'Filament\Tables\Actions\ForceDeleteBulkAction' => 'Filament\Actions\ForceDeleteBulkAction',
        'Filament\Tables\Actions\RestoreBulkAction' => 'Filament\Actions\RestoreBulkAction',
        'Filament\Tables\Actions\DetachBulkAction' => 'Filament\Actions\DetachBulkAction',
        'Filament\Tables\Actions\AttachAction' => 'Filament\Actions\AttachAction',
        'Filament\Tables\Actions\DetachAction' => 'Filament\Actions\DetachAction',
        'Filament\Tables\Actions\AssociateAction' => 'Filament\Actions\AssociateAction',
        'Filament\Tables\Actions\DdissociateAction' => 'Filament\Actions\DissociateAction',
        'Filament\Tables\Actions\Action' => 'Filament\Actions\Action',
        'Filament\Tables\Actions\StaticAction' => 'Filament\Actions\StaticAction',
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
