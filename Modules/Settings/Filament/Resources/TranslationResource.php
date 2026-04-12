<?php

namespace Modules\Settings\Filament\Resources;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Actions\Action;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Translation\Models\TranslationKey;
use MicroweberPackages\Translation\Models\TranslationText;
use Modules\Settings\Filament\Resources\TranslationResource\Pages;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Filament\Notifications\Notification;

class TranslationResource extends Resource
{
    protected static ?string $model = TranslationKey::class;
    protected static ?string $recordTitleAttribute = 'translation_key';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-language';

    protected static string | \UnitEnum | null $navigationGroup = 'Language Settings';

    protected static string | null $navigationLabel = 'Translations';

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->translation_group) {
            $details['Group'] = $record->translation_group;
        }

        if ($record->translation_namespace && $record->translation_namespace !== '*') {
            $details['Namespace'] = $record->translation_namespace;
        }

        return $details;
    }

    protected static ?string $pluralModelLabel = 'Translations';

    protected static ?int $navigationSort = 10;
    protected static bool $shouldRegisterNavigation = false;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('translation_key')
                    ->label('Translation Key')
                    ->required()
                    ->maxLength(255)
                    ->helperText('The unique identifier for this translation'),

                TextInput::make('translation_namespace')
                    ->label('Namespace')
                    ->maxLength(255)
                    ->helperText('Optional namespace to group related translations'),

                TextInput::make('translation_group')
                    ->label('Translation Group')
                    ->maxLength(255)
                    ->default('default')
                    ->helperText('Group for organizing translations'),

                Textarea::make('translation_value_default')
                    ->label('Default Value')
                    ->helperText('The default text in the base language')
                    ->rows(3),

                Select::make('initial_locale')
                    ->label('Initial Language')
                    ->options(function () {
                        $languages = [];
                        if (function_exists('get_available_languages')) {
                            $languages = get_available_languages();
                        } else {
                            $defaultLang = function_exists('mw') ? mw()->lang_helper->default_lang() : 'en_US';
                            $languages[$defaultLang] = strtoupper($defaultLang) . ' - Default';
                        }
                        return $languages;
                    })
                    ->helperText('Create an initial translation for this language')
                    ->searchable(),

                Textarea::make('initial_translation')
                    ->label('Initial Translation')
                    ->helperText('The translated text for the selected language')
                    ->rows(3)
                    ->visible(fn(callable $get) => $get('initial_locale')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('translation_key')
                    ->label('Key')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->translation_key),

                TextColumn::make('translation_namespace')
                    ->label('Namespace')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('Global')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('translation_group')
                    ->label('Group')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('Default')
                    ->badge()
                    ->color('info'),

                TextColumn::make('translation_value_default')
                    ->label('Default Value')
                    ->limit(60)
                    ->wrap()
                    ->searchable()
                    ->toggleable(),

                BadgeColumn::make('translations_count')
                    ->label('Languages')
                    ->getStateUsing(function (TranslationKey $record) {
                        return $record->texts()->count();
                    })
                    ->color('success'),

                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('translation_namespace')
                    ->label('Namespace')
                    ->options(function () {
                        $namespaces = TranslationKey::distinct()
                            ->whereNotNull('translation_namespace')
                            ->where('translation_namespace', '!=', '')
                            ->pluck('translation_namespace', 'translation_namespace')
                            ->toArray();

                        return ['' => 'Global (No Namespace)'] + $namespaces;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        if (isset($data['value'])) {
                            if ($data['value'] === '') {
                                return $query->where(function ($q) {
                                    $q->whereNull('translation_namespace')
                                        ->orWhere('translation_namespace', '');
                                });
                            }
                            return $query->where('translation_namespace', $data['value']);
                        }
                        return $query;
                    }),

                SelectFilter::make('translation_group')
                    ->label('Translation Group')
                    ->options(function () {
                        $groups = TranslationKey::distinct()
                            ->whereNotNull('translation_group')
                            ->where('translation_group', '!=', '')
                            ->pluck('translation_group', 'translation_group')
                            ->toArray();

                        return ['default' => 'Default'] + $groups;
                    })
                    ->query(function (Builder $query, array $data): Builder {
                        if (isset($data['value'])) {
                            return $query->where('translation_group', $data['value']);
                        }
                        return $query;
                    }),

                Tables\Filters\Filter::make('has_translations')
                    ->label('Has Translations')
                    ->query(function (Builder $query, array $data): Builder {
                        if (isset($data['isActive']) && $data['isActive']) {
                            return $query->has('texts');
                        }
                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),

                    TableAction::make('quickTranslate')
                        ->label('Quick Translate')
                        ->icon('heroicon-o-plus')
                        ->color('success')
                        ->modal()
                        ->modalHeading('Add Translation')
                        ->form(function (TranslationKey $record) {
                            $supportedLanguages = get_supported_languages(true);
                            $existingLocales = $record->texts->pluck('translation_locale')->toArray();
                            $availableLanguages = [];

                            foreach ($supportedLanguages as $lang) {
                                if (!in_array($lang['locale'], $existingLocales)) {
                                    $availableLanguages[$lang['locale']] = $lang['display_name'] ?? $lang['locale'];
                                }
                            }

                            return [
                                Select::make('locale')
                                    ->label('Language')
                                    ->options($availableLanguages)
                                    ->required()
                                    ->searchable(),

                                Textarea::make('translation_text')
                                    ->label('Translation')
                                    ->required()
                                    ->rows(4)
                                    ->default($record->translation_value_default ?? $record->translation_key),
                            ];
                        })
                        ->action(function (TranslationKey $record, array $data): void {
                            TranslationText::create([
                                'translation_key_id' => $record->id,
                                'translation_locale' => $data['locale'],
                                'translation_text' => $data['translation_text'],
                            ]);

                            Notification::make()
                                ->title('Translation added successfully')
                                ->success()
                                ->send();
                        })
                        ->visible(function (TranslationKey $record) {
                            $supportedLanguages = get_supported_languages(true);
                            $existingLocales = $record->texts->pluck('translation_locale')->toArray();
                            return count($supportedLanguages) > count($existingLocales);
                        }),
                ])
                ->tooltip('Actions'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    BulkAction::make('add_translation')
                        ->label('Add Translation')
                        ->icon('heroicon-o-plus')
                        ->form([
                            Select::make('locale')
                                ->label('Target Language')
                                ->options(function () {
                                    $languages = [];
                                    $supportedLanguages = get_supported_languages(true);
                                    foreach ($supportedLanguages as $lang) {
                                        $languages[$lang['locale']] = $lang['display_name'] ?? $lang['locale'];
                                    }
                                    return $languages;
                                })
                                ->required()
                                ->searchable()
                                ->helperText('Select the language to add translations for'),
                        ])
                        ->action(function (Collection $records, array $data): void {
                            $count = 0;
                            foreach ($records as $record) {
                                $existingTranslation = $record->texts()
                                    ->where('translation_locale', $data['locale'])
                                    ->first();

                                if (!$existingTranslation) {
                                    TranslationText::create([
                                        'translation_key_id' => $record->id,
                                        'translation_locale' => $data['locale'],
                                        'translation_text' => $record->translation_value_default ?? $record->translation_key,
                                    ]);
                                    $count++;
                                }
                            }

                            Notification::make()
                                ->title("Added {$count} translations for " . strtoupper($data['locale']))
                                ->success()
                                ->send();
                        })
                        ->requiresConfirmation()
                        ->modalDescription('This will add translations for the selected language to all chosen records.'),
                ]),
            ])
            ->defaultSort('id', 'desc')
            ->searchable()
            ->persistFiltersInSession()
            ->persistSearchInSession();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTranslations::route('/'),
            'create' => Pages\CreateTranslation::route('/create'),
            'edit' => Pages\EditTranslation::route('/{record}/edit'),
        ];
    }
}
