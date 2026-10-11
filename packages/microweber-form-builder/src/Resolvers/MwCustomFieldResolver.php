<?php

namespace MicroweberPackages\FormBuilder\Resolvers;

use MicroweberPackages\Filament\Forms\Components\MwColorPicker;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Filament\Forms\Components\MwIconPicker;
use MicroweberPackages\Filament\Forms\Components\MwInputSlider;
use MicroweberPackages\Filament\Forms\Components\MwInputSliderGroup;
use MicroweberPackages\Filament\Forms\Components\MwLinkPicker;
use MicroweberPackages\Filament\Forms\Components\MwMediaBrowser;
use MicroweberPackages\Filament\Forms\Components\MwRichEditor;
use MicroweberPackages\Filament\Forms\Components\MwSelectTemplateForPage;
use MicroweberPackages\Filament\Forms\Components\MwTitleWithSlugInput;
use MicroweberPackages\FormBuilder\FieldTypeRegistry;

class MwCustomFieldResolver
{
    /**
     * Register all Microweber custom field types.
     */
    public static function register(FieldTypeRegistry $registry): void
    {
        // Image / File Upload
        $imageResolver = function (array $field) {
            $component = MwFileUpload::make($field['name'])
                ->label($field['label'] ?? FilamentFieldResolver::titlelize($field['name']));

            FilamentFieldResolver::applyCommon($component, $field);

            return $component;
        };
        $registry->register('image', $imageResolver);
        $registry->register('file_upload', $imageResolver);

        // Color Picker (Mw custom)
        $colorPickerResolver = function (array $field) {
            $component = MwColorPicker::make($field['name'])
                ->label($field['label'] ?? FilamentFieldResolver::titlelize($field['name']))
                ->hex();

            FilamentFieldResolver::applyCommon($component, $field);

            return $component;
        };
        $registry->register('color_picker', $colorPickerResolver);

        // Icon Picker
        $iconResolver = function (array $field) {
            $component = MwIconPicker::make($field['name'])
                ->label($field['label'] ?? FilamentFieldResolver::titlelize($field['name']));

            if (!empty($field['options']['icon_sets'])) {
                $component->iconSets($field['options']['icon_sets']);
            }

            FilamentFieldResolver::applyCommon($component, $field);

            return $component;
        };
        $registry->register('icon', $iconResolver);
        $registry->register('icon_picker', $iconResolver);

        // Link Picker
        $linkResolver = function (array $field) {
            $component = MwLinkPicker::make($field['name'])
                ->label($field['label'] ?? FilamentFieldResolver::titlelize($field['name']));

            if (!empty($field['extra']['simple_mode']) || !empty($field['options']['simple_mode'])) {
                $component->setSimpleMode(true);
            }

            FilamentFieldResolver::applyCommon($component, $field);

            return $component;
        };
        $registry->register('link', $linkResolver);
        $registry->register('link_picker', $linkResolver);

        // Range Slider
        $sliderResolver = function (array $field) {
            $options = $field['options'] ?? [];
            $min = $options['min'] ?? 0;
            $max = $options['max'] ?? 100;
            $step = $options['step'] ?? 1;

            $component = MwInputSliderGroup::make()
                ->label($field['label'] ?? FilamentFieldResolver::titlelize($field['name']))
                ->enableTooltips()
                ->sliders([
                    MwInputSlider::make($field['name'])
                ])
                ->range([
                    'min' => $min,
                    'max' => $max
                ])
                ->step($step);

            if (!empty($field['live'])) {
                $component->live();
            }

            return $component;
        };
        $registry->register('slider', $sliderResolver);
        $registry->register('range_slider', $sliderResolver);

        // Rich Editor
        $registry->register('rich_editor', function (array $field) {
            $component = MwRichEditor::make($field['name'])
                ->label($field['label'] ?? FilamentFieldResolver::titlelize($field['name']));

            if (!empty($field['options']['simple'])) {
                $component->simple();
            }
            if (!empty($field['options']['profile'])) {
                $component->profile($field['options']['profile']);
            }

            FilamentFieldResolver::applyCommon($component, $field);

            return $component;
        });

        // Media Browser
        $registry->register('media_browser', function (array $field) {
            $component = MwMediaBrowser::make($field['name'])
                ->label($field['label'] ?? FilamentFieldResolver::titlelize($field['name']));

            FilamentFieldResolver::applyCommon($component, $field);

            return $component;
        });

        // Title With Slug (static ::make() returns a Group)
        $registry->register('title_with_slug', function (array $field) {
            $params = [];
            if (!empty($field['options']['field_title'])) {
                $params['fieldTitle'] = $field['options']['field_title'];
            }
            if (!empty($field['options']['field_slug'])) {
                $params['fieldSlug'] = $field['options']['field_slug'];
            }

            return MwTitleWithSlugInput::make(
                fieldTitle: $params['fieldTitle'] ?? $field['name'] ?? 'title',
                fieldSlug: $params['fieldSlug'] ?? 'slug',
            );
        });

        // Select Template For Page
        if (class_exists(MwSelectTemplateForPage::class)) {
            $registry->register('select_template', function (array $field) {
                $component = MwSelectTemplateForPage::make($field['name'])
                    ->label($field['label'] ?? FilamentFieldResolver::titlelize($field['name']));

                FilamentFieldResolver::applyCommon($component, $field);

                return $component;
            });
        }
    }
}
