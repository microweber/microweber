<?php

namespace Modules\MailTemplate\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Modules\MailTemplate\Models\MailTemplate;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Modules\MailTemplate\Services\MailTemplateService;

class MailTemplateResource extends Resource
{
    protected static ?string $model = MailTemplate::class;

    protected static ?string $navigationGroup = 'Settings';

    public static function form(Form $form): Form
    {
        $service = app(MailTemplateService::class);
        
        return $form
            ->schema([
                Section::make('Template Details')
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
                                'unorderedList',
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
                //
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
