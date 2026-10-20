<?php

declare(strict_types=1);

namespace Arcanedev\Html\Elements;

use Arcanedev\Html\Contracts\Selectable;
use Arcanedev\Html\Elements\Concerns\HasAutofocusAttribute;
use Arcanedev\Html\Elements\Concerns\HasDisabledAttribute;
use Arcanedev\Html\Elements\Concerns\HasNameAttribute;
use Arcanedev\Html\Elements\Concerns\HasReadonlyAttribute;
use Arcanedev\Html\Elements\Concerns\HasRequiredAttribute;
use Illuminate\Support\{Collection, Str};

/**
 * Class     Select
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Select extends HtmlElement
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use HasAutofocusAttribute;
    use HasDisabledAttribute;
    use HasNameAttribute;
    use HasReadonlyAttribute;
    use HasRequiredAttribute;

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    protected string $tag = 'select';

    protected array $options = [];

    protected mixed $value = '';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Set the select input as multiple.
     *
     * @return $this
     */
    public function multiple(): static
    {
        /** @var  self  $elt */
        $elt  = with(clone $this)->attribute('multiple');
        $name = $elt->getAttribute('name');

        return $elt->if(
            $name && ! Str::endsWith($name->value(), '[]'),
            fn(self $elt) => $elt->name($name->value() . '[]')
        )->applyValueToOptions();
    }

    /**
     * Add options.
     *
     * @return $this
     */
    public function options(iterable $options, array $attributes = [], array $groupAttributes = []): static
    {
        return $this->children(
            $options,
            fn($text, $value) => is_array($text) || $text instanceof Collection
            ? $this->makeOptionsGroup($value, $text, $attributes, $groupAttributes[$value] ?? [])
            : $this->makeOption($value, $text, $attributes[$value] ?? [])
        );
    }

    /**
     * Add a placeholder option.
     *
     * @return $this
     */
    public function placeholder(string $text, mixed $value = null, bool $disabled = false): static
    {
        return $this->prependChild(
            $this->makeOption($value, $text)
                ->selectedUnless($this->hasSelection())
                ->disabled($disabled)
        );
    }

    /**
     * Set the value.
     *
     * @return $this
     */
    public function value(mixed $value = null): static
    {
        return tap(clone $this, function (self $element) use ($value): void {
            $element->value = $value;
        })->applyValueToOptions();
    }

    /**
     * Apply the selected value to the options.
     */
    protected static function applyValueToElements(Collection $value, Collection $children): Collection
    {
        return $children->map(function (HtmlElement $child) use ($value) {
            if ($child instanceof Optgroup) {
                return $child->setNewChildren(static::applyValueToElements($value, $child->getChildren()));
            }

            if ($child instanceof Selectable) {
                return $child->selectedIf(
                    $value->contains($child->getAttribute('value')->value())
                );
            }

            return $child;
        });
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if has a selected option.
     */
    protected function hasSelection(): bool
    {
        return $this->getChildren()->contains(
            fn(HtmlElement $child) => $child->hasAttribute('selected')
        );
    }

    /**
     * Make an option tag.
     */
    protected function makeOption(mixed $value, ?string $text = null, array $attributes = []): Option
    {
        return Option::make()
            ->value($value)
            ->text($text ?: $value, false)
            ->selectedIf($value === $this->value)
            ->attributes($attributes);
    }

    /**
     * Make an options group.
     */
    protected function makeOptionsGroup(
        string $label,
        array $options,
        array $attributes = [],
        array $groupAttributes = []
    ): Optgroup {
        return Optgroup::make()
            ->label($label)
            ->attributes($groupAttributes)
            ->children(
                $options,
                fn($optionText, $optionValue) => $this->makeOption($optionValue, $optionText, $attributes[$optionValue] ?? [])
            );
    }

    /**
     * Apply the selected value to the options.
     *
     * @return $this
     */
    protected function applyValueToOptions(): static
    {
        $value = Collection::make($this->value);

        if ( ! $this->hasAttribute('multiple')) {
            $value = $value->take(1);
        }

        return $this->setNewChildren(
            static::applyValueToElements($value, $this->getChildren())
        );
    }
}
