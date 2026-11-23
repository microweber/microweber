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
     * Inject the manager on EVERY request. Livewire only calls mount() on the
     * initial render; subsequent requests (e.g. the switchCurrency action) skip
     * mount, so injecting there left $currencyManager uninitialized and threw
     * "Typed property … must not be accessed before initialization". boot() runs
     * on every request (before hydrate + the action), so the service is always
     * available.
     *
     * @param CurrencyManager $currencyManager
     */
    public function boot(CurrencyManager $currencyManager): void
    {
        $this->currencyManager = $currencyManager;
    }

    /**
     * Mount the component.
     *
     * @return void
     */
    public function mount(): void
    {
        $this->selectedCurrency = $this->currencyManager->getCurrentCurrencyCode();
        $this->loadCurrencies();
    }

    /**
     * Load the currencies offered for switching. Respects the
     * multi_currency_enabled config: when multi-currency is off there is nothing
     * to switch between, so the list is empty and the view's `count > 1` guard
     * hides the widget. Queries the active currencies directly (a fresh query,
     * not the manager's cached Eloquent collection) so the hot render path can't
     * trip over a stale/poisoned currency cache.
     *
     * @return void
     */
    protected function loadCurrencies(): void
    {
        if (!$this->currencyManager->isMultiCurrencyEnabled()) {
            $this->currencies = [];
            return;
        }

        $currencies = Currency::active()
            ->orderBy('position', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        $this->currencies = $currencies->map(function ($currency) {
            return [
                'code' => $currency->code,
                'name' => $currency->name,
                'symbol' => $currency->symbol,
                'is_default' => (bool) $currency->is_default,
            ];
        })->values()->toArray();
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
