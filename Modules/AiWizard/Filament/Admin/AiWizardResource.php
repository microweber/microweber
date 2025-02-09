<?php

namespace Modules\AiWizard\Filament\Admin;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Modules\AiWizard\Filament\Admin\AiWizardResource\Pages\AiWizardPageDesign;
use Modules\AiWizard\Filament\Admin\AiWizardResource\Pages\CreateAiWizardPage;
use Modules\AiWizard\Filament\Admin\AiWizardResource\Pages\EditAiWizardPage;
use Modules\AiWizard\Filament\Admin\AiWizardResource\Pages\ListAiWizardPages;
use Modules\Content\Models\Content;
use Modules\Ai\Services\Contracts\AiServiceInterface;

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

        $layouts = module_templates('layouts');

        $uniqueId = 'layout_' . uniqid();
        $iterator = 0;
        // Group layouts by category
        $groupedLayouts = collect($layouts)->groupBy('category')->map(function ($items) use (&$iterator, $uniqueId) {
            return $items->map(function ($item) use (&$iterator, $uniqueId) {
                return [
                    'id' => $uniqueId . '_' . $iterator++,
                    'layout_file' => $item['layout_file'],
                    'name' => $item['name'],
                    'screenshot' => $item['screenshot_public_url'] ?? null,
                ];
            });
        })->toArray();

        return $form
            ->schema([
                Forms\Components\Section::make('Page Details')
                    ->description('Enter the basic details for your page')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->maxLength(1000)
                            ->helperText('Describe what kind of page you want to create'),

                        Forms\Components\Hidden::make('content_type')
                            ->default('page'),

                        Forms\Components\Hidden::make('is_active')
                            ->default(1),
                    ])
                    ->columns(1),

                Forms\Components\Section::make('Select Layouts')
                    ->description('Choose the layouts you want to use for your page')
                    ->schema(
                        collect($groupedLayouts)->map(function ($layouts, $category) {
                            return Forms\Components\Group::make()
                                ->label(ucfirst($category))
                                ->schema([
                                    Forms\Components\CheckboxList::make("layouts.{$category}")
                                        ->label('')
                                        ->options(collect($layouts)->pluck('name', 'layout_file')->toArray())
                                        ->descriptions(collect($layouts)->mapWithKeys(function ($layout) {
                                            return [$layout['layout_file'] => new HtmlString("<img loading='lazy' src='{$layout['screenshot']}' style='display:none;max-width: 200px; margin-top: 10px;' />")];
                                        })->toArray())
                                        ->columns(3)
                                ]);
                        })->values()->toArray()
                    ),
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
            'index' => ListAiWizardPages::route('/'),
            'create' => CreateAiWizardPage::route('/create'),
            'edit' => EditAiWizardPage::route('/{record}/edit'),
            'design' => AiWizardPageDesign::route('/{record}/design'),
        ];
    }
}
