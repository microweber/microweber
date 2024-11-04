<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources;

use Filament\Actions\ImportAction;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Actions\ImportAction as ImportTableAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use League\Csv\Reader as CsvReader;
use League\Csv\Statement;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource\Pages\ManageSenderAccounts;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SubscribersResource\Pages\ManageSubscribers;
use MicroweberPackages\Modules\Newsletter\Filament\Exports\NewsletterSubscriberExporter;
use MicroweberPackages\Modules\Newsletter\Filament\Imports\NewsletterSubscriberImporter;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSenderAccount;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;

class SubscribersResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $label = 'Subscribers';

    protected static ?string $navigationGroup = 'Mail';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('email')
                    ->label('Email')
                    ->placeholder('Enter email')
                    ->required()
                    ->email(),

                TextInput::make('name')
                    ->label('Name')
                    ->placeholder('Enter name'),

                CheckboxList::make('lists')
                    ->relationship('lists', 'name')
                    ->label('Subscribed for lists')
                    ->options(function () {
                        $lists = [];
                        $lists[0] = 'Default';
                        $getLists = NewsletterList::query()->pluck('name', 'id');
                        if ($getLists) {
                            foreach ($getLists as $key => $value) {
                                $lists[$key] = $value;
                            }
                        }
                        return $lists;
                    })


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                    Tables\Columns\TextColumn::make('name')
                        ->searchable()
                        ->sortable()
                        ->weight('medium')
                        ->alignLeft(),

                    Tables\Columns\TextColumn::make('email')
                        ->label('Email address')
                        ->searchable()
                        ->sortable()
                        ->color('gray')
                        ->alignLeft(),

                    Tables\Columns\TextColumn::make('lists.name')
                        ->label('Lists')
                        ->listWithLineBreaks()
                        ->limitList(2)
                        ->expandableLimitedList(),

            ])
            ->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                \MicroweberPackages\Filament\Tables\Actions\ImportAction::make('importProducts')
                    ->icon('heroicon-m-cloud-arrow-up')
                    ->importer(NewsletterSubscriberImporter::class)
                    ->chunkSize(50),
                Tables\Actions\ExportAction::make()
                    ->icon('heroicon-m-cloud-arrow-down')
                    ->exporter(NewsletterSubscriberExporter::class),
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(NewsletterSubscriberExporter::class),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageSubscribers::route('/'),
        ];
    }
}
