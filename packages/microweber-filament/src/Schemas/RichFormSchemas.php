<?php

namespace MicroweberPackages\Filament\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;

/**
 * Reusable rich-form building blocks shared by real admin pages (the content
 * editor, settings pages, …) AND the KitchenSink component showcase, so both
 * render the SAME Filament components off ONE source — styling verified in the
 * kitchen sink holds for the real pages (DRY).
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
