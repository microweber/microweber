<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources;

use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SenderAccountResource\Pages\ManageSenderAccounts;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSenderAccount;

class SenderAccountResource extends Resource
{
    protected static ?string $model = NewsletterSenderAccount::class;

   // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Newsletter';

    protected static ?string $slug = 'newsletter/sender-accounts';

    protected static bool $shouldRegisterNavigation = false;

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
                //
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
            'index' => ManageSenderAccounts::route('/'),
        ];
    }
}
