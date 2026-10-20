<?php

declare(strict_types=1);

namespace Arcanedev\Html;

use Arcanedev\Html\Contracts\Html as HtmlContract;
use Arcanedev\Html\Elements\A;
use Arcanedev\Html\Elements\Button;
use Arcanedev\Html\Elements\Div;
use Arcanedev\Html\Elements\Dl;
use Arcanedev\Html\Elements\Fieldset;
use Arcanedev\Html\Elements\File;
use Arcanedev\Html\Elements\Form;
use Arcanedev\Html\Elements\HtmlElement;
use Arcanedev\Html\Elements\I;
use Arcanedev\Html\Elements\Img;
use Arcanedev\Html\Elements\Input;
use Arcanedev\Html\Elements\Label;
use Arcanedev\Html\Elements\Legend;
use Arcanedev\Html\Elements\Ol;
use Arcanedev\Html\Elements\Option;
use Arcanedev\Html\Elements\P;
use Arcanedev\Html\Elements\Select;
use Arcanedev\Html\Elements\Span;
use Arcanedev\Html\Elements\Textarea;
use Arcanedev\Html\Elements\Ul;
use Arcanedev\Html\Entities\Attributes\ClassAttribute;
use DateTimeImmutable;
use Exception;
use Illuminate\Support\Str;

/**
 * Class     Html
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Html implements HtmlContract
{
    /* -----------------------------------------------------------------
     |  Constants
     | -----------------------------------------------------------------
     */

    public const HTML_DATE_FORMAT = 'Y-m-d';
    public const HTML_TIME_FORMAT = 'H:i:s';

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make an `a` tag.
     */
    public function a(?string $href = null, ?string $content = null): A
    {
        return A::make()
            ->attributeIfNotNull($href, 'href', $href)
            ->html($content);
    }

    /**
     * Make a `button` tag.
     */
    public function button(mixed $content = null, ?string $type = null): Button
    {
        return Button::make()
            ->attributeIfNotNull($type, 'type', $type)
            ->html($content);
    }

    /**
     * Make a checkbox input.
     */
    public function checkbox(?string $name = null, ?bool $checked = null, string|int $value = '1'): Input
    {
        return $this->input()
            ->attribute('type', 'checkbox')
            ->attributeIfNotNull($name, 'name', $name)
            ->attributeIfNotNull($name, 'id', $name)
            ->attributeIfNotNull($value, 'value', $value)
            ->attributeIf((bool) $checked, 'checked');
    }

    /**
     * Parse and render `class` attribute.
     */
    public function class(iterable|string $classes): string
    {
        return ClassAttribute::make($classes)->render();
    }

    /**
     * Make a date input.
     */
    public function date(?string $name = null, ?string $value = null, bool $format = true): Input
    {
        $input = $this->input('date', $name, $value);

        return $this->canFormatDateInput($input, $value, $format)
            ? $input->value(static::formatDateTime($value, static::HTML_DATE_FORMAT))
            : $input;
    }

    /**
     * Make a datetime input.
     */
    public function datetime(?string $name = null, ?string $value = null, ?bool $format = true): Input
    {
        $input = $this->input('datetime-local', $name, $value);

        return $this->canFormatDateInput($input, $value, $format)
            ? $input->value($this->formatDateTime($value, static::HTML_DATE_FORMAT . '\T' . static::HTML_TIME_FORMAT))
            : $input;
    }

    /**
     * Make a div element.
     */
    public function div(mixed $content = null): Div
    {
        return Div::make()->addChild($content);
    }

    /**
     * Make a description list.
     */
    public function dl(array $attributes = []): Dl
    {
        return Dl::make()->attributes($attributes);
    }

    /**
     * Make a custom tag element.
     */
    public function element(string $tag): HtmlElement
    {
        return HtmlElement::withTag($tag);
    }

    /**
     * Make an email input.
     */
    public function email(?string $name = null, ?string $value = null): Input
    {
        return $this->input('email', $name, $value);
    }

    /**
     * Make a fieldset tag.
     */
    public function fieldset(mixed $legend = null): Fieldset
    {
        return $legend === null
            ? Fieldset::make()
            : Fieldset::make()->legend($legend);
    }

    /**
     * Make a file input.
     */
    public function file(?string $name = null): File
    {
        return File::make()
            ->attributeIfNotNull($name, 'name', $name)
            ->attributeIfNotNull($name, 'id', $name);
    }

    /**
     * Make a form input.
     */
    public function form(string $method = 'POST', ?string $action = null): Form
    {
        return Form::make()
            ->method($method)
            ->attributeIfNotNull($action, 'action', $action);
    }

    /**
     * Make a hidden input.
     */
    public function hidden(?string $name = null, ?string $value = null): Input
    {
        return $this->input('hidden', $name, $value);
    }

    /**
     * Make an i tag.
     */
    public function i(?string $content = null): I
    {
        return I::make()->html($content);
    }

    /**
     * Make an input tag.
     */
    public function input(?string $type = null, ?string $name = null, mixed $value = null): Input
    {
        $hasValue = $name && $value !== null && $type !== 'password';

        return Input::make()
            ->attributeIfNotNull($type, 'type', $type)
            ->attributeIfNotNull($name, 'name', $name)
            ->attributeIfNotNull($name, 'id', $name)
            ->attributeIf($hasValue, 'value', $value);
    }

    /**
     * Make an image tag.
     */
    public function img(?string $src = null, ?string $alt = null): Img
    {
        return Img::make()
            ->attributeIfNotNull($src, 'src', $src)
            ->attributeIfNotNull($alt, 'alt', $alt);
    }

    /**
     * Make a label tag.
     */
    public function label(mixed $content = null, ?string $for = null): Label
    {
        return Label::make()
            ->attributeIfNotNull($for, 'for', $for)
            ->children($content);
    }

    /**
     * Make a legend tag.
     */
    public function legend(HtmlElement|string|null $content = null): Legend
    {
        return Legend::make()->html($content);
    }

    /**
     * Make a mailto link.
     */
    public function mailto(string $email, ?string $content = null): A
    {
        return $this->a("mailto:{$email}", $content ?: $email);
    }

    /**
     * Make a number input.
     */
    public function number(
        ?string $name = null,
        mixed $value = null,
        mixed $min = null,
        mixed $max = null,
        mixed $step = null
    ): Input {
        return $this->input('number', $name, $value)
            ->attributeIfNotNull($min, 'min', $min)
            ->attributeIfNotNull($max, 'max', $max)
            ->attributeIfNotNull($step, 'step', $step);
    }

    /**
     * Make an ordered list.
     */
    public function ol(array $attributes = []): Ol
    {
        return Ol::make()->attributes($attributes);
    }

    /**
     * Make an option tag.
     */
    public function option(?string $text = null, mixed $value = null, bool $selected = false): Option
    {
        return Option::make()
            ->text($text)
            ->value($value)
            ->selectedIf($selected);
    }

    /**
     * Make a paragraph tag.
     */
    public function p(HtmlElement|string|null $content = null): P
    {
        return P::make()->html($content);
    }

    /**
     * Make a password input.
     */
    public function password(?string $name = null): Input
    {
        return $this->input('password', $name);
    }

    /**
     * Make a radio input.
     */
    public function radio(?string $name = null, ?bool $checked = null, mixed $value = null): Input
    {
        return $this->input('radio', $name, $value)
            ->attributeIfNotNull($name, 'id', $value === null ? $name : ($name . '_' . Str::slug($value)))
            ->attributeIf(($value !== null) || $checked, 'checked');
    }

    /**
     * Make a range input.
     */
    public function range(
        ?string $name = null,
        mixed $value = null,
        mixed $min = null,
        mixed $max = null,
        mixed $step = null
    ): Input {
        return $this->input('range', $name, $value)
            ->attributeIfNotNull($min, 'min', $min)
            ->attributeIfNotNull($max, 'max', $max)
            ->attributeIfNotNull($step, 'step', $step);
    }

    /**
     * Make a reset button.
     */
    public function reset(mixed $content = null): Button
    {
        return $this->button($content, 'reset');
    }

    /**
     * Make a select tag.
     */
    public function select(?string $name = null, iterable $options = [], mixed $value = null): Select
    {
        return Select::make()
            ->attributeIfNotNull($name, 'name', $name)
            ->attributeIfNotNull($name, 'id', $name)
            ->options($options)
            ->value($value);
    }

    /**
     * Make a span tag.
     */
    public function span(mixed $content = null): Span
    {
        return Span::make()->children($content);
    }

    /**
     * Make a submit button.
     */
    public function submit(?string $text = null): Button
    {
        return $this->button($text, 'submit');
    }

    /**
     * Make a tel link.
     */
    public function telLink(string $phoneNumber, mixed $text = null): A
    {
        return $this->a("tel:{$phoneNumber}", $text ?: $phoneNumber);
    }

    /**
     * Make a text input.
     */
    public function text(string $name, ?string $value = null): Input
    {
        return $this->input('text', $name, $value);
    }

    /**
     * Make a textarea tag.
     */
    public function textarea(?string $name = null, ?string $value = null): Textarea
    {
        return Textarea::make()
            ->attributeIfNotNull($name, 'name', $name)
            ->attributeIfNotNull($name, 'id', $name)
            ->value($value);
    }

    /**
     * Make a time input.
     */
    public function time(?string $name = null, ?string $value = null, bool $format = true): Input
    {
        $input = $this->input('time', $name, $value);

        return $this->canFormatDateInput($input, $value, $format)
            ? $input->value(static::formatDateTime($value, self::HTML_TIME_FORMAT))
            : $input;
    }

    /**
     * Make an unordered list.
     */
    public function ul(array $attributes = []): Ul
    {
        return Ul::make()->attributes($attributes);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Format the date/time value.
     */
    protected static function formatDateTime(string $value, string $format): string
    {
        try {
            if ( ! empty($value)) {
                $value = (new DateTimeImmutable($value))->format($format);
            }
        } catch (Exception $e) {
            // Do nothing...
        }

        return $value;
    }

    /**
     * Check if the input can be formatted.
     */
    protected function canFormatDateInput(Input $input, ?string $value, bool $format): bool
    {
        return $format
            && $input->hasAttribute('value')
            && ! empty($value = $input->getAttribute('value')->value());
    }
}
