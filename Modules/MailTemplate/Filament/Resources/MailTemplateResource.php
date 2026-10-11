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

use MicroweberPackages\FilamentRegistry\GlobalSearch\MicroweberGloballySearchable;
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
    use MicroweberGloballySearchable;

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
                    ->description('What this email is and who it is sent from.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Template name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Order confirmation')
                            ->helperText('Internal name — not shown to recipients.')
                            ->columnSpanFull(),

                        Select::make('type')
                            ->label('Template type')
                            ->options($service->getTemplateTypes())
                            ->required()
                            ->reactive()
                            ->searchable()
                            ->helperText('Determines which variables are available below.')
                            ->columnSpanFull(),

                        TextInput::make('from_name')
                            ->label('From name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('From Name')
                            ->default($service->getDefaultFromName()),

                        TextInput::make('from_email')
                            ->label('From email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('you@example.com')
                            ->default($service->getDefaultFromEmail()),

                        TextInput::make('copy_to')
                            ->label('Copy to (BCC)')
                            ->email()
                            ->maxLength(255)
                            ->placeholder('Optional')
                            ->helperText('Send a hidden copy of every email to this address.')
                            ->columnSpanFull(),

                        TextInput::make('subject')
                            ->label('Subject')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g. Your order {order_id} is confirmed')
                            ->helperText('Supports variables — click one below to copy it.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Template Content')
                    ->icon('heroicon-m-pencil-square')
                    ->description('The email body. Click a variable below to copy, then paste it here.')
                    ->schema([
                        RichEditor::make('message')
                            ->label('Message')
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
                            ->placeholder('Write your email…')
                            ->helperText('Supports the variables listed below (e.g. {customer_name}).')
                            ->columnSpanFull(),

                        Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Inactive templates are never sent.')
                            ->default(true),
                    ]),

                Section::make('Available Variables')
                    ->icon('heroicon-m-code-bracket')
                    ->description('Click any variable to copy it, then paste into the subject or content.')
                    ->schema([
                        Forms\Components\Placeholder::make('variables')
                            ->hiddenLabel()
                            ->content(function ($get) use ($service) {
                                $type = $get('type');
                                if (! $type) {
                                    return new \Illuminate\Support\HtmlString(
                                        '<p style="color:#6b7280;font-size:13.5px;margin:0;">Select a template type above to see the variables you can use.</p>'
                                    );
                                }

                                $variables = $service->getAvailableVariables($type);
                                if (empty($variables)) {
                                    return new \Illuminate\Support\HtmlString(
                                        '<p style="color:#6b7280;font-size:13.5px;margin:0;">No variables for this template type.</p>'
                                    );
                                }

                                $chips = '';
                                foreach ($variables as $var => $desc) {
                                    $chips .= '<button type="button" data-var="' . e($var) . '" '
                                        . 'onclick="navigator.clipboard&&navigator.clipboard.writeText(this.dataset.var);this.style.background=\'#dcfce7\';this.style.borderColor=\'#16a34a\';var s=this;setTimeout(function(){s.style.background=\'#f8fafc\';s.style.borderColor=\'rgba(0,0,0,.12)\';},900);" '
                                        . 'title="Copy ' . e($var) . '" '
                                        . 'style="display:inline-flex;align-items:center;gap:8px;margin:0 8px 8px 0;padding:6px 11px;border:1px solid rgba(0,0,0,.12);border-radius:8px;background:#f8fafc;cursor:pointer;font-size:13px;line-height:1.3;transition:background .15s,border-color .15s;">'
                                        . '<code style="color:#0d6efd;font-family:ui-monospace,SFMono-Regular,Menlo,monospace;">' . e($var) . '</code>'
                                        . '<span style="color:#6b7280;">' . e($desc) . '</span>'
                                        . '</button>';
                                }

                                return new \Illuminate\Support\HtmlString(
                                    '<div style="display:flex;flex-wrap:wrap;">' . $chips . '</div>'
                                );
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
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No mail templates')
            ->emptyStateDescription('Create email templates to customize the messages your site sends.')
            ->emptyStateIcon('heroicon-o-envelope');
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
