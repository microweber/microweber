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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Actions\BulkAction;
use Filament\Actions\Action;
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

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-language';

    protected static string | \UnitEnum | null $navigationGroup = 'Language Settings';

    protected static string | null $navigationLabel = 'Translations';

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

                // Simplified approach - create one translation at a time
                Select::make('initial_locale')
                    ->label('Initial Language')
                    ->options(function () {
                        $languages = [];
                        if (function_exists('get_available_languages')) {
                            $languages = get_available_languages();

                        } else {
                            // Default language fallback
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
                    ->limit(40),

                TextColumn::make('translation_namespace')
                    ->label('Namespace')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('Global'),

                TextColumn::make('translation_group')
                    ->label('Group')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->placeholder('Default'),

                TextColumn::make('translation_value_default')
                    ->label('Default Value')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(),

//                TextColumn::make('translations_count')
//                    ->label('Languages')
//                    ->getStateUsing(function (TranslationKey $record) {
//                        return $record->texts()->count();
//                    })
//                    ->badge()
//                    ->color('success'),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                /* SelectFilter::make('translation_namespace')
                     ->label('Namespace')
                     ->options(function () {
                         $namespaces = TranslationKey::distinct()
                             ->whereNotNull('translation_namespace')
                             ->where('translation_namespace', '!=', '')
                             ->pluck('translation_namespace', 'translation_namespace')
                             ->toArray();

                         return [
                             '' => 'Global (No Namespace)',
                         ] + $namespaces;
                     })
                     ->query(function (Builder $query, array $data): Builder {
                         if (isset($data['value'])) {
                             if ($data['value'] === '') {
                                 return $query->where(function ($q) {
                                     $q->whereNull('translation_namespace')
                                       ->orWhere('translation_namespace', '');
                                 });
                             } else {
                                 return $query->where('translation_namespace', $data['value']);
                             }
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

                         return [
                             'default' => 'Default',
                         ] + $groups;
                     })
                     ->query(function (Builder $query, array $data): Builder {
                         if (isset($data['value'])) {
                             return $query->where('translation_group', $data['value']);
                         }
                         return $query;
                     }),
 */


            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    BulkAction::make('add_translation')
                        ->label('Add Translation')
                        ->icon('heroicon-o-plus')
                        ->form([
                            Select::make('locale')
                                ->label('Language')
                                ->options(function () {
                                    $languages = [];
                                    if (function_exists('get_available_languages')) {
                                        $languages = get_available_languages();

                                    }
                                    return $languages;
                                })
                                ->required(),
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
                        }),
                ]),
            ])
            ->searchable();
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
