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



    public static function form(Form $form): Form
    {

        $lists = [];
        $listDescriptions = [];
        $findLists = NewsletterList::all();
        if ($findLists) {
            foreach ($findLists as $list) {
                $lists[$list->id] = $list->name;
                $listDescriptions[$list->id] =  $list->subscribers()->count() . ' subscribers found in this list.';
            }
        }
        $countSubscribers = NewsletterSubscriber::count();

        return $form
            ->model(NewsletterCampaign::class)
            ->schema([

                Wizard::make([
                Wizard\Step::make('Design')
                    ->icon('heroicon-o-paint-brush')
                    ->schema([
                        SelectTemplate::make('state.template'),
                    ]),
//                Wizard\Step::make('Content')
//                    ->icon('heroicon-o-document-text')
//                    ->schema([
//                        View::make('state.template')
//                            ->view('microweber-module-newsletter::livewire.filament.admin.template-editor-iframe')
//                    ]),
                    Wizard\Step::make('Recipients')
                        ->icon('heroicon-o-users')
                        ->schema([

                            RadioDeck::make('state.recipientsFrom')
                                ->columns(3)
                                ->icons([
                                    'all_subscribers' => 'heroicon-o-users',
                                    'specific_lists' => 'heroicon-o-list-bullet',
                                    'import_new_list' => 'heroicon-o-arrow-up-tray',
                                ])
                                ->color('primary')
                                ->live()
                                ->descriptions([
                                    'all_subscribers' => $countSubscribers . ' subscribers found in all lists.',
                                    'specific_lists' => 'Send to existing lists.',
                                    'import_new_list' => 'Create new list or import list from file.',
                                ])
                                ->options([
                                    'all_subscribers' => 'All subscribers',
                                    'specific_lists' => 'Specific lists',
                                    'import_new_list' => 'Import new list',
                                ]),

                            Radio::make('state.list_id')
                                ->label('Select list')
                                ->hidden(function (Get $get) {
                                    if ($get('state.recipientsFrom') == 'specific_lists') {
                                        return false;
                                    }
                                    return true;
                                })
                                ->descriptions($listDescriptions)
                                ->options($lists),

                            Group::make([
                                TextInput::make('state.list_name')
                                    ->label('List name'),
                                MwFileUpload::make('state.upload_list_file')
                                    ->label('Upload list file'),
                            ])->hidden(function (Get $get) {
                                if ($get('state.recipientsFrom') == 'import_new_list') {
                                    return false;
                                }
                                return true;
                            }),


                        ]),
                    Wizard\Step::make('Schedule')
                        // ->description('Select when you would you like this email to launch.')
                        ->icon('heroicon-o-calendar-days')
                        ->schema([

                            RadioDeck::make('state.deliveryType')
                                ->columns(2)
                                ->icons([
                                    'send_now' => 'heroicon-o-rocket-launch',
                                    'schedule' => 'heroicon-o-clock',
                                ])
                                ->color('primary')
                                ->live()
                                ->descriptions([
                                    'send_now' => 'Send campaign now.',
                                    'schedule' => 'Schedule campaign for later.',
                                ])
                                ->options([
                                    'send_now' => 'Send now',
                                    'schedule' => 'Schedule',
                                ]),

                            DateTimePicker::make('state.scheduled_at')
                                ->hidden(function (Get $get) {
                                    if ($get('state.deliveryType') == 'schedule') {
                                        return false;
                                    }
                                    return true;
                                }),

                            Checkbox::make('state.advanceOptions')
                                ->label('Advance options')
                                ->live(),

                            Group::make([

                                TextInput::make('state.sendingLimit')
                                    ->label('Sending limit (Per day)')
                                    ->helperText('Set the maximum number of emails to be sent per day ')
                                    ->numeric()
                                    ->default(300)
                                    ->label('Sending limit'),

                            ])->hidden(function (Get $get) {
                                if ($get('state.advanceOptions')
                                    && $get('state.deliveryType') == 'schedule') {
                                    return false;
                                }
                                return true;
                            }),

                        ]),

                    Wizard\Step::make('Send')
                        // ->description('Review and send your campaign.')
                        ->icon('heroicon-o-rocket-launch')
                        ->schema([

                        ])

                ])->submitAction(new HtmlString(Blade::render(<<<BLADE
                        <x-filament::button
                            type="submit"
                            size="lg"
                            icon="heroicon-o-rocket-launch"
                        >
                            Send campaign
                        </x-filament::button>
                    BLADE)))
                    ->persistStepInQueryString(),

            ]);
    }

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
