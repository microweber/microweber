<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\MarketplaceResource\Pages;
use App\Filament\Admin\Resources\MarketplaceResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use MicroweberPackages\Filament\Tables\Columns\ImageUrlColumn;
use MicroweberPackages\Marketplace\Models\MarketplaceItem;
use MicroweberPackages\Module\Models\Module;

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
                Tables\Columns\Layout\Stack::make([

                    ImageUrlColumn::make('screenshot_link')
                        ->backgroundCropped(100)
                        ->imageUrl(function (MarketplaceItem $marketplaceItem) {
                            return $marketplaceItem->screenshot_link;
                        })->columnSpanFull(),

                    Tables\Columns\TextColumn::make('name')
                        ->searchable()
                        ->columnSpanFull()
                        ->weight(FontWeight::Bold),

                ])
                ->space(3)
                ->alignment(Alignment::Center),

            ])
            ->contentGrid([
                'md' => 3,
                'xl' => 3,
            ])
            ->paginationPageOptions([
                24,
                50,
                100,
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
            ])
            ->bulkActions([

            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([

                ImageEntry::make('screenshot_link')
                    ->defaultImageUrl(function (MarketplaceItem $marketplaceItem) {
                        return $marketplaceItem->screenshot_link;
                    })
                    ->columnSpanFull(),

                TextEntry::make('name'),

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
