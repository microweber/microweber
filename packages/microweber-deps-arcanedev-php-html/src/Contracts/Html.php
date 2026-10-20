<?php

declare(strict_types=1);

namespace Arcanedev\Html\Contracts;

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
use Arcanedev\Html\Elements\Select;
use Arcanedev\Html\Elements\Span;
use Arcanedev\Html\Elements\Textarea;
use Arcanedev\Html\Elements\Ul;

/**
 * Interface  Html
 *
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface Html
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make an `a` tag.
     */
    public function a(?string $href = null, ?string $content = null): A;

    /**
     * Make a `button` tag.
     */
    public function button(mixed $content = null, ?string $type = null): Button;

    /**
     * Make a checkbox input.
     */
    public function checkbox(?string $name = null, ?bool $checked = null, string|int $value = '1'): Input;

    /**
     * Parse and render `class` attribute.
     */
    public function class(iterable|string $classes): string;

    /**
     * Make a date input.
     */
    public function date(?string $name = null, ?string $value = null, bool $format = true): Input;

    /**
     * Make a datetime input.
     */
    public function datetime(?string $name = null, ?string $value = null, ?bool $format = true): Input;

    /**
     * Make a div element.
     */
    public function div(mixed $content = null): Div;

    /**
     * Make a description list.
     */
    public function dl(array $attributes = []): Dl;

    /**
     * Make a custom tag element.
     */
    public function element(string $tag): HtmlElement;

    /**
     * Make an email input.
     */
    public function email(?string $name = null, ?string $value = null): Input;

    /**
     * Make a fieldset tag.
     */
    public function fieldset(mixed $legend = null): Fieldset;

    /**
     * Make a file input.
     */
    public function file(?string $name = null): File;

    /**
     * Make a form tag.
     */
    public function form(string $method = 'POST', ?string $action = null): Form;

    /**
     * Make a hidden input.
     */
    public function hidden(?string $name = null, ?string $value = null): Input;

    /**
     * Make an i tag.
     */
    public function i(?string $content = null): I;

    /**
     * Make an input tag.
     */
    public function input(?string $type = null, ?string $name = null, mixed $value = null): Input;

    /**
     * Make an image tag.
     */
    public function img(?string $src = null, ?string $alt = null): Img;

    /**
     * Make a label tag.
     */
    public function label(mixed $content = null, ?string $for = null): Label;

    /**
     * Make a legend tag.
     */
    public function legend(HtmlElement|string|null $content = null): Legend;

    /**
     * Make a mailto link.
     */
    public function mailto(string $email, ?string $content = null): A;

    /**
     * Make a number input.
     */
    public function number(
        ?string $name = null,
        mixed $value = null,
        mixed $min = null,
        mixed $max = null,
        mixed $step = null
    ): Input;

    /**
     * Make an ordered list.
     */
    public function ol(array $attributes = []): Ol;

    /**
     * Make an option tag.
     */
    public function option(?string $text = null, mixed $value = null, bool $selected = false): Option;

    /**
     * Make a password input.
     */
    public function password(?string $name = null): Input;

    /**
     * Make a radio input.
     */
    public function radio(?string $name = null, ?bool $checked = null, mixed $value = null): Input;

    /**
     * Make a range input.
     */
    public function range(
        ?string $name = null,
        mixed $value = null,
        mixed $min = null,
        mixed $max = null,
        mixed $step = null
    ): Input;

    /**
     * Make a reset button.
     */
    public function reset(mixed $content = null): Button;

    /**
     * Make a select tag.
     */
    public function select(?string $name = null, iterable $options = [], mixed $value = null): Select;

    /**
     * Make a span tag.
     */
    public function span(mixed $content = null): Span;

    /**
     * Make a submit button.
     */
    public function submit(?string $text = null): Button;

    /**
     * Make a tel link.
     */
    public function telLink(string $phoneNumber, mixed $text = null): A;

    /**
     * Make a text input.
     */
    public function text(string $name, ?string $value = null): Input;

    /**
     * Make a textarea tag.
     */
    public function textarea(?string $name = null, ?string $value = null): Textarea;

    /**
     * Make a time input.
     */
    public function time(?string $name = null, ?string $value = null, bool $format = true): Input;

    /**
     * Make an unordered list.
     */
    public function ul(array $attributes = []): Ul;
}
