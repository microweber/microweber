<?php

declare(strict_types=1);

namespace Dev\Rector\Filament\Rector;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Identifier;
use PhpParser\Node\Expr\Variable;
use Rector\Rector\AbstractRector;

/**
 * Rector rule to replace $emit with $dispatch for Livewire v3.
 *
 * @changelog https://livewire.laravel.com/docs/upgrading#event-dispatching
 */
final class FixLivewireEventDispatchRector extends AbstractRector
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
        // Check if this is $this->emit() call
        if (! $node->var instanceof Variable) {
            return null;
        }

        if ($node->var->name !== 'this') {
            return null;
        }

        if (! $node->name instanceof Identifier) {
            return null;
        }

        $methodName = $node->name->toString();
        
        // Replace emit with dispatch
        if ($methodName === 'emit') {
            $node->name = new Identifier('dispatch');
            return $node;
        }
        
        // Replace emitUp with dispatch
        if ($methodName === 'emitUp') {
            $node->name = new Identifier('dispatch');
            return $node;
        }

        return null;
    }
}
