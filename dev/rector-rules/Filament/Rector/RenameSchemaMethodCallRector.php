<?php

declare(strict_types=1);

namespace Dev\Rector\Filament\Rector;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Expr\MethodCall;
use Rector\Rector\AbstractRector;

/**
 * Rector rule to rename schema() to components() in form definitions for v5.
 *
 * @changelog https://filamentphp.com/docs/5.x/upgrade-guide#form-schema-changed-to-components
 */
final class RenameSchemaMethodCallRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [MethodCall::class];
    }

    /**
     * @param MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        // Check if this is a ->schema() method call
        if (! $node->name instanceof Identifier) {
            return null;
        }

        $methodName = $node->name->toString();
        
        // Only process schema() calls on form/schema objects
        if ($methodName !== 'schema') {
            return null;
        }

        // Change schema() to components()
        $node->name = new Identifier('components');

        return $node;
    }
}
