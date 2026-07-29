<?php

declare(strict_types=1);

namespace MicroweberPackages\TemplateFonts\Filament\Resources;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use MicroweberPackages\TemplateFonts\Filament\Resources\TemplateFontResource\Pages\CreateTemplateFont;
use MicroweberPackages\TemplateFonts\Filament\Resources\TemplateFontResource\Pages\EditTemplateFont;
use MicroweberPackages\TemplateFonts\Filament\Resources\TemplateFontResource\Pages\ListTemplateFonts;
use MicroweberPackages\TemplateFonts\Models\TemplateFont;
use MicroweberPackages\TemplateFonts\Services\TemplateFontsManager;

class TemplateFontResource extends Resource
{
    protected static ?string $model = TemplateFont::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-language';

    protected static string|\UnitEnum|null $navigationGroup = 'Website Settings';

    protected static ?int $navigationSort = 56;

    protected static ?string $navigationLabel = 'Fonts';

    protected static ?string $modelLabel = 'Font';

    protected static ?string $pluralModelLabel = 'Fonts';

    protected static ?string $slug = 'template-fonts';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Font')
                    ->schema([
                        TextInput::make('family')
                            ->label('Font family')
                            ->required()
                            ->maxLength(255)
                            ->helperText('CSS font-family name, e.g. "Roboto" or "My Custom Font".'),

                        Select::make('provider')
                            ->label('Provider')
                            ->options([
                                TemplateFont::PROVIDER_GOOGLE => 'Google Fonts',
                                TemplateFont::PROVIDER_CUSTOM => 'Custom upload',
                                TemplateFont::PROVIDER_SYSTEM => 'System',
                            ])
                            ->required()
                            ->default(TemplateFont::PROVIDER_GOOGLE)
                            ->live(),

                        TextInput::make('category')
                            ->label('Category')
                            ->maxLength(64)
                            ->placeholder('serif, sans-serif, custom…'),

                        Toggle::make('is_enabled')
                            ->label('Enabled')
                            ->default(true)
                            ->helperText('Enabled fonts appear in the font picker and are loaded in CSS.'),

                        TextInput::make('sort_order')
                            ->label('Sort order')
                            ->numeric()
                            ->default(0),

                        FileUpload::make('upload')
                            ->label('Font file (TTF / WOFF / WOFF2 / OTF)')
                            ->disk('local')
                            ->directory('tmp-font-uploads')
                            ->acceptedFileTypes([
                                'font/ttf',
                                'font/otf',
                                'font/woff',
                                'font/woff2',
                                'application/font-woff',
                                'application/font-woff2',
                                'application/x-font-ttf',
                                'application/octet-stream',
                            ])
                            ->visible(fn ($get) => $get('provider') === TemplateFont::PROVIDER_CUSTOM)
                            ->dehydrated(false)
                            ->helperText('Upload a custom font file. Saved under the configured fonts path.'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('family')
                    ->label('Family')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('provider')
                    ->badge()
                    ->sortable(),

                TextColumn::make('category')
                    ->toggleable(),

                IconColumn::make('is_enabled')
                    ->label('Enabled')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('css_url')
                    ->label('CSS')
                    ->limit(40)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('provider')
                    ->options([
                        TemplateFont::PROVIDER_GOOGLE => 'Google',
                        TemplateFont::PROVIDER_CUSTOM => 'Custom',
                        TemplateFont::PROVIDER_SYSTEM => 'System',
                    ]),
                TernaryFilter::make('is_enabled')->label('Enabled'),
            ])
            ->defaultSort('sort_order')
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make()
                    ->after(function () {
                        app(TemplateFontsManager::class)->clearCssCache();
                    }),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make()
                        ->after(function () {
                            app(TemplateFontsManager::class)->clearCssCache();
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTemplateFonts::route('/'),
            'create' => CreateTemplateFont::route('/create'),
            'edit' => EditTemplateFont::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        if (function_exists('is_admin') && is_admin()) {
            return true;
        }

        try {
            $user = auth()->user();
            if ($user !== null && isset($user->is_admin) && (int) $user->is_admin === 1) {
                return true;
            }
        } catch (\Throwable) {
            // ignore
        }

        if (!function_exists('is_admin') && auth()->guest()) {
            return true;
        }

        return auth()->check();
    }
}
