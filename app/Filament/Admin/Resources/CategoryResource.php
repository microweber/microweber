<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\CategoryResource\Pages;
use App\Filament\Admin\Resources\CategoryResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Filament\Forms\Components\MwTree;
use Modules\Content\Models\Content;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    //protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Website';

    public static function form(Form $form): Form
    {

        $selectedPage = 0;
        $selectedCategories = [];
        $livewire = $form->getLivewire();
        $record = $form->getRecord();

        if ($record) {
            if ($record->parent_id) {
                $selectedCategories[] = $record->parent_id;
            } elseif ($record->rel_id) {
                $selectedPage = $record->rel_id;
            }
        }


        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([


                        MwTree::make('mw_parent_page_and_category_state')
                            ->live()
                            ->viewData([
                                'singleSelect' => true,
                                'selectedPage' => $selectedPage,
                                'selectedCategories' => $selectedCategories
                            ])
                            ->default([
                                //  'page' => $parent,
                                //'categories' => $category_ids
                            ])->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?array $old, ?array $state) {
                                if (!$state) {
                                    $set('parent_id', '');
                                    $set('rel_type', '');
                                    $set('rel_id', '');
                                }
                                if ($state) {
                                    foreach ($state as $item) {
                                        if (isset($item['type']) and $item['type'] == 'page') {
                                            $set('rel_type', morph_name(Content::class));
                                            $set('rel_id', $item['id']);
                                            $set('parent_id', '');
                                        }
                                        if (isset($item['type']) and $item['type'] == 'category') {
                                            $set('parent_id', $item['id']);
                                            $set('rel_type', '');
                                            $set('rel_id', '');
                                        }
                                    }
                                }
                            }),


                        Forms\Components\TextInput::make('id')
                            ->hidden(),
                        Forms\Components\TextInput::make('parent_id'),
                        Forms\Components\TextInput::make('rel_type'),
                        Forms\Components\TextInput::make('rel_id'),


                        Forms\Components\TextInput::make('title')
                            ->label('Title')
                            ->required(),
                        Forms\Components\TextInput::make('url')
                            ->label('Url')
                            ->required(),

                        Forms\Components\TextInput::make('description')->label('Description'),
                        Forms\Components\TextInput::make('category_meta_title')->label('Meta Title'),
                        Forms\Components\TextInput::make('category_meta_description')->label('Meta Description'),


                    ])
            ]);
    }

    public static function table(Table $table): Table
    {


        $table
            ->columns([

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('url')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->hidden(),
                Tables\Columns\TextColumn::make('category_meta_title')
                    ->searchable()
                    ->hidden(),
                Tables\Columns\TextColumn::make('category_meta_description')
                    ->searchable()
                    ->hidden(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);


        return $table;
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
