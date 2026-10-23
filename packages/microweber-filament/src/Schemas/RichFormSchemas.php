<?php

namespace MicroweberPackages\Filament\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

/**
 * Reusable rich-form building blocks: the canonical "rich page" content + SEO
 * sections, rendered in the KitchenSink showcase so the theme's styling of the
 * core Filament components (Section / TextInput / RichEditor / Textarea) is
 * verified against a live gate.
 *
 * Scope — read before wiring this into an existing resource. These are the SHARED
 * BASE for the kitchen sink and for any NEW rich page that wants exactly these
 * fields. Do NOT retrofit them onto the feature-rich existing forms: the real
 * content forms (e.g. Modules\Content ContentResource) deliberately add things
 * this generic base omits — TranslateFieldAction (multilanguage), resource-specific
 * maxLengths, and a chosen editor type (Content's body is a Textarea per
 * task-2026-05-22-AI-936, not a RichEditor). Swapping in these parts would STRIP
 * that functionality — a regression, not a DRY win. Those forms already render
 * consistently because they use the same component TYPES + the same theme CSS;
 * reuse is for new pages that match these fields, not a retrofit.
 *
 * Each method returns a self-contained Section that renders standalone (no record
 * or domain coupling); the calling page/resource just supplies matching form
 * state (either model attributes or public Livewire properties).
 */
class RichFormSchemas
{
    /**
     * Title + rich body + excerpt — the core of a "rich page" content form.
     * Fields: title, content_body, excerpt.
     */
    public static function contentSection(): Section
    {
        return Section::make(__('Content'))
            ->icon('heroicon-m-document-text')
            ->schema([
                TextInput::make('title')
                    ->label(__('Title'))
                    ->required()
                    ->maxLength(191)
                    ->columnSpanFull(),
                RichEditor::make('content_body')
                    ->label(__('Body'))
                    ->columnSpanFull(),
                Textarea::make('excerpt')
                    ->label(__('Excerpt'))
                    ->rows(3)
                    ->columnSpanFull(),
            ])
            ->columns(1);
    }

    /**
     * SEO meta fields — common to every rich page. Collapsed by default.
     * Fields: meta_title, meta_description, meta_keywords.
     */
    public static function seoSection(): Section
    {
        return Section::make(__('SEO'))
            ->icon('heroicon-m-magnifying-glass')
            ->collapsible()
            ->collapsed()
            ->schema([
                TextInput::make('meta_title')
                    ->label(__('Meta title'))
                    ->maxLength(191)
                    ->columnSpanFull(),
                Textarea::make('meta_description')
                    ->label(__('Meta description'))
                    ->rows(2)
                    ->columnSpanFull(),
                TextInput::make('meta_keywords')
                    ->label(__('Meta keywords'))
                    ->columnSpanFull(),
            ])
            ->columns(1);
    }
}
