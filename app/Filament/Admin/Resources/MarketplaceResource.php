<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MarketplaceResource\Pages;
use App\Filament\Admin\Resources\MarketplaceResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use MicroweberPackages\Marketplace\Models\MarketplaceItem;

class MarketplaceResource extends Resource
{
    protected static ?string $model = MarketplaceItem::class;
    protected static ?string $navigationIcon = 'mw-marketplace';
    protected static ?string $navigationLabel = 'Marketplace';

    protected static ?string $breadcrumb = 'Marketplace';

    protected static ?string $pluralLabel = 'Marketplaces';

    protected static ?string $slug = 'marketplace';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
            ])
            ->filters([
                //
            ])
            ->actions([

            ])
            ->bulkActions([

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
            'index' => Pages\ListMarketplaces::route('/'),
        ];
    }
}
