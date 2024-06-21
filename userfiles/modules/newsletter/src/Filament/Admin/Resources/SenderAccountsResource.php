<?php

namespace MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources;

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
use MicroweberPackages\Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource\Pages\ManageSenderAccounts;
use MicroweberPackages\Modules\Newsletter\Models\NewsletterSenderAccount;

class SenderAccountsResource extends Resource
{
    protected static ?string $model = NewsletterSenderAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';

//    protected static ?string $slug = 'newsletter/sender-accounts';

//    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $label = 'Senders';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([


                Wizard::make([
                    Wizard\Step::make('Mail Provider')
                        ->schema([

                            RadioDeck::make('account_type')
                                ->label('Send email function')
                                ->options([
                                    'php_mail' => 'PHP Mail',
                                    'smtp' => 'SMTP Server',
                                    'mailchimp' => 'Mailchimp',
                                    'mailgun' => 'Mailgun',
                                    'mandrill' => 'Mandrill',
                                    'amazon_ses' => 'Amazon SES',
                                    'sparkpost' => 'Sparkpost',
                                ])
                                ->icons([
                                    'php_mail' => 'newsletter-php',
                                    'smtp' => 'newsletter-smtp',
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
                                TextInput::make('smtp_password')
                                    ->label('SMTP Password')
                                    ->required()
                                    ->helperText('Enter the SMTP password'),
                                TextInput::make('smtp_host')
                                    ->label('SMTP Host')
                                    ->required()
                                    ->helperText('Enter the SMTP host'),
                                TextInput::make('smtp_port')
                                    ->label('SMTP Port')
                                    ->required()
                                    ->helperText('Enter the SMTP port'),
                            ])->hidden(function(Get $get) {
                                if ($get('account_type') == 'smtp') {
                                    return false;
                                }
                                return true;
                            }),

                            Group::make([

                                TextInput::make('mailchimp_secret')
                                    ->label('Mailchimp Secret')
                                    ->required()
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
                                    ->helperText('Enter the Amazon SES key'),
                                TextInput::make('amazon_ses_secret')
                                    ->label('Amazon SES Secret')
                                    ->required()
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
                            TextInput::make('from_email')
                                ->label('From Email')
                                ->required()
                                ->helperText('This e-mail will be visible as Sender e-mail address in the received e-mail'),

                            TextInput::make('reply_email')
                                ->label('Reply To Email')
                                ->required()
                                ->helperText('This e-mail will used for reply in the received e-mail'),

                        ]),
                ])->columnSpanFull(),


            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('from_name'),
                TextColumn::make('from_email'),
                TextColumn::make('reply_email'),
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
