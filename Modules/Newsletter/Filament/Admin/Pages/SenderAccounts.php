<?php

namespace Modules\Newsletter\Filament\Admin\Pages;

use Filament\Schemas\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Pages\Page;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Modules\Newsletter\Models\NewsletterSenderAccount;

class SenderAccounts extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'newsletter/sender-accounts';

    protected string $view = 'microweber-module-newsletter::livewire.filament.admin.sender-accounts';

    public static function table(Table $table): Table
    {

        $editForm = [


            Wizard::make([
                Wizard\Step::make('Mail Provider')
                    ->schema([

                        RadioDeck::make('account_type')
                            ->label('Send email function')
                            ->options([
                                'php_mail' => 'PHP Mail',
                                'gmail' => 'SMTP Server',
                                'mailchimp' => 'Mailchimp',
                                'mailgun' => 'Mailgun',
                                'mandrill' => 'Mandrill',
                                'amazon_ses' => 'Amazon SES',
                                'sparkpost' => 'Sparkpost',
                            ])
                            ->icons([
                                'php_mail' => 'newsletter-php',
                                'smtp' => 'newsletter-smtp',
                                'gmail' => 'newsletter-smtp',
                                'mailchimp' => 'newsletter-mailchimp',
                                'mailgun' => 'newsletter-mailgun',
                                'mandrill' => 'newsletter-mandrill',
                                'amazon_ses' => 'newsletter-amazon-ses',
                                'sparkpost' => 'newsletter-sparkpost',
                            ])
                            ->iconSize(IconSize::Large)
                            ->columns(2)
                            ->color('primary')
                            ->required()
                            ->helperText('Choose a method to send the emails'),

                    ]),

                Wizard\Step::make('Mail Provider Connection')
                    ->schema([

                        Group::make([
                            TextInput::make('smtp_username')
                                ->label('SMTP Username')
                                ->required()
                                ->helperText('Enter the SMTP username'),
                            // audit-test 2026-05-07 Newsletter Admin audit finding #4 (SECURITY HIGH):
                            // every secret field below was missing ->password(), so when admin
                            // re-opened an existing sender account in the wizard, all credentials
                            // (SMTP password, Mailchimp/Mailgun/Mandrill/Sparkpost secrets,
                            // Amazon SES key+secret) rendered as plaintext text inputs — a
                            // shoulder-surf / screenshot exposure. Only Gmail had it (line 110).
                            // ->password() masks the input as type="password"; ->revealable()
                            // gives admin an opt-in show button.
                            TextInput::make('smtp_password')
                                ->label('SMTP Password')
                                ->required()
                                ->password()
                                ->revealable()
                                ->helperText('Enter the SMTP password'),
                            TextInput::make('smtp_host')
                                ->label('SMTP Host')
                                ->required()
                                ->helperText('Enter the SMTP host'),
                            // audit-test 2026-05-07 SenderAccounts follow-up #2 issue #3:
                            // smtp_port accepted any string ("abc") which then failed
                            // downstream as an SMTP error. Added numeric() + valid TCP
                            // port range so admin gets immediate form-side feedback.
                            TextInput::make('smtp_port')
                                ->label('SMTP Port')
                                ->required()
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(65535)
                                ->helperText('Enter the SMTP port'),
                        ])->hidden(function(Get $get) {
                            if ($get('account_type') == 'smtp') {
                                return false;
                            }
                            return true;
                        }),


                        Group::make([
                            // audit-test 2026-05-08 PM TASK-009 / TICKET-AX:
                            // Field NAMES (not labels) renamed from gmail_email/
                            // gmail_app_password to smtp_username/smtp_password.
                            // Why: NewsletterMailSender.php:122-123 reads
                            // $this->sender['smtp_username'] + $this->sender['smtp_password']
                            // for both account_type='gmail' AND account_type='smtp' —
                            // the Gmail case just hardcodes smtp.gmail.com:465. The
                            // gmail_email/gmail_app_password field names were vestigial:
                            // never in $fillable, never in the migration → form values
                            // silently dropped on save → Gmail integration broken.
                            // Right fix is the rename so the form writes to the columns
                            // the send-path already reads. smtp_password is already
                            // 'encrypted'-cast (cycle-43 TASK-004). No migration needed.
                            // Visible labels stay "Gmail Email Address" / "Gmail App
                            // Password" — admins see labels, not field names.
                            TextInput::make('smtp_username')
                                ->label('Gmail Email Address')
                                ->required()
                                ->email()
                                ->helperText('Enter your Gmail email address'),
                            TextInput::make('smtp_password')
                                ->label('Gmail App Password')
                                ->required()
                                ->password()
                                ->revealable()
                                ->helperText('Enter your Gmail app password (create one at myaccount.google.com/apppasswords)'),
                        ])->hidden(function(Get $get) {
                            if ($get('account_type') == 'gmail') {
                                return false;
                            }
                            return true;
                        }),

                         Group::make([

                                TextInput::make('mailchimp_secret')
                                    ->label('Mailchimp Secret')
                                    ->required()
                                    ->password()
                                    ->revealable()
                                    ->helperText('Enter the Mailchimp secret key'),

                             ])->hidden(function(Get $get) {
                             if ($get('account_type') == 'mailchimp') {
                                 return false;
                             }
                             return true;
                         }),

                        Group::make([
                            TextInput::make('mailgun_domain')
                                ->label('Mailgun Domain')
                                ->required()
                                ->helperText('Enter the Mailgun domain'),
                            TextInput::make('mailgun_secret')
                                ->label('Mailgun Secret')
                                ->required()
                                ->password()
                                ->revealable()
                                ->helperText('Enter the Mailgun secret'),
                        ])->hidden(function(Get $get) {
                            if ($get('account_type') == 'mailgun') {
                                return false;
                            }
                            return true;
                        }),

                        Group::make([
                            TextInput::make('mandrill_secret')
                                ->label('Mandrill Secret')
                                ->required()
                                ->password()
                                ->revealable()
                                ->helperText('Enter the Mandrill secret'),
                        ])->hidden(function(Get $get) {
                            if ($get('account_type') == 'mandrill') {
                                return false;
                            }
                            return true;
                        }),


                        Group::make([
                            TextInput::make('sparkpost_secret')
                                ->label('Sparkpost Secret')
                                ->required()
                                ->password()
                                ->revealable()
                                ->helperText('Enter the Sparkpost secret'),

                        ])->hidden(function(Get $get) {
                            if ($get('account_type') == 'sparkpost') {
                                return false;
                            }
                            return true;
                        }),

                        Group::make([
                            TextInput::make('amazon_ses_key')
                                ->label('Amazon SES Key')
                                ->required()
                                ->password()
                                ->revealable()
                                ->helperText('Enter the Amazon SES key'),
                            TextInput::make('amazon_ses_secret')
                                ->label('Amazon SES Secret')
                                ->required()
                                ->password()
                                ->revealable()
                                ->helperText('Enter the Amazon SES secret'),
                            TextInput::make('amazon_ses_region')
                                ->label('Amazon SES Region')
                                ->required()
                                ->helperText('Enter the Amazon SES region'),
                        ])->hidden(function(Get $get) {
                            if ($get('account_type') == 'amazon_ses') {
                                return false;
                            }
                            return true;
                        }),



                    ]),
                Wizard\Step::make('Sender Details')
                    ->schema([
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->helperText('Enter the name of the sender account'),
                        TextInput::make('from_name')
                            ->label('From Name')
                            ->required()
                            ->helperText('This name will be visible as Sender name in the received e-mail'),
                        // audit-test 2026-05-07 SenderAccounts follow-up #2 issue #2 (bonus consistency):
                        // from_email + reply_email also need ->email() validation so admin
                        // gets immediate form feedback for malformed addresses (otherwise
                        // the SMTP send fails silently or with an opaque error later).
                        TextInput::make('from_email')
                            ->label('From Email')
                            ->required()
                            ->email()
                            ->helperText('This e-mail will be visible as Sender e-mail address in the received e-mail'),

                        TextInput::make('reply_email')
                            ->label('Reply To Email')
                            ->required()
                            ->email()
                            ->helperText('This e-mail will used for reply in the received e-mail'),

                    ]),
            ]),


        ];

        return $table
            ->heading('Sender Accounts')
            ->query(NewsletterSenderAccount::query())
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('from_name'),
                TextColumn::make('from_email'),
                TextColumn::make('reply_email'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Sender Account')
                    ->form($editForm),
            ])
            ->actions([
                EditAction::make()->form($editForm),
                DeleteAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
            ]);
    }

}
