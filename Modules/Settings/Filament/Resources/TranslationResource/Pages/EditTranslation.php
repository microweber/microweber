<?php

namespace Modules\Settings\Filament\Resources\TranslationResource\Pages;

use Modules\Settings\Filament\Resources\TranslationResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use MicroweberPackages\Translation\Models\TranslationText;

class EditTranslation extends EditRecord
{
    protected static string $resource = TranslationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Translation Key Information')
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
                    ]),

                Section::make('Translations')
                    ->schema([
                        Repeater::make('existing_translations')
                            ->label('Existing Translations')
                            ->relationship('texts')
                            ->schema([
                                Select::make('translation_locale')
                                    ->label('Language')
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
                                    ->required()
                                    ->searchable(),

                                Textarea::make('translation_text')
                                    ->label('Translation')
                                    ->required()
                                    ->rows(3)
                                    ->helperText('The translated text for this language'),
                            ])
                            ->addActionLabel('Add Translation')
                            ->collapsible()
                            ->cloneable()
                            ->deleteAction(fn ($action) => $action->requiresConfirmation()),
                    ]),
            ]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
