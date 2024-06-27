<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages;


use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Filament\Support\Enums\IconSize;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use MicroweberPackages\FormBuilder\Elements\RadioButton;
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource;
use MicroweberPackages\Modules\Newsletter\Filament\Components\SelectTemplate;
use MicroweberPackages\Modules\Newsletter\Filament\Imports\NewsletterSubscriberImporter;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaignsSendLog;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterList;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSenderAccount;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSubscriber;

class CreateCampaign extends Page
{
//    protected static ?string $slug = 'newsletter/create-campaign';

    protected static string $view = 'microweber-module-newsletter::livewire.filament.admin.create-campaign';

    protected static bool $shouldRegisterNavigation = false;

    public $state = [];

    public function form(Form $form): Form
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

        $recipientsOptions = [];

        if ($countSubscribers > 0) {
            $recipientsOptions['all_subscribers'] = 'All subscribers';
        }

     //   $recipientsOptions['import_new_list'] = 'Import new list';

        if (!empty($lists)) {
            $recipientsOptions['specific_lists'] = 'Specific lists';
        }

        $senderOptions = [];
        $senderIcons = [];
        $senderDescriptions = [];
        $senderIconsProviders = [
            'php_mail' => 'newsletter-php',
            'smtp' => 'newsletter-smtp',
            'mailchimp' => 'newsletter-mailchimp',
            'mailgun' => 'newsletter-mailgun',
            'mandrill' => 'newsletter-mandrill',
            'amazon_ses' => 'newsletter-amazon-ses',
            'sparkpost' => 'newsletter-sparkpost',
        ];
        $getSenderAccounts = NewsletterSenderAccount::all();
        if ($getSenderAccounts) {
            foreach ($getSenderAccounts as $senderAccount) {
                $senderOptions[$senderAccount->id] = $senderAccount->from_name;
                $senderIcons[$senderAccount->id] = $senderIconsProviders[$senderAccount->account_type];
                $senderDescriptions[$senderAccount->id] = $senderAccount->from_email;
            }
        }

        return $form
            ->model(NewsletterCampaign::class)
            ->schema([

                Wizard::make([

//                Wizard\Step::make('Content')
//                    ->icon('heroicon-o-document-text')
//                    ->schema([
//                        View::make('state.template')
//                            ->view('microweber-module-newsletter::livewire.filament.admin.template-editor-iframe')
//                    ]),
                    Wizard\Step::make('Email To')
                        ->icon('heroicon-o-users')
                        ->schema([

                            RadioDeck::make('state.recipientsFrom')
                                ->hintActions([
                                    Action::make('Import new subscribers')
                                        ->view('microweber-module-newsletter::livewire.filament.admin.render-import-subscribers-action')
                                ])
                                ->label('Select subscribers')
                                ->columns(count($recipientsOptions))
                                ->padding('py-4 px-8')
                                ->gap('gap-0')
                                ->extraCardsAttributes([ // Extra Attributes to add to the card HTML element
                                    'class' => 'rounded-xl'
                                ])
                                ->extraOptionsAttributes([ // Extra Attributes to add to the option HTML element
                                    'class' => 'text-lg leading-none w-full flex flex-col p-4'
                                ])
                                ->extraDescriptionsAttributes([ // Extra Attributes to add to the description HTML element
                                    'class' => 'text-sm font-light'
                                ])
                                ->icons([
                                    'all_subscribers' => 'heroicon-o-users',
                                    'specific_lists' => 'heroicon-o-list-bullet',
                                    'import_new_list' => 'heroicon-o-arrow-up-tray',
                                ])
                                ->iconSize(IconSize::Large)
                                ->color('primary')
                                ->live()
                                ->descriptions([
                                    'all_subscribers' => $countSubscribers . ' subscribers found in all lists.',
                                    'specific_lists' => 'Send to existing lists.',
                                    'import_new_list' => 'Create new list or import list from file.',
                                ])
                                ->options($recipientsOptions),

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

//                                Actions::make([
//                                    ImportAction::make('importProducts')
//                                        ->icon('heroicon-m-cloud-arrow-up')
//                                        ->importer(NewsletterSubscriberImporter::class),
//                                ]),

//                                TextInput::make('state.list_name')
//                                    ->label('List name'),
//                                MwFileUpload::make('state.upload_list_file')
//                                    ->label('Upload list file'),
                            ])->hidden(function (Get $get) {
                                if ($get('state.recipientsFrom') == 'import_new_list') {
                                    return false;
                                }
                                return true;
                            }),


                        ]),

                    Wizard\Step::make('From Email')
                        ->icon('heroicon-o-user')
                        ->schema([

                            RadioDeck::make('sender_account_id')
                                ->hintActions([
                                    Action::make('Manage Senders')
                                        ->link()
                                        ->url(admin_url('newsletter/sender-accounts'))
                                        ->openUrlInNewTab()
                                        ->icon('heroicon-o-cog'),

//                                    Action::make('Add new sender')
//                                        ->link()
//                                        ->icon('heroicon-o-arrow-trending-up')
//                                        ->form(SenderAccountsResource::getEditFormArray())
//                                        ->action(function (array $data): void {
//                                            $newsletterSenderAccount = new NewsletterSenderAccount();
//                                            $newsletterSenderAccount->fill($data);
//                                            $newsletterSenderAccount->save();
//                                        })
//                                        ->color('primary'),
                                ])
                                ->label('Select sender')
                                ->columns(2)
                                ->padding('py-4 px-8')
                                    ->gap('gap-0')
                                ->iconSize(IconSize::Large)
                                ->extraCardsAttributes([ // Extra Attributes to add to the card HTML element
                                    'class' => 'rounded-xl'
                                ])
                                ->extraOptionsAttributes([ // Extra Attributes to add to the option HTML element
                                    'class' => 'text-lg leading-none w-full flex flex-col p-4'
                                ])
                                ->extraDescriptionsAttributes([ // Extra Attributes to add to the description HTML element
                                    'class' => 'text-sm font-light'
                                ])
                                ->color('primary')
                                ->icons($senderIcons)
                                ->descriptions($senderDescriptions)
                                ->options($senderOptions),

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

                    Wizard\Step::make('Design')
                        ->icon('heroicon-o-paint-brush')
                        ->schema([
                            SelectTemplate::make('state.template'),
                        ]),

                    Wizard\Step::make('Send')
                        // ->description('Review and send your campaign.')
                        ->icon('heroicon-o-rocket-launch')
                        ->schema([



                        ])

                ])
                    ->columnSpanFull()
                    ->submitAction(new HtmlString(Blade::render(<<<BLADE
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

}
