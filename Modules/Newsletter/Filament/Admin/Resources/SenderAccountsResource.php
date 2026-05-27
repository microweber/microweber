<?php

namespace Modules\Newsletter\Filament\Admin\Resources;

use Filament\Schemas\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\IconSize;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Modules\Newsletter\Filament\Admin\Resources\SenderAccountsResource\Pages\ManageSenderAccounts;
use Modules\Newsletter\Models\NewsletterSenderAccount;
use Modules\Newsletter\Senders\NewsletterMailSender;

class SenderAccountsResource extends Resource
{
    protected static ?string $model = NewsletterSenderAccount::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string | \BackedEnum | null $navigationIcon = null;

//    protected static ?string $slug = 'newsletter/sender-accounts';

//    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $label = 'Sender';
    protected static ?string $pluralLabel = 'Senders';

    protected static string | \UnitEnum | null $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'from_name', 'from_email', 'account_type'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        $details = [];

        if ($record->from_email) {
            $details['Email'] = $record->from_email;
        }

        if ($record->account_type) {
            $details['Type'] = ucfirst(str_replace('_', ' ', $record->account_type));
        }

        return $details;
    }

    public static function getEditFormArray()
    {
        return [

            Wizard::make([
                Wizard\Step::make('Mail Provider')
                    ->schema([

                        RadioDeck::make('account_type')
                            ->label('Send email function')
                            ->options([
                                'php_mail' => 'PHP Mail',
                                'smtp' => 'SMTP Server',
                              //  'gmail' => 'Gmail',
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
                            TextInput::make('smtp_username')
                                ->label('Gmail Email Address')
                                ->required()
                                ->helperText('Enter your Gmail email address'),
                            TextInput::make('smtp_password')
                                ->label('Gmail App Password')
                                ->required()
                                ->password()
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
//                            TextInput::make('name')
//                                ->label('Name')
//                                ->required()
//                                ->disabled()
//                                ->helperText('Enter the name of the sender account'),
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


        ];
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema(self::getEditFormArray());
    }

    public static function table(Table $table): Table
    {
        // task-2026-05-27-892547 / AI-1176: filter out Faker-seeded sender accounts
        return $table
            ->modifyQueryUsing(fn ($query) => $query
                ->where(function ($q) {
                    $q->whereNull('from_email')
                      ->orWhere(function ($q2) {
                          $q2->where('from_email', 'NOT LIKE', '%@example.com')
                             ->where('from_email', 'NOT LIKE', '%@example.net')
                             ->where('from_email', 'NOT LIKE', '%@example.org');
                      });
                })
            )
            ->columns([
                TextColumn::make('account_type')
                    ->label('Type')
                    ->icon(fn (string $state): string => match ($state) {
                        'php_mail' => 'newsletter-php',
                        'gmail' => 'newsletter-smtp',
                        'smtp' => 'newsletter-smtp',
                        'mailchimp' => 'newsletter-mailchimp',
                        'mailgun' => 'newsletter-mailgun',
                        'mandrill' => 'newsletter-mandrill',
                        'amazon_ses' => 'newsletter-amazon-ses',
                        'sparkpost' => 'newsletter-sparkpost',
                        default => 'heroicon-o-envelope',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'php_mail' => 'PHP Mail',
                        'smtp' => 'SMTP',
                        'gmail' => 'Gmail',
                        'mailchimp' => 'Mailchimp',
                        'mailgun' => 'Mailgun',
                        'mandrill' => 'Mandrill',
                        'amazon_ses' => 'Amazon SES',
                        'sparkpost' => 'Sparkpost',
                        default => ucfirst($state),
                    })
                    ->sortable(),
                TextColumn::make('from_name')->searchable(),
                TextColumn::make('from_email')->searchable(),
                TextColumn::make('reply_email'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('test')
                    ->label('Test')
                    ->icon('heroicon-o-paper-airplane')
                    ->requiresConfirmation()
                    ->modalHeading('Send Test Email')
                    ->modalDescription(fn (NewsletterSenderAccount $record) =>
                        "Send a test email from \"{$record->from_name} <{$record->from_email}>\" to the current admin's email address."
                    )
                    ->action(function (NewsletterSenderAccount $record) {
                        $adminEmail = auth()->user()->email;

                        $mailSender = new NewsletterMailSender();
                        $mailSender->setSender($record->toArray());
                        $mailSender->setTemplate(['text' => '<h2>Test Email</h2><p>This is a test email from your newsletter sender account: <strong>' . e($record->from_name) . '</strong>.</p><p>If you received this email, your sender configuration is working correctly.</p>']);
                        $mailSender->setCampaign([
                            'id' => 0,
                            'name' => $record->from_name,
                            'subject' => 'Test Email — ' . $record->from_name,
                        ]);
                        $mailSender->setSubscriber([
                            'email' => $adminEmail,
                            'name' => auth()->user()->name ?? 'Admin',
                        ]);

                        $result = $mailSender->sendMail();

                        if ($result['success']) {
                            Notification::make()
                                ->title('Test email sent')
                                ->body("Sent to {$adminEmail}")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('Test email failed')
                                ->body($result['message'] ?? 'Unknown error')
                                ->danger()
                                ->send();
                        }
                    }),
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
