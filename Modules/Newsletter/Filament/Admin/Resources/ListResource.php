<?php

namespace Modules\Newsletter\Filament\Admin\Resources;

use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Modules\Newsletter\Filament\Admin\Resources\CampaignResource\Pages\ManageCampaigns;
use Modules\Newsletter\Filament\Admin\Resources\ListResource\Pages\ManageLists;
use Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource\Pages\ManageSenderAccounts;
use Modules\Newsletter\Filament\Admin\Resources\TemplatesResource\Pages\ManageTemplates;
use Modules\Newsletter\Filament\Exports\NewsletterListExporter; // Added exporter import
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Models\NewsletterTemplate;
use Illuminate\Support\Arr;
use Filament\Forms\Components\Checkbox;


class ListResource extends Resource
{
    protected static ?string $model = NewsletterList::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

//    protected static ?string $slug = 'newsletter/sender-accounts';

//    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $label = 'Lists';

    protected static ?string $navigationGroup = 'Campaigns';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                TextInput::make('name')
                    ->label('Name')
                    ->placeholder('Enter name'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('subscribersCount'),
            ])
            ->filters([
                //
            ])
             ->headerActions([ // Added header actions
                 Tables\Actions\ExportAction::make()
                     ->icon('heroicon-m-cloud-arrow-down')
                     ->form(function (Tables\Actions\ExportAction $action): array {
                         $exportColumns = NewsletterListExporter::getColumns();
                         $formSchema = [];
                         foreach ($exportColumns as $column) {
                             $formSchema[] = \Filament\Forms\Components\Checkbox::make($column->getName())
                                 ->label($column->getLabel())
                                 ->default(true);
                         }
                         $formSchema[] = Checkbox::make('export_multiple')
                             ->label('Export to multiple files (ZIP)');
                         return $formSchema;
                     })
                     ->action(function (array $data) {
                         $selectedColumns = array_keys(array_filter(Arr::except($data, 'export_multiple')));
                         $exportMultiple = $data['export_multiple'] ?? false;
                         $url = route('filament.admin-newsletter.export.lists', ['columns' => $selectedColumns, 'export_multiple' => $exportMultiple]);
                         return redirect()->to($url);
                     }),
                 Tables\Actions\CreateAction::make(),
             ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\ExportBulkAction::make() // Added bulk export action
                        ->form(function (Tables\Actions\BulkAction $action): array {
                            $exportColumns = NewsletterListExporter::getColumns();
                            $formSchema = [];
                            foreach ($exportColumns as $column) {
                                $formSchema[] = \Filament\Forms\Components\Checkbox::make($column->getName())
                                    ->label($column->getLabel())
                                    ->default(true);
                            }
                            $formSchema[] = Checkbox::make('export_multiple')
                                ->label('Export to multiple files (ZIP)')
                                ->default(false);
                            return $formSchema;
                        })
                        ->action(function (array $data, Tables\Actions\BulkAction $action) {
                            $selectedColumns = array_keys(array_filter(Arr::except($data, 'export_multiple')));
                            $selectedRecordIds = $action->getRecords()->pluck('id')->toArray();
                            $route = route('filament.admin-newsletter.export.lists', ['columns' => $selectedColumns, 'selected_ids' => implode(',', $selectedRecordIds), 'export_multiple' => $data['export_multiple'] ?? false]);
                            return redirect()->to($route);
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageLists::route('/'),
        ];
    }
}
