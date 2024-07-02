<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Pages;


use Filament\Actions\CreateAction;
use Filament\Actions\ImportAction;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Page;
use Filament\Support\Colors\Color;
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
use Livewire\Attributes\On;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterTemplate;

class EditCampaign extends Page
{
    protected static ?string $slug = 'newsletter/edit-campaign/{id}';

    protected static string $view = 'microweber-module-newsletter::livewire.filament.admin.edit-campaign';

    protected static bool $shouldRegisterNavigation = false;

    public $state = [];
    private $model;

    #[On('subscribers-imported')]
    public function subscribersImported($listId = null) {

        $this->state['recipients_from'] = 'specific_lists';
        $this->state['list_id'] = $listId;
    }

    public function updated($key, $value)
    {
        $key = str_replace('state.', '', $key);

        $campaign = NewsletterCampaign::where('id', $this->state['id'])->first();

        if ($campaign) {
            $campaign->fill([$key => $value]);
            $campaign->save();
            $this->model = $campaign;
        }

    }

    public function mount()
    {
        $id = request()->route('id');
        $campaign = NewsletterCampaign::where('id', $id)->first();

        if ($campaign) {
            $this->model = $campaign;
            $this->state = $campaign->toArray();
        }
    }

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
            ->fill($this->state)
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

                            RadioDeck::make('state.recipients_from')
                                ->live()
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
                                ->live()
                                ->hidden(function (Get $get) {
                                    if ($get('state.recipients_from') == 'specific_lists') {
                                        return false;
                                    }
                                    return true;
                                })
                                ->descriptions($listDescriptions)
                                ->options($lists),


                        ]),

                    Wizard\Step::make('From Email')
                        ->icon('heroicon-o-user')
                        ->schema([
                            RadioDeck::make('state.sender_account_id')
                                ->live()
                                ->hintActions([
                                    Action::make('Manage Senders')
                                        ->link()
                                        ->url(admin_url('newsletter/sender-accounts'))
                                        ->openUrlInNewTab()
                                        ->icon('heroicon-o-cog'),
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

                            RadioDeck::make('state.delivery_type')
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
                                ->default('send_now')
                                ->options([
                                    'send_now' => 'Send now',
                                    'schedule' => 'Schedule',
                                ]),

                            DateTimePicker::make('state.scheduled_at')
                                ->live()
                                ->hidden(function (Get $get) {
                                    if ($get('state.delivery_type') == 'schedule') {
                                        return false;
                                    }
                                    return true;
                                }),

                            Checkbox::make('state.advanced_options')
                                ->label('Advanced options')
                                ->hidden(function (Get $get) {
                                    if ($get('state.delivery_type') == 'schedule') {
                                        return false;
                                    }
                                    return true;
                                })
                                ->live(),

                            Group::make([

                                TextInput::make('state.sending_limit_per_day')
                                    ->label('Sending limit (Per day)')
                                    ->helperText('Set the maximum number of emails to be sent per day ')
                                    ->numeric()
                                    ->default(300)
                                    ->live()
                                    ->label('Sending limit'),

                            ])->hidden(function (Get $get) {
                                if ($get('state.advanced_options')
                                    && $get('state.delivery_type') == 'schedule') {
                                    return false;
                                }
                                return true;
                            }),

                        ]),

                    Wizard\Step::make('Design')
                        ->icon('heroicon-o-paint-brush')
                        ->schema([

                            Select::make('state.email_template_id')
                                ->live()
                                ->label('Select Design')
                                ->options(NewsletterTemplate::all()->pluck('title', 'id')),

//                            SelectTemplate::make('state.template'),
                        ]),

                    Wizard\Step::make('Send')
                        // ->description('Review and send your campaign.')
                        ->icon('heroicon-o-rocket-launch')
                        ->schema([

                            View::make('state.preview')
                                ->view('microweber-module-newsletter::livewire.filament.admin.preview-campaign',[
                                    'model' => $this->model,
                                ]),

                            Actions::make([
                                Action::make('Send campaign')
                                    ->icon('heroicon-o-rocket-launch')
                                    ->requiresConfirmation(true)
                                    ->after(function () {
                                        // Send campaign
                                        dd($this->state);
                                    }),
                                Action::make('Preview E-mail')
                                    ->label('Preview E-mail')
                                    ->link()
                                    ->url(function() {
                                        return admin_url('modules/newsletter/preview-email-template').'?filename=mockup1';
                                    })
                                    ->openUrlInNewTab()
                                    ->icon('heroicon-o-eye'),

                            ])->alignCenter(),


                        ])->afterValidation(function () {
                            dd($this->state);
                        })

                ])->persistStepInQueryString()
                ->columnSpanFull(),

            ]);
    }

}
