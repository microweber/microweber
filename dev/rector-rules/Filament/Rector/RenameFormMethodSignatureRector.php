<?php

declare(strict_types=1);

namespace Dev\Rector\Filament\Rector;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Rector\AbstractRector;

/**
 * Rector rule to rename Filament form() method signature from v3 to v5.
 *
 * Changes: form(Form $form): Form -> form(Schema $schema): Schema
 */
final class RenameFormMethodSignatureRector extends AbstractRector
{
    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param ClassMethod $node
     */
    public function refactor(Node $node): ?Node
    {
        // Check if this is the form() method
        if (! $node->name instanceof Identifier || $node->name->toString() !== 'form') {
            return null;
        }

        // Check if method has parameters
        if ($node->params === [] || count($node->params) < 1) {
            return null;
        }

        $firstParam = $node->params[0];
        
        // Check if first param is Form type
        if (! $firstParam->type instanceof Name) {
            return null;
        }

        $paramType = $firstParam->type->toString();
        
        if ($paramType !== 'Form' && $paramType !== 'Filament\Forms\Form') {
            return null;
        }

        // Change parameter type from Form to Schema
        $firstParam->type = new Name('Schema');
        
        // Change return type from Form to Schema if it exists
        if ($node->returnType instanceof Name) {
            $returnType = $node->returnType->toString();
            if ($returnType === 'Form' || $returnType === 'Filament\Forms\Form') {
                $node->returnType = new Name('Schema');
            }
        }

        return $node;
    }
}
