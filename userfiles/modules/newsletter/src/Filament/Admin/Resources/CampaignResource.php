<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\ManageCampaigns;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource\Pages\ManageSenderAccounts;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\TemplatesResource\Pages\ManageTemplates;
use MicroweberPackages\Modules\Newsletter\Filament\Components\SelectTemplate;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSenderAccount;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;

class CampaignResource extends Resource
{
    protected static ?string $model = NewsletterCampaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

//    protected static ?string $slug = 'newsletter/sender-accounts';

//    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $label = 'Campaigns';

    protected static ?string $navigationLabel = 'All';

    protected static ?string $navigationGroup = 'Campaigns';

    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('list.name'),
                TextColumn::make('subscribers'),
                TextColumn::make('scheduled'),
                TextColumn::make('scheduled_at'),
                TextColumn::make('done'),
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
            'index' => ManageCampaigns::route('/'),
        ];
    }
}