<?php

namespace Modules\Currency\Livewire;

use Livewire\Component;
use Modules\Currency\Models\Currency;
use Modules\Currency\Services\CurrencyManager;

class CurrencySwitcher extends Component
{
    /**
     * The selected currency code.
     *
     * @var string
     */
    public string $selectedCurrency;

    /**
     * Available currencies.
     *
     * @var array
     */
    public array $currencies = [];

    /**
     * @var CurrencyManager
     */
    protected CurrencyManager $currencyManager;

    /**
     * Mount the component.
     *
     * @param CurrencyManager $currencyManager
     */
    public function mount(CurrencyManager $currencyManager): void
    {
        $this->currencyManager = $currencyManager;
        $this->selectedCurrency = $this->currencyManager->getCurrentCurrencyCode();
        $this->loadCurrencies();
    }

    /**
     * Load available currencies.
     *
     * @return void
     */
    protected function loadCurrencies(): void
    {
        $currencies = Currency::active()
            ->orderBy('position', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        $this->currencies = $currencies->map(function ($currency) {
            return [
                'code' => $currency->code,
                'name' => $currency->name,
                'symbol' => $currency->symbol,
                'is_default' => $currency->is_default,
            ];
        })->toArray();
    }

    /**
     * Switch to a different currency.
     *
     * @param string $currencyCode
     * @return void
     */
    public function switchCurrency(string $currencyCode): void
    {
        if ($this->currencyManager->switchCurrency($currencyCode)) {
            $this->selectedCurrency = $currencyCode;
            $this->dispatch('currency-changed', ['currency' => $currencyCode]);
            
            // Refresh the page to update all prices
            $this->dispatch('refresh-page');
        }
    }

    /**
     * Get the display label for a currency.
     *
     * @param array $currency
     * @return string
     */
    public function getCurrencyLabel(array $currency): string
    {
        if ($currency['is_default']) {
            return "{$currency['name']} ({$currency['symbol']}) - Default";
        }
        return "{$currency['name']} ({$currency['symbol']})";
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('currency::livewire.currency-switcher');
    }
}
