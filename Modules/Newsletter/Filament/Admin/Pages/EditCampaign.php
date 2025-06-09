<?php

namespace Modules\Newsletter\Filament\Admin\Pages;


use BobiMicroweber\FilamentFlatpickr\Forms\Components\Flatpickr;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\IconSize;
use Filament\Support\Exceptions\Halt;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use MicroweberPackages\Filament\Forms\Components\MwFileUpload;
use Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource;
use Modules\Newsletter\Filament\Components\SelectTemplate;
use Modules\Newsletter\Models\NewsletterCampaign;
use Modules\Newsletter\Models\NewsletterList;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Models\NewsletterSubscriber;
use Livewire\Attributes\On;
use Modules\Newsletter\Models\NewsletterTemplate;
use Modules\Newsletter\Senders\NewsletterMailSender;
use Tapp\FilamentTimezoneField\Forms\Components\TimezoneSelect;

class EditCampaign extends Page
{
    protected static ?string $slug = 'edit-campaign/{id}';

    protected static string $view = 'microweber-module-newsletter::livewire.filament.admin.edit-campaign';

    protected static bool $shouldRegisterNavigation = false;

    public $state = [];
    private $model;

    #[On('subscribers-imported')]
    public function subscribersImported($listId = null) {

        $this->state['recipients_from'] = 'specific_list';
        $this->state['list_id'] = $listId;

        $campaign = NewsletterCampaign::where('id', $this->state['id'])->first();
        if ($campaign) {
            $campaign->fill([
                'recipients_from'=>'specific_list',
                'list_id'=>$listId
            ]);
            $campaign->save();
            $this->model = $campaign;
        }

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

    public function deleteTemplate()
    {
        $findCampaign = NewsletterCampaign::where('id', $this->state['id'])->first();
        if (!$findCampaign) {
            return;
        }

        $findCampaign->email_template_id = null;
        $findCampaign->save();

    }

    public function startWithTemplateById($templateId)
    {
        $findNewsletterTemplate = NewsletterTemplate::where('id', $templateId)->first();
        if (!$findNewsletterTemplate) {
            return;
        }

        $findCampaign = NewsletterCampaign::where('id', $this->state['id'])->first();
        if (!$findCampaign) {
            return;
        }


        $findCampaign->email_template_id = $findNewsletterTemplate->id;
        $findCampaign->save();

        return redirect(route('filament.admin.pages.newsletter.template-editor') . '?id=' . $findNewsletterTemplate->id . '&campaignId=' . $findCampaign->id);


    }
    public function startWithTemplate($template)
    {
        $templateJson = file_get_contents(module_path('newsletter'). '/resources/views/email-templates/' . $template. '.json');
        if (!$templateJson) {
            return;
        }

        $findCampaign = NewsletterCampaign::where('id', $this->state['id'])->first();
        if (!$findCampaign) {
            return;
        }

        $newTemplate = new NewsletterTemplate();
        $newTemplate->title = $findCampaign->name . ' ' . ucfirst($template);
        $newTemplate->json = $templateJson;
        $newTemplate->save();

        $findCampaign->email_template_id = $newTemplate->id;
        $findCampaign->save();

        return redirect(route('filament.admin.pages.newsletter.template-editor') . '?id=' . $newTemplate->id . '&campaignId=' . $findCampaign->id);
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

     //   $recipientsOptions['import_new_list'] = 'Restore new list';

        if (!empty($lists)) {
            $recipientsOptions['specific_list'] = 'Specific lists';
        }

        $senderOptions = [];
        $senderIcons = [];
        $senderDescriptions = [];
        $senderIconsProviders = [
            'php_mail' => 'newsletter-php',
            'gmail' => 'newsletter-smtp',
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
                                ->required()
                                ->hintActions([
                                    Action::make('Restore new subscribers')
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
                                    'specific_list' => 'heroicon-o-list-bullet',
                                    'import_new_list' => 'heroicon-o-arrow-up-tray',
                                ])
                                ->iconSize(IconSize::Large)
                                ->color('primary')
                                ->live()
                                ->descriptions([
                                    'all_subscribers' => $countSubscribers . ' subscribers found in all lists.',
                                    'specific_list' => 'Send to existing lists.',
                                    'import_new_list' => 'Create new list or import list from file.',
                                ])
                                ->options($recipientsOptions),

                            Radio::make('state.list_id')
                                ->label('Select list')
                                ->live()
                                ->required(function (Get $get) {
                                    if ($get('state.recipients_from') == 'specific_list') {
                                        return true;
                                    }
                                    return false;
                                })
                                ->hidden(function (Get $get) {
                                    if ($get('state.recipients_from') == 'specific_list') {
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
                                ->required()
                                ->live()
                                ->hintActions([
                                    Action::make('Add Sender')
                                        ->after(function ($data) {
                                            $new = new NewsletterSenderAccount();
                                            $new->fill($data);
                                            $new->save();
                                        })
                                        ->button()
                                        ->size('xl')
                                        ->color('primary')
                                        ->form(SenderAccountsResource::getEditFormArray())
                                        ->icon('heroicon-o-plus'),
                                    Action::make('Manage Senders')
                                        ->button()
                                        ->size('xl')
                                        ->outlined()
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

                    Wizard\Step::make('Content')
                        ->icon('heroicon-o-paint-brush')
                        ->beforeValidation(function () {
                            if ($this->state['email_content_type'] == 'design') {
                                if (!isset($this->state['email_template_id'])) {
                                    throw new Halt('Please select a design.');
                                }
                            }
                        })
                        ->schema([

                            TextInput::make('state.subject')
                                ->label('E-mail Subject')
                                ->helperText('Enter the subject of your email.')
                                ->required()
                                ->live(),

                            RadioDeck::make('state.email_content_type')
                                ->columns(2)
                                ->required()
                                ->icons([
                                    'html' => 'heroicon-o-pencil',
                                    'design' => 'heroicon-o-paint-brush',
                                ])
                                ->color('primary')
                                ->live()
                                ->descriptions([
                                    'html' => 'Send simple text email.',
                                    'design' => 'Send designed email.',
                                ])
                                ->default('design')
                                ->options([
                                    'design' => 'Design',
                                    'html' => 'Text',
                                ]),

                            RichEditor::make('state.email_content_html')
                                ->label('E-mail Content')
                                ->placeholder('Enter the plain text of your email.')
                                ->helperText('You can use the following variables: {{name}}, {{email}}, {{unsubscribe_url}}')
                                ->live()
                                ->hidden(function (Get $get) {
                                    if ($get('state.email_content_type') == 'html') {
                                        return false;
                                    }
                                    return true;
                                })
                                ->required(),

                            Toggle::make('state.enable_email_attachments')
                                     ->live(),

                            MwFileUpload::make('state.email_attached_files')
                                ->label('E-mail Attachments')
                                ->multiple()
                                ->hidden(function(Get $get) {
                                    if ($get('state.enable_email_attachments')) {
                                        return false;
                                    }
                                    return true;
                                })
                                ->fileTypes([
                                    // documents
                                    'application/pdf',
                                    'application/msword',
                                    'application/vnd.ms-excel',
                                    'application/vnd.ms-powerpoint',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                                    'application/vnd.oasis.opendocument.text',
                                    'application/vnd.oasis.opendocument.spreadsheet',
                                    'application/vnd.oasis.opendocument.presentation',
                                    'application/rtf',
                                    'application/txt',
                                    // images
                                    'image/jpeg',
                                    'image/png',
                                    'image/gif',
                                    'image/svg+xml',
                                    'image/webp',
                                    'image/tiff',
                                    'image/bmp',
                                    // video
                                    'video/mp4',
                                    'video/mpeg',
                                    'video/quicktime',
                                    'video/x-msvideo',
                                    'video/x-ms-wmv',
                                    'video/x-flv',
                                    'video/webm',
                                    'video/3gpp',
                                    'video/3gpp2',
                                    'video/avi',
                                    'video/mkv',
                                    'video/ogg',
                                    'video/mov',
                                    'video/wmv',
                                    'video/3gp',
                                    'video/flv',
                                    'video/mpg',
                                    'video/m4v',
                                    'video/asf',
                                ])
                                ->live(),

                            SelectTemplate::make('state.email_template_id')
                                ->label('Select design')
                                ->hidden(function (Get $get) {
                                    if ($get('state.email_content_type') == 'design') {
                                        return false;
                                    }
                                    return true;
                                })
                                ->required(function (Get $get) {
                                    if ($get('state.email_content_type') == 'design') {
                                        return true;
                                    }
                                    return false;
                                })
                                ->setCampaignId($this->state['id']),
                        ]),

                    Wizard\Step::make('Schedule')
                        // ->description('Select when you would you like this email to launch.')
                        ->icon('heroicon-o-calendar-days')
                        ->schema([

                            RadioDeck::make('state.delivery_type')
                                ->columns(2)
                                ->required()
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

                            Group::make([

                                Flatpickr::make('state.scheduled_at')
                                    ->enableTime()
                                    ->live(),

                                Select::make('state.scheduled_timezone')
                                ->options([
                                    'GMT'=>'GMT',
                                    'UTC'=>'UTC'
                                ])

//                                TimezoneSelect::make('state.scheduled_timezone')
//                                    ->searchable()
//                                    ->timezoneType('GMT')
//                                    ->default(date_default_timezone_get())
//                                    ->required(),

                            ])
                                ->columns(2)
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

                                TextInput::make('state.delay_between_sending_emails')
                                    ->label('Delay between sending emails')
                                    ->helperText('Set the delay between sending emails in seconds. Default is 2 seconds')
                                    ->suffix('Seconds')
                                    ->numeric()
                                    ->maxValue(15)
                                    ->minValue(0.1)
                                    ->default(2)
                                    ->live(),

//                                TextInput::make('state.sending_limit_per_day')
//                                    ->label('Sending limit (Per day)')
//                                    ->helperText('Set the maximum number of emails to be sent per day ')
//                                    ->numeric()
//                                    ->default(300)
//                                    ->live()
//                                    ->label('Sending limit'),

                            ])
                                ->columns(2)
                                ->hidden(function (Get $get) {
                                    if ($get('state.advanced_options')
                                        && $get('state.delivery_type') == 'schedule') {
                                        return false;
                                    }
                                    return true;
                                }),

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
                                        $findCampaign = NewsletterCampaign::where('id', $this->state['id'])->first();
                                        if ($findCampaign) {
                                            if ($findCampaign->delivery_type == NewsletterCampaign::DELIVERY_TYPE_SCHEDULE) {
                                                $findCampaign->status = NewsletterCampaign::STATUS_SCHEDULED;
                                            } else {
                                                $findCampaign->status = NewsletterCampaign::STATUS_PENDING;
                                            }
                                            $findCampaign->save();
                                        }
                                        return $this->redirect(route('filament.admin-newsletter.resources.campaigns.index'));
                                    }),
                                Action::make('Preview E-mail')
                                    ->label('Preview E-mail')
                                    ->link()
                                    ->url(function() {
                                        return admin_url('modules/newsletter/preview-campaign-email').'?id='.$this->state['id'];
                                    })
                                    ->openUrlInNewTab()
                                    ->icon('heroicon-o-eye'),
                                Action::make('Send test E-mail')
                                    ->modalSubmitActionLabel('Send test E-mail')
                                    ->icon('heroicon-o-beaker')
                                    ->link()
                                    ->form([
                                        TextInput::make('testName')
                                                ->label('Test name')
                                                ->live(),
                                        TextInput::make('testEmail')
                                            ->label('Test email')
                                            ->email()
                                            ->required()
                                            ->live(),
                                    ])
                                    ->action(function (array $data) {

                                        $testName = $data['testName'];
                                        $testEmail = $data['testEmail'];

                                        try {
                                            $campaign = NewsletterCampaign::where('id', $this->state['id'])->first();
                                            $sender = NewsletterSenderAccount::where('id', $this->state['sender_account_id'])->first();

                                            $templateArray = [];
                                            if ($this->state['email_content_type'] == 'design') {
                                                $template = NewsletterTemplate::where('id', $this->state['email_template_id'])->first();
                                                $templateArray = $template->toArray();
                                            } else {
                                                $templateArray['text'] = $this->state['email_content_html'];
                                            }

                                            $newsletterMailSender = new NewsletterMailSender();
                                            $newsletterMailSender->setCampaign($campaign->toArray());
                                            $newsletterMailSender->setSubscriber([
                                                'name' => $testName,
                                                'first_name' => $testName,
                                                'email' => $testEmail,
                                            ]);
                                            $newsletterMailSender->setSender($sender->toArray());
                                            $newsletterMailSender->setTemplate($templateArray);
                                            $sendMailResponse = $newsletterMailSender->sendMail();
                                            if (isset($sendMailResponse['success']) and $sendMailResponse['success'] == true) {
                                                Notification::make()
                                                    ->title('Test campaign sent successfully')
                                                    ->success()
                                                    ->send();
                                            } else if (isset($sendMailResponse['success']) and $sendMailResponse['success'] == false) {
                                                $error = $sendMailResponse['message'] ?? 'Error sending test campaign';
                                                Notification::make()
                                                    ->title('Error sending test campaign')
                                                    ->body($error)
                                                    ->danger()
                                                    ->send();
                                            }
                                        } catch (\Exception $e) {
                                            Notification::make()
                                                ->title('Error sending test campaign')
                                                ->body($e->getMessage())
                                                ->danger()
                                                ->send();
                                        }
                                    }),

                            ])->alignCenter(),


                        ]),

                ])->persistStepInQueryString()
                ->columnSpanFull(),

            ]);
    }

}
