<?php

namespace Modules\Content\Concerns;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Page\Models\Page;

trait HasContentFilterModuleSettings
{
    public function getContentFilterModuleSettingsSchema(): array
    {

        $pages = Page::query()->where('is_active', 1)->get();

        $pagesOpts = [];
        if ($pages) {
            foreach ($pages as $page) {
                $pagesOpts[$page->id] = $page->title;
            }
        }
        $categories = Category::query()->where('is_active', 1)->get();

        $categoriesOpts = [];
        if ($categories) {
            foreach ($categories as $category) {
                $categoriesOpts[$category->id] = $category->title;
            }
        }

        return [
            Select::make('options.data-page-id')
                ->label('From Page Source')
                ->live()
                ->visible(function (Forms\Get $get) use ($pagesOpts) {
                    return !empty($pagesOpts);
                })
                ->options($pagesOpts),
            Select::make('options.data-category-id')
                ->label('From Category Source')
                ->live()
                ->visible(function (Forms\Get $get) use ($categoriesOpts) {
                    return !empty($categoriesOpts);
                })
                ->options($categoriesOpts),
            Select::make('options.data-tags')
                ->label('Filter Tags')
                ->live()
                ->options([]),

            Select::make('options.data-order-by')
                ->label('Order by')
                ->options([
                    'position+asc' => 'Position (ASC)',
                    'position+desc' => 'Position (DESC)',
                    'created_at+asc' => 'Date (ASC)',
                    'created_at+desc' => 'Date (DESC)',
                    'title+asc' => 'Title (ASC)',
                    'title+desc' => 'Title (DESC)',
                ])
                ->live(),


            Radio::make('options.data-show')
                ->label('Display on post')
                ->live()
                ->options([
                    0 => 'Default information from skin',
                    1 => 'Custom information',
                ]),

            Checkbox::make('options.data-show-thumbnail')
                ->visible(function (Forms\Get $get) {
                    return $get('options.data-show') == 1;
                })
                ->label('Thumbnail')
                ->live(),
            Checkbox::make('options.data-show-title')
                ->visible(function (Forms\Get $get) {
                    return $get('options.data-show') == 1;
                })
                ->label('Title')
                ->live(),
            TextInput::make('options.data-title-limit')
                ->visible(function (Forms\Get $get) {
                    return $get('options.data-show') == 1;
                })
                ->label('Title Limit')
                ->numeric()
                ->live(),
            Checkbox::make('options.data-show-description')
                ->visible(function (Forms\Get $get) {
                    return $get('options.data-show') == 1;
                })
                ->label('Description')
                ->live(),
            TextInput::make('data-character-limit')
                ->visible(function (Forms\Get $get) {
                    return $get('options.data-show') == 1;
                })
                ->label('Description Limit')
                ->numeric()
                ->live(),
            Checkbox::make('options.data-show-read-more')
                ->visible(function (Forms\Get $get) {
                    return $get('options.data-show') == 1;
                })
                ->label('Read More')
                ->live(),
            TextInput::make('options.data-read-more-text')
                ->visible(function (Forms\Get $get) {
                    return $get('options.data-show') == 1;
                })
                ->label('Read more text')
                ->live(),
            Checkbox::make('options.data-show-date')
                ->visible(function (Forms\Get $get) {
                    return $get('options.data-show') == 1;
                })
                ->label('Created At')
                ->live(),
            TextInput::make('options.data-limit')
                ->visible(function (Forms\Get $get) {
                    return $get('options.data-show') == 1;
                })
                ->label('Post per page')
                ->numeric()
                ->live(),

        ];
    }
}
