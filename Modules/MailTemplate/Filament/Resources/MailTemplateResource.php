<?php

namespace Modules\MailTemplate\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Modules\MailTemplate\Models\MailTemplate;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Filament\Support\AdminFixtureGuard;
use Modules\MailTemplate\Services\MailTemplateService;

class MailTemplateResource extends Resource
{

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $model = MailTemplate::class;
    protected static ?string $recordTitleAttribute = 'name';

     protected static string | \UnitEnum | null $navigationGroup = 'Email Settings';

    protected static string $description = 'Manage email notification templates';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    public static function getDescription(): string
    {
        return static::$description;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'subject', 'type'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];

        if ($record->subject) {
            $details['Subject'] = $record->subject;
        }

        if ($record->type) {
            $details['Type'] = ucfirst(str_replace('_', ' ', $record->type));
        }

        return $details;
    }

    public static function form(Schema $schema): Schema
    {
        $service = app(MailTemplateService::class);

        return $schema
            ->schema([
                Section::make('Template Details')
                    ->icon('heroicon-m-document-text')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Template Name')
                            ->columnSpanFull(),

                        Select::make('type')
                            ->options($service->getTemplateTypes())
                            ->required()
                            ->reactive()
                            ->columnSpanFull(),

                        TextInput::make('from_name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('From Name')
                            ->default($service->getDefaultFromName())
                            ->columnSpanFull(),

                        TextInput::make('from_email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('From Email')
                            ->default($service->getDefaultFromEmail())
                            ->columnSpanFull(),

                        TextInput::make('copy_to')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('Copy To Email (Optional)')
                            ->columnSpanFull(),

                        TextInput::make('subject')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Email Subject')
                            ->columnSpanFull(),
                    ]),

                Section::make('Template Content')
                    ->icon('heroicon-m-pencil-square')
                    ->schema([
                        RichEditor::make('message')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'link',
                                'orderedList',
                                'bulletList',
                                'undo',
                                'redo',
                            ])
                            ->placeholder('Email Content')
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->columnSpanFull(),
                    ]),

                Section::make('Available Variables')
                    ->icon('heroicon-m-code-bracket')
                    ->schema([
                        Forms\Components\Placeholder::make('variables')
                            ->content(function ($get) use ($service) {
                                $type = $get('type');
                                if (!$type) {
                                    return 'Select a template type to see available variables.';
                                }

                                $variables = $service->getAvailableVariables($type);
                                $content = '<div class="space-y-2">';
                                foreach ($variables as $var => $desc) {
                                    $content .= "<div><code class='text-primary-600'>{$var}</code> - {$desc}</div>";
                                }
                                $content .= '</div>';

                                return new \Illuminate\Support\HtmlString($content);
                            })
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // task-2026-05-23-73e461 / AI-1055 — filter Faker/test records from production list.
            // The "Updated" record (name='Updated', Latin subject) was created by a test run
            // against the dev DB. We exclude rows where the subject contains any of the
            // AdminFixtureGuard's Faker lorem-ipsum words (the Latin text key-word list).
            // The name='Updated' alone is too generic to filter on; the Latin subject is the
            // reliable discriminator.
            ->modifyQueryUsing(function (Builder $query) {
                // Build a NOT LIKE exclusion for each Faker lorem ipsum keyword.
                // AdminFixtureGuard::FAKER_LOREM_WORDS is the canonical 158-word vocab.
                $fakerWords = AdminFixtureGuard::FAKER_LOREM_WORDS ?? [];
                if (!empty($fakerWords)) {
                    $query->where(function (Builder $q) use ($fakerWords) {
                        foreach (array_slice($fakerWords, 0, 20) as $word) {
                            $q->where('subject', 'NOT LIKE', '%' . $word . '%');
                        }
                    });
                }
                return $query;
            })
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('subject')
                    ->searchable()
                    ->sortable()
                    ->limit(50),

                TextColumn::make('from_email')
                    ->searchable()
                    ->sortable(),

            ToggleColumn::make('is_active')
                ->label('Active')
                ->sortable(),

            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable(),
        ])
        ->filters([
            Tables\Filters\TernaryFilter::make('is_active')
                ->label('Active Status')
                ->placeholder('All templates')
                ->trueLabel('Active templates')
                ->falseLabel('Inactive templates'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages\ListMailTemplates::class::route('/'),
            'create' => \Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages\CreateMailTemplate::class::route('/create'),
            'edit' => \Modules\MailTemplate\Filament\Resources\MailTemplateResource\Pages\EditMailTemplate::class::route('/{record}/edit'),
        ];
    }
}
