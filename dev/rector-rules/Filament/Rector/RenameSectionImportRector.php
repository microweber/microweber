<?php

declare(strict_types=1);

namespace Dev\Rector\Filament\Rector;

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\UseUse;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Rector rule to rename Filament Section import from v3 to v5.
 *
 * @changelog https://filamentphp.com/docs/5.x/upgrade-guide#filamentformscomponentssection-namespace-changed
 */
final class RenameSectionImportRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rename Filament Section import from Forms to Schemas namespace',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
use Filament\Forms\Components\Section;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
use Filament\Schemas\Components\Section;
CODE_SAMPLE
                ),
            ]
        );
    }

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

        // Check if this is the old Section import
        if ($fqn !== 'Filament\Forms\Components\Section') {
            return null;
        }

        // Replace with new namespace
        $node->name = new FullyQualified('Filament\Schemas\Components\Section');

        return $node;
    }
}
