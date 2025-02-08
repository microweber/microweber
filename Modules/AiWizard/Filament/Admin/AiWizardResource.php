<?php

namespace Modules\AiWizard\Filament\Admin;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\AiWizard\Filament\Admin\AiWizardResource\Pages\AiWizardPageDesign;
use Modules\AiWizard\Filament\Admin\AiWizardResource\Pages\CreateAiWizardPage;
use Modules\AiWizard\Filament\Admin\AiWizardResource\Pages\EditAiWizardPage;
use Modules\AiWizard\Filament\Admin\AiWizardResource\Pages\ListAiWizardPages;
use Modules\Content\Models\Content;
use Modules\AiWizard\Services\Contracts\AiServiceInterface;

class AiWizardResource extends Resource
{
    protected static ?string $model = Content::class;

    // protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'Other';

    protected static ?string $navigationLabel = 'AI Page Wizard';

    protected static ?string $modelLabel = 'AI Page';

    protected static ?string $pluralModelLabel = 'AI Pages';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Create Page with AI')
                    ->description('Enter details about the page you want to create')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->maxLength(1000)
                            ->helperText('Describe what kind of page you want to create'),

                        Forms\Components\CheckboxList::make('sections')
                            ->label('Page Sections')
                            ->options([
                                'header' => 'Header Section',
                                'content' => 'Main Content',
                                'features' => 'Features Section',
                                'testimonials' => 'Testimonials Section',
                                'contact' => 'Contact Section',
                            ])
                            ->columns(2)
                            ->default(['header', 'content'])
                            ->required(),

                        Forms\Components\Hidden::make('content_type')
                            ->default('page'),

                        Forms\Components\Hidden::make('is_active')
                            ->default(1),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('AI Generation Settings')
                    ->schema([
                        Forms\Components\Select::make('ai_model')
                            ->label('AI Model')
                            ->options([
                                'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
                                'gpt-4' => 'GPT-4',
                            ])
                            ->default('gpt-3.5-turbo')
                            ->required(),

                        Forms\Components\Select::make('tone')
                            ->label('Content Tone')
                            ->options([
                                'professional' => 'Professional',
                                'casual' => 'Casual',
                                'friendly' => 'Friendly',
                                'formal' => 'Formal',
                            ])
                            ->default('professional')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                static::getModel()::query()
                    ->where('content_type', 'page')
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Published'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('view')
                    ->url(fn(Content $record) => $record->link())
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-eye'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' =>  ListAiWizardPages::route('/'),
            'create' =>  CreateAiWizardPage::route('/create'),
            'edit' => EditAiWizardPage::route('/{record}/edit'),
            'design' =>  AiWizardPageDesign::route('/{record}/design'),
        ];
    }
}
