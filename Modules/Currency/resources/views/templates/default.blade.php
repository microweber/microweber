@php
/*
type: layout
name: Default
description: Currency switcher dropdown
*/
@endphp

@php
    // Only mount the switcher when multi-currency is enabled AND there is more
    // than one active currency to switch between. With a single currency there
    // is nothing to choose, so render nothing at all — not even an empty wrapper.
    // Use a plain COUNT query (not the manager's cached Eloquent collection) so
    // this render path stays robust.
    $__currencyManager = app(\Modules\Currency\Services\CurrencyManager::class);
    $__showCurrencySwitcher = $__currencyManager->isMultiCurrencyEnabled()
        && \Modules\Currency\Models\Currency::active()->count() > 1;
@endphp

@if($__showCurrencySwitcher)
<div class="module-currency-template">
    <livewire:currency-switcher />
</div>
@endif
