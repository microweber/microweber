<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace  MicroweberPackages\Security\HtmlSanitizer;

use Illuminate\Support\Str;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;
use Symfony\Component\HtmlSanitizer\Parser\MastermindsParser;
use Symfony\Component\HtmlSanitizer\Parser\ParserInterface;
use Symfony\Component\HtmlSanitizer\Reference\W3CReference;
use Symfony\Component\HtmlSanitizer\TextSanitizer\StringSanitizer;
use Symfony\Component\HtmlSanitizer\Visitor\AttributeSanitizer\AttributeSanitizerInterface;
use Symfony\Component\HtmlSanitizer\Visitor\DomVisitor;
use Symfony\Component\HtmlSanitizer\Visitor\Model\Cursor;
use Symfony\Component\HtmlSanitizer\Visitor\Node\BlockedNode;
use Symfony\Component\HtmlSanitizer\Visitor\Node\DocumentNode;
use Symfony\Component\HtmlSanitizer\Visitor\Node\Node;
use Symfony\Component\HtmlSanitizer\Visitor\Node\NodeInterface;
use Symfony\Component\HtmlSanitizer\Visitor\Node\TextNode;


class MwHtmlSanitizerDomVisitor
{
    private HtmlSanitizerConfig $config;

    /**
     * Registry of allowed/blocked elements:
     * * If an element is present as a key and contains an array, the element should be allowed
     *   and the array is the list of allowed attributes.
     * * If an element is present as a key and contains "false", the element should be blocked.
     * * If an element is not present as a key, the element should be dropped.
     *
     * @var array<string, false|array<string, bool>>
     */
    private array $elementsConfig;

    /**
     * Registry of attributes to forcefully set on nodes, index by element and attribute.
     *
     * @var array<string, array<string, string>>
     */
    private array $forcedAttributes;

    /**
     * Registry of attributes sanitizers indexed by element name and attribute name for
     * faster sanitization.
     *
     * @var array<string, array<string, list<AttributeSanitizerInterface>>>
     */
    private array $attributeSanitizers = [];

    /**
     * @param array<string, false|array<string, bool>> $elementsConfig
     */
    public function __construct(MwHtmlSanitizerConfig $config, array $elementsConfig)
    {
        $this->config = $config;
        $this->elementsConfig = $elementsConfig;
        $this->forcedAttributes = $config->getForcedAttributes();

        foreach ($config->getAttributeSanitizers() as $attributeSanitizer) {
            foreach ($attributeSanitizer->getSupportedElements() ?? ['*'] as $element) {
                foreach ($attributeSanitizer->getSupportedAttributes() ?? ['*'] as $attribute) {
                    $this->attributeSanitizers[$element][$attribute][] = $attributeSanitizer;
                }
            }
        }
    }

    public function visit(\DOMDocumentFragment $domNode): ?NodeInterface
    {
        $cursor = new Cursor(new DocumentNode());
        $this->visitChildren($domNode, $cursor);

        return $cursor->node;
    }

    private function visitNode(\DOMNode $domNode, Cursor $cursor): void
    {
        dd(111);
        $nodeName = StringSanitizer::htmlLower($domNode->nodeName);

        // Element should be dropped, including its children
        if (!\array_key_exists($nodeName, $this->elementsConfig)) {
            return;
        }

        // Otherwise, visit recursively
        $this->enterNode($nodeName, $domNode, $cursor);
        $this->visitChildren($domNode, $cursor);
        $cursor->node = $cursor->node->getParent();
    }

    private function enterNode(string $domNodeName, \DOMNode $domNode, Cursor $cursor): void
    {
        // Element should be blocked, retaining its children
        if (false === $this->elementsConfig[$domNodeName]) {
            $node = new BlockedNode($cursor->node);

            $cursor->node->addChild($node);
            $cursor->node = $node;

            return;
        }

        // Otherwise create the node
      //  $node = new Node($cursor->node, $domNodeName);
        $node = new MwHtmlSanitizerDomNode($cursor->node, $domNodeName);

        $this->setAttributes($domNodeName, $domNode, $node, $this->elementsConfig[$domNodeName]);

        // Force configured attributes
        foreach ($this->forcedAttributes[$domNodeName] ?? [] as $attribute => $value) {
            $node->setAttribute($attribute, $value);
        }

        $cursor->node->addChild($node);
        $cursor->node = $node;
    }

    private function visitChildren(\DOMNode $domNode, Cursor $cursor): void
    {
        /** @var \DOMNode $child */
        foreach ($domNode->childNodes ?? [] as $child) {
            if ('#text' === $child->nodeName) {
                // Add text directly for performance
                $cursor->node->addChild(new TextNode($cursor->node, $child->nodeValue));
            } elseif (!$child instanceof \DOMText) {
                // Otherwise continue the visit recursively
                // Ignore comments for security reasons (interpreted differently by browsers)
                $this->visitNode($child, $cursor);
            }
        }
    }

    /**
     * Set attributes from a DOM node to a sanitized node.
     */
    private function setAttributes(string $domNodeName, \DOMNode $domNode, MwHtmlSanitizerDomNode $node, array $allowedAttributes = []): void
    {
        /** @var iterable<\DOMAttr> $domAttributes */
        if (!$domAttributes = $domNode->attributes ? $domNode->attributes->getIterator() : []) {
            return;
        }

        $neverAllowed = MwHtmlSanitizerReference::MW_NOT_ALLOWED_ATTRIBUTES;
        foreach ($domAttributes as $attribute) {
            $name = StringSanitizer::htmlLower($attribute->name);

            if (in_array($name, $neverAllowed)) {
                continue;
            }
            $skip = false;
            //if start with
            foreach ($neverAllowed as $neverAllowedItem) {
                $startsWith = Str::startsWith($name, $neverAllowedItem);
                if($startsWith){
                    $skip = true;
                }
            }

            if($skip){
                continue;
            }



            //if (isset($allowedAttributes[$name])) {
                $value = $attribute->value;

                // Sanitize the attribute value if there are attribute sanitizers for it
                $attributeSanitizers = array_merge(
                    $this->attributeSanitizers[$domNodeName][$name] ?? [],
                    $this->attributeSanitizers['*'][$name] ?? [],
                    $this->attributeSanitizers[$domNodeName]['*'] ?? [],
                );

                foreach ($attributeSanitizers as $sanitizer) {
                 //  / $value = $sanitizer->sanitizeAttribute($domNodeName, $name, $value, $this->config);
                 //   $value = xss_clean($value);
                }

                $node->setAttribute($name, $value);
           // }
        }
    }
}
