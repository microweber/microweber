<?php

namespace MicroweberPackages\Filament\Forms\Components;
use Closure;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use MicroweberPackages\Filament\Forms\Fields\MwSlugInput;

// from https://github.com/camya/filament-title-with-slug/blob/main/src/Forms/Components/TitleWithSlugInput.php
class MwTitleWithSlugInput
{
    public static function make(

        // Model fields
        string $fieldTitle = null,
        string $fieldSlug = null,

        // Url
        string|Closure|null $urlPath = '/',
        string|Closure $urlHost = null,
        bool $urlHostVisible = true,
        bool|Closure $urlVisitLinkVisible = true,
        Closure|string $urlVisitLinkLabel = null,
        Closure $urlVisitLinkRoute = null,

        // Title
        string|Closure $titleLabel = null,
        string $titlePlaceholder = null,
        array|Closure $titleExtraInputAttributes = null,
        array $titleRules = [
            'required',
        ],
        array $titleRuleUniqueParameters = [],
        bool|Closure $titleIsReadonly = false,
        bool|Closure $titleAutofocus = true,
        Closure $titleAfterStateUpdated = null,

        // Slug
        string $slugLabel = null,
        array $slugRules = [
            'required',
        ],
        array $slugRuleUniqueParameters = [],
        bool|Closure $slugIsReadonly = false,
        Closure $slugAfterStateUpdated = null,
        Closure $slugSlugifier = null,
        string|Closure|null $slugRuleRegex = '/^[a-z0-9\-\_]*$/',
        string|Closure $slugLabelPostfix = null,
    ): Group {
        $fieldTitle = $fieldTitle ?? config('filament-title-with-slug.field_title');
        $fieldSlug = $fieldSlug ?? config('filament-title-with-slug.field_slug');
        $urlHost = $urlHost ?? config('filament-title-with-slug.url_host');

        /** Input: "Title" */
        $textInput = TextInput::make($fieldTitle)
            ->disabled($titleIsReadonly)
            ->autofocus($titleAutofocus)
            ->live(true)
            ->autocomplete(false)
            ->rules($titleRules)
            ->extraInputAttributes($titleExtraInputAttributes ?? ['class' => 'text-xl font-semibold'])
            ->beforeStateDehydrated(fn (TextInput $component, $state) => $component->state(trim($state)))
            ->afterStateUpdated(
                function (
                    $state,
                    Set $set,
                    Get $get,
                    string $context,
                    ?Model $record,
                    TextInput $component
                ) use (
                    $slugSlugifier,
                    $fieldSlug,
                    $titleAfterStateUpdated,
                ) {
                    $slugAutoUpdateDisabled = $get($fieldSlug . '_slug_auto_update_disabled');

                    if ($context === 'edit' && filled($record)) {
                        $slugAutoUpdateDisabled = true;
                    }

                    if (! $slugAutoUpdateDisabled && filled($state)) {
                        $set($fieldSlug, self::slugify($slugSlugifier, $state));
                    }

                    if ($titleAfterStateUpdated) {
                        $component->evaluate($titleAfterStateUpdated);
                    }
                }
            );

        if (in_array('required', $titleRules, true)) {
            $textInput->required();
        }

        if ($titlePlaceholder !== '') {
            $textInput->placeholder($titlePlaceholder ?: fn () => Str::of($fieldTitle)->headline());
        }

        if (! $titleLabel) {
            $textInput->hiddenLabel();
        }

        if ($titleLabel) {
            $textInput->label($titleLabel);
        }

        if ($titleRuleUniqueParameters) {
            $textInput->unique(...$titleRuleUniqueParameters);
        }

        /** Input: "Slug" (+ view) */
        $slugInput = MwSlugInput::make($fieldSlug)

            // Custom SlugInput methods
            ->slugInputVisitLinkRoute($urlVisitLinkRoute)
            ->slugInputVisitLinkLabel($urlVisitLinkLabel)
            ->slugInputUrlVisitLinkVisible($urlVisitLinkVisible)
            ->slugInputContext(fn ($context) => $context === 'create' ? 'create' : 'edit')
            ->slugInputRecordSlug(fn (?Model $record) => data_get($record?->attributesToArray(), $fieldSlug))
            ->slugInputModelName(
                fn (?Model $record) => $record
                    ? Str::of(class_basename($record))->headline()
                    : ''
            )
            ->slugInputLabelPrefix($slugLabel)
            ->slugInputBasePath($urlPath)
            ->slugInputBaseUrl($urlHost)
            ->slugInputShowUrl($urlHostVisible)
            ->slugInputSlugLabelPostfix($slugLabelPostfix)

            // Default TextInput methods
            ->readOnly($slugIsReadonly)
            ->live(true)
            ->autocomplete(false)
            ->hiddenLabel()
            ->regex($slugRuleRegex)
            ->rules($slugRules)
            ->afterStateUpdated(
                function (
                    $state,
                    Set $set,
                    Get $get,
                    TextInput $component
                ) use (
                    $slugSlugifier,
                    $fieldTitle,
                    $fieldSlug,
                    $slugAfterStateUpdated,
                ) {
                    $text = trim($state) === ''
                        ? $get($fieldTitle)
                        : $get($fieldSlug);

                    $set($fieldSlug, self::slugify($slugSlugifier, $text));

                    $set($fieldSlug . '_slug_auto_update_disabled', true);

                    if ($slugAfterStateUpdated) {
                        $component->evaluate($slugAfterStateUpdated);
                    }
                }
            );

        if (in_array('required', $slugRules, true)) {
            $slugInput->required();
        }

        $slugRuleUniqueParameters
            ? $slugInput->unique(...$slugRuleUniqueParameters)
            : $slugInput->unique(ignorable: fn (?Model $record) => $record);

        /** Input: "Slug Auto Update Disabled" (Hidden) */
        $hiddenInputSlugAutoUpdateDisabled = Hidden::make($fieldSlug . '_slug_auto_update_disabled')
            ->dehydrated(false);

        /** Group */

        return Group::make()
            ->schema([
                $textInput,
                $slugInput,
                $hiddenInputSlugAutoUpdateDisabled,
            ]);
    }

    /** Fallback slugifier, over-writable with slugSlugifier parameter. */
    protected static function slugify(?Closure $slugifier, ?string $text): string
    {
        if (is_null($text) || ! trim($text)) {
            return '';
        }

        return is_callable($slugifier)
            ? $slugifier($text)
            : Str::slug($text);
    }
}
