<?php

namespace Modules\Billing\Filament\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Actions\Action;
use MicroweberPackages\Option\Models\Option;
use Modules\Billing\Services\StripeService;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'modules.billing::filament.pages.settings';

    protected static ?int $navigationSort = 6;

    public ?array $data = [];

    public function mount(): void
    {
        $this->data = $this->getFormDefaults();
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([

                TextInput::make('cashier_stripe_publishable_api_key')
                    ->label('Publishable key')
                    ->required()
                    ->placeholder('pk_...')
                    ->columnSpan('full'),


                TextInput::make('cashier_stripe_api_key')
                    ->label('Secret key')
                    ->required()
                    ->placeholder('sk_...')
                    ->columnSpan('full'),



                TextInput::make('cashier_stripe_webhook_secret')
                    ->label('Webhook secret')

                    ->placeholder('whsec_...')
                    ->columnSpan('full'),

                Placeholder::make('webhook_url')
                    ->label('Callback URL')
                    ->content(fn () => route('billing.webhook.stripe'))
                    ->columnSpan('full'),

                TextInput::make('currency')
                    ->label('Currency')

                    ->placeholder('USD')
                    ->default('USD')
                    ->columnSpan('full'),

                TextInput::make('currency_locale')
                    ->label('Currency locale')

                    ->placeholder('en_US')
                    ->default('en_US')
                    ->columnSpan('full'),

                TextInput::make('cashier_success_url')
                    ->label('Success URL redirect')

                    ->url()
                    ->columnSpan('full'),

                TextInput::make('cashier_cancel_url')
                    ->label('Cancel URL redirect')

                    ->url()
                    ->columnSpan('full'),
            ]);
    }

    public function getFormDefaults(): array
    {
        $settings = Option::query()
            ->where('option_group', 'payments')
            ->pluck('option_value', 'option_key')
            ->toArray();

        return $settings;
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            save_option($key, $value, 'payments');
        }

        Notification::make()
            ->success()
            ->title('Settings saved successfully.')
            ->send();
    }

    protected function getActions(): array
    {
        return [
            Action::make('Save Settings')
                ->label('Save Settings')
                ->action(fn () => $this->save())
                ->color('success'),

            Action::make('Test Stripe Connection')
                ->label('Test Stripe Connection')
                ->action(fn () => $this->testStripeConnection())
                ->color('primary'),
        ];
    }

    public function testStripeConnection(): void
    {
        $service = new StripeService();

        if ($service->testConnection()) {
            Notification::make()
                ->success()
                ->title('Stripe connection successful.')
                ->send();
        } else {
            Notification::make()
                ->danger()
                ->title('Stripe connection failed.')
                ->send();
        }
    }
}
