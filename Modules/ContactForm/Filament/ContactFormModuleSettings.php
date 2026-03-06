<?php

namespace Modules\ContactForm\Filament;

use Filament\Forms\Components\Livewire;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use MicroweberPackages\LiveEdit\Filament\Admin\Pages\Abstract\LiveEditModuleSettings;

class ContactFormModuleSettings extends LiveEditModuleSettings
{
    public string $module = 'contact_form';
    public string $name = '';
    public string $email = '';

    public function form(Schema $schema): Schema
    {

        $relId = $this->params['id'] ?? null;
        return $schema
//            ->model()

            ->schema([

                Tabs::make('Contact Form')
                    ->schema([
                        Tabs\Tab::make('Main settings')
                            ->schema([

                                \LaraZeus\Accordion\Forms\Accordions::make('Options')
                                    ->slideOverRight()
                                    ->activeAccordion(0)
                                    ->accordions([

                                        \LaraZeus\Accordion\Forms\Accordion::make('from_fields')
                                            ->columns()
                                            ->icon( 'heroicon-o-rectangle-stack')
                                            ->label('From Fields')
                                            ->schema(function () use ($relId) {

                                                $customFieldParams = [
                                                    'relId' => $relId,
                                                    'relType' => 'module'
                                                ];

                                                if ($relId == 0) {
                                                    $customFieldParams['createdBy'] = user_id();
                                                }

                                                $components = [];
                                                $components[] = Livewire::make('admin-list-custom-fields', $customFieldParams)->columnSpanFull();

                                                return $components;
                                            }),
                                        \LaraZeus\Accordion\Forms\Accordion::make('auto_respond_settings')
                                            ->columnSpanFull()
                                            ->label('Auto Respond Settings')
                                            ->icon( 'heroicon-o-envelope-open')
                                            ->schema([
                                                Toggle::make('options.email_autorespond_enable')
                                                    ->label('Enable auto respond message to user')
                                                    ->helperText('Allow users to receive "Thank you emails after subscription."')
                                                    ->live(),

                                                TextInput::make('options.email_autorespond_subject')
                                                    ->label('Auto respond subject')
                                                    ->helperText('E.x. "Thank you for your request"')
                                                    ->live()
                                                    ->visible(fn (callable $get) => $get('options.email_autorespond_enable')),

                                                Textarea::make('options.email_autorespond')
                                                    ->label('Auto respond message')
                                                    ->helperText('Auto respond e-mail sent back to the user')
                                                    ->live()
                                                    ->rows(4)
                                                    ->visible(fn (callable $get) => $get('options.email_autorespond_enable')),

                                                Toggle::make('options.email_autorespond_custom_sender')
                                                    ->label('Auto respond custom sender')
                                                    ->helperText('Use custom sender settings for the current contact form.')
                                                    ->reactive()
                                                    ->visible(fn (callable $get) => $get('options.email_autorespond_enable')),

                                                TextInput::make('options.email_autorespond_from')
                                                    ->label('Auto respond from e-mail address')
                                                    ->helperText('The e-mail address which will send the message')
                                                    ->live()
                                                    ->email()
                                                    ->visible(fn (callable $get) => $get('options.email_autorespond_enable') && $get('options.email_autorespond_custom_sender')),

                                                TextInput::make('options.email_autorespond_from_name')
                                                    ->label('Auto respond from name')
                                                    ->helperText('e.x. your name, company or brand name')
                                                    ->live()
                                                    ->visible(fn (callable $get) => $get('options.email_autorespond_enable') && $get('options.email_autorespond_custom_sender')),

                                                TextInput::make('options.email_autorespond_reply_to')
                                                    ->label('Auto respond reply to e-mail')
                                                    ->helperText('When the user receive the auto respond message they can response back to reply to email.')
                                                    ->live()
                                                    ->email()
                                                    ->visible(fn (callable $get) => $get('options.email_autorespond_enable') && $get('options.email_autorespond_custom_sender'))

                                            ]),

                                        \LaraZeus\Accordion\Forms\Accordion::make('advanced')
                                            ->columnSpanFull()
                                            ->icon('heroicon-o-cog')
                                            ->label('Advanced')
                                            ->schema([
                                                TextInput::make('options.button_text')
                                                    ->label('Button text')
                                                    ->helperText('Write your button text')
                                                    ->live()
                                                ->mwTranslatableOption()
                                                ,



                                                TextInput::make('options.thank_you_message')
                                                    ->label('Thank you message')
                                                    ->live()
                                                    ->helperText('Write your thank you message'),

                                                Toggle::make('options.newsletter_subscription')
                                                    ->label('Newsletter')
                                                    ->live()
                                                    ->helperText('Show the newsletter subscription checkbox?'),

                                                Toggle::make('options.enable_captcha')
                                                    ->label('Enable Code Verification')
                                                    ->live()
                                                    ->default(true)
                                                    ->helperText('Enable captcha for this contact form'),

                                                TextInput::make('options.email_redirect_after_submit')
                                                    ->label('Redirect URL')
                                                    ->live()
                                                    ->helperText('Redirect to URL after submit for example for "Thank you" page')
                                                    ->url(),

                                                TextInput::make('options.form_name')
                                                    ->label('Contact form name')
                                                    ->helperText('What is the name of this contact form?')
                                                    ->live(),


                                                Toggle::make('options.email_custom_receivers')
                                                    ->label('Send contact form data to custom receivers when is submitted')
                                                    ->helperText('Use custom receivers settings for the current contact form.')
                                                    ->live(),

                                                TextInput::make('options.email_to')
                                                    ->label('To e-mail addresses')
                                                    ->live()
                                                    ->helperText('E-mail address of the receivers separated with comma.')
                                                    ->visible(fn (callable $get) => $get('options.email_custom_receivers'))



                                            ]),
                                    ]),


                            ]),

                        Tabs\Tab::make('Design')
                            ->schema($this->getTemplatesFormSchema()),
                    ]),
            ]);
    }

}
