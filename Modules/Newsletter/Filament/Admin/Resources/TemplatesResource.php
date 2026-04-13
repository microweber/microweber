<?php

namespace Modules\Newsletter\Filament\Admin\Resources;

use Filament\Schemas\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\View;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource\Pages\ManageSenderAccounts;
use Modules\Newsletter\Filament\Admin\Resources\TemplatesResource\Pages\ManageTemplates;
use Modules\Newsletter\Filament\Components\SelectTemplate;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Models\NewsletterTemplate;

class TemplatesResource extends Resource
{
    protected static ?string $model = NewsletterTemplate::class;

    protected static ?string $recordTitleAttribute = 'title';

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-paint-brush';

//    protected static ?string $slug = 'newsletter/sender-accounts';

//    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $label = 'Designs';

    protected static string | \UnitEnum | null $navigationGroup = 'Mail';

    protected static ?int $navigationSort = 3;

    public static function getGloballySearchableAttributes(): array
    {
        return ['title'];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('created_at')
            ])
            ->filters([
                //
            ])
->actions([
            Tables\Actions\Action::make('Edit')
                ->icon('heroicon-o-pencil')
                ->url(function ($record) {
                    return route('filament.admin-newsletter.pages.template-editor') . '?id=' . $record->id;
                }),
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
            'index' => ManageTemplates::route('/'),
        ];
    }
}
