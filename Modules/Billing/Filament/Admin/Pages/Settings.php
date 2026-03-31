<?php

namespace Modules\Billing\Filament\Admin\Pages;

use Filament\Actions\Action;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use MicroweberPackages\Option\Models\Option;
use Modules\Payment\Models\PaymentProvider;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';

    protected string $view = 'modules.billing::filament.pages.settings';

    protected static ?int $navigationSort = 6;

    public ?array $data = [];

    public function mount(): void
    {
        $this->data = $this->getFormDefaults();
    }

    public function form(Schema $schema): Schema
    {
        $providers = PaymentProvider::where('is_active', 1)
            ->where('provider', 'stripe')
            ->orderBy('position', 'asc')
            ->get();

        $options = [];
        $descriptions = [];
        $icons = [];

        foreach ($providers as $provider) {
            $options[$provider->id] = $provider->name;
            $descriptions[$provider->id] = $provider->description ?? '';
            $icons[$provider->id] = 'heroicon-m-credit-card';
        }

        $components = [];

        if ($providers->isEmpty()) {
            $components[] = Placeholder::make('no_providers')
                ->label('No active payment providers found')
                ->content('Please set up at least one payment provider to enable billing. For now only Stripe is supported.')
                ->extraAttributes(['class' => 'text-center text-lg font-semibold']);

            $components[] = \Filament\Schemas\Components\Actions::make([
                \Filament\Actions\Action::make('setup_providers')
                    ->label('Go to Payment Providers Setup')
                    ->url(admin_url('payment-providers'))
                    ->color('primary')
                    ->openUrlInNewTab(),
            ]);
        } else {
            $components[] = \JaOcero\RadioDeck\Forms\Components\RadioDeck::make('cashier_billing_payment_provider_id')
                ->label('Select Payment Provider')
                ->options($options)
                ->descriptions($descriptions)
                ->icons($icons)
                ->required()
                ->columns(2);
        }

        $components[] = TextInput::make('cashier_success_url')
            ->label('Success URL redirect')
            ->url()
            ->columnSpan('full');

        $components[] = TextInput::make('cashier_cancel_url')
            ->label('Cancel URL redirect')
            ->url()
            ->columnSpan('full');
        $components[] = TextInput::make('cashier_currency')
            ->label('Currency')
            ->placeholder('USD')
            ->default('USD')
            ->columnSpan('full');

        $components[] = TextInput::make('cashier_currency_locale')
            ->label('Currency locale')
            ->placeholder('en_US')
            ->default('en_US')
            ->columnSpan('full');
        return $schema
            ->statePath('data')
            ->schema($components);
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
                ->action(fn() => $this->save())
                ->color('success'),
        ];
    }
}
