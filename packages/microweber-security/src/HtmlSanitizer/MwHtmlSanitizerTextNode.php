<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MicroweberPackages\Security\HtmlSanitizer;

use Symfony\Component\HtmlSanitizer\TextSanitizer\StringSanitizer;
use Symfony\Component\HtmlSanitizer\Visitor\Node\NodeInterface;

/**
 * Text node for the Microweber HTML sanitizer visitor.
 */
class MwHtmlSanitizerTextNode implements NodeInterface
{
    public function __construct(
        private NodeInterface $parentNode,
        private string $text,
    ) {
    }

    public function addChild(NodeInterface $node): void
    {
        throw new \LogicException('Text nodes cannot have children.');
    }

    public function getParent(): ?NodeInterface
    {
        return $this->parentNode;
    }

    public function render(): string
    {
        return StringSanitizer::encodeHtmlEntities($this->text);
    }
}
