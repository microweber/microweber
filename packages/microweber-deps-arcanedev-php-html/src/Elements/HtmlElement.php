<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements;

use Arcanedev\Html\Contracts\Elements\HtmlElement as HtmlElementContract;
use Arcanedev\Html\Elements\Concerns\{HasAttributes, HasChildElements, HasConditionalMethods};
use Arcanedev\Html\Entities\Attributes\ClassAttribute;
use Arcanedev\Html\Exceptions\InvalidHtmlException;
use Arcanedev\Html\Exceptions\MissingTagException;
use Illuminate\Support\{Collection, HtmlString, Str, Traits\Macroable};

/**
 * Class     HtmlElement
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 *
 * @method  \Arcanedev\Html\Elements\HtmlElement|mixed  attributeIf(bool $condition, string $attribute, mixed $value = null)
 * @method  \Arcanedev\Html\Elements\HtmlElement|mixed  attributeUnless(bool $condition, string $attribute, mixed $value = null)
 * @method  \Arcanedev\Html\Elements\HtmlElement|mixed  attributeIfNotNull(mixed $valueToCheck, string $attribute, mixed $value = null)
 */
class HtmlElement implements HtmlElementContract
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use HasAttributes;
    use HasChildElements;
    use HasConditionalMethods;
    use Macroable {
        __call as __callMacro;
    }

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The tag type.
     */
    protected string $tag;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
     * HtmlElement constructor.
     */
    public function __construct()
    {
        $this->initAttributes();
        $this->initChildren();
    }

    /* -----------------------------------------------------------------
     |  Magic Methods
     | -----------------------------------------------------------------
     */

    /**
     * Render the element to string (magic method).
     */
    public function __toString(): string
    {
        return $this->toHtml();
    }

    /**
     * @inheritDoc
     */
    public function __call($name, array $arguments = [])
    {
        if (Str::endsWith($name, $this->supportedConditions)) {
            foreach ($this->supportedConditions as $condition) {
                if (method_exists($this, $method = str_replace($condition, '', $name))) {
                    return $this->callConditionalMethod($condition, $method, $arguments);
                }
            }
        }

        return $this->__callMacro($name, $arguments);
    }

    /**
     * Clone the object.
     */
    public function __clone()
    {
        $this->attributes = clone $this->attributes;
        $this->children   = clone $this->children;
    }

    /**
     * Make a html element.
     *
     * @return $this
     */
    public static function make(): static
    {
        return new static();
    }

    /**
     * Create a element with tag.
     */
    public static function withTag(string $tag): static
    {
        return static::make()->setTag($tag);
    }

    /**
     * Set an id attribute.
     *
     * @return $this
     */
    public function id(string $id): static
    {
        return $this->attribute('id', $id);
    }

    /**
     * Get the class attribute.
     */
    public function classList(): ClassAttribute
    {
        return $this->getAttributes()->classList();
    }

    /**
     * Add a class (alias).
     *
     * @return $this
     */
    public function class(iterable|string $class): static
    {
        return tap(clone $this, function (HtmlElement $elt) use ($class): void {
            $elt->getAttributes()->addClass($class);
        });
    }

    /**
     * Push a class to the list.
     *
     * @return $this
     */
    public function pushClass(string $class): static
    {
        return tap(clone $this, function (HtmlElement $elt) use ($class): void {
            $elt->classList()->push($class);
        });
    }

    /**
     * Set the style attribute.
     *
     * @return $this
     */
    public function style(array|string $style): static
    {
        if (is_array($style)) {
            $style = implode('; ', array_map(
                fn($value, $attribute) => "{$attribute}: {$value}",
                $style,
                array_keys($style)
            ));
        }

        return $this->attribute('style', $style);
    }

    /**
     * Set the data attribute.
     *
     * @return $this
     */
    public function data(array|string $name, mixed $value = null): static
    {
        return $this->attributes(
            Collection::make(is_array($name) ? $name : [$name => $value])
                ->mapWithKeys(fn($mapValue, $mapKey) => ["data-{$mapKey}" => $mapValue])
        );
    }

    /**
     * Set the text.
     *
     * @return $this
     */
    public function text(mixed $text, bool $doubleEncode = true): static
    {
        return $this->html(e($text, $doubleEncode));
    }

    /**
     * Add a html child/children.
     *
     * @return $this
     */
    public function html(mixed $html): static
    {
        if ($this->isVoidElement()) {
            throw InvalidHtmlException::onTag($this->getTag());
        }

        return $this->setNewChildren($html);
    }

    /**
     * Open the html element.
     */
    public function open(): HtmlString
    {
        $attributes = $this->getAttributes();

        $html = $attributes->isNotEmpty()
            ? "<{$this->getTag()} {$attributes->render()}>"
            : "<{$this->getTag()}>";

        return new HtmlString(
            $html . $this->getChildren()->toHtml()
        );
    }

    /**
     * Close the html element.
     */
    public function close(): HtmlString
    {
        return new HtmlString(
            $this->isVoidElement() ? '' : "</{$this->getTag()}>"
        );
    }

    /**
     * Render the element to HtmlString object.
     */
    public function render(): HtmlString
    {
        return new HtmlString($this->toHtml());
    }

    /**
     * Render the element to string.
     */
    public function toHtml(): string
    {
        return $this->open()->toHtml() .
               $this->close()->toHtml();
    }

    /* -----------------------------------------------------------------
     |  Check Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if the tag is a void element.
     */
    public function isVoidElement(): bool
    {
        return in_array($this->getTag(), [
            'area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'keygen',
            'link', 'menuitem', 'meta', 'param', 'source', 'track', 'wbr',
        ]);
    }

    /* -----------------------------------------------------------------
     |  Setters & Getters
     | -----------------------------------------------------------------
     */

    /**
     * Set the tag property.
     */
    protected function setTag(string $tag): static
    {
        $this->tag = $tag;

        return $this;
    }

    /**
     * Get the tag type.
     *
     * @throws MissingTagException
     */
    protected function getTag(): string
    {
        $this->ensureHasTag();

        return $this->tag;
    }

    /**
     * Ensure the tag property is defined.
     *
     * @throws MissingTagException
     */
    protected function ensureHasTag(): void
    {
        if (empty($this->tag)) {
            throw MissingTagException::onClass(static::class);
        }
    }
}
