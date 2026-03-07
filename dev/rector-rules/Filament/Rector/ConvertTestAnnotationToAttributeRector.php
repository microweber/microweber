<?php

declare(strict_types=1);

namespace Dev\Rector\Filament\Rector;

use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Attribute;
use PhpParser\Node\AttributeGroup;
use PhpParser\Node\Name\FullyQualified;
use Rector\Rector\AbstractRector;

/**
 * Rector rule to convert @test annotation to #[Test] attribute.
 */
final class ConvertTestAnnotationToAttributeRector extends AbstractRector
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
        $docComment = $node->getDocComment();
        
        if ($docComment === null) {
            return null;
        }

        $docText = $docComment->getText();
        
        // Check if @test annotation exists
        if (! str_contains($docText, '@test')) {
            return null;
        }

        // Check if #[Test] attribute already exists
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                if ($attr->name->toString() === 'Test' || $attr->name->toString() === 'PHPUnit\Framework\Attributes\Test') {
                    // Already has Test attribute
                    return null;
                }
            }
        }

        // Add #[Test] attribute
        $testAttribute = new Attribute(
            new FullyQualified('PHPUnit\Framework\Attributes\Test'),
            []
        );
        
        $attrGroup = new AttributeGroup([$testAttribute]);
        
        // Prepend to existing attributes
        array_unshift($node->attrGroups, $attrGroup);

        return $node;
    }
}
