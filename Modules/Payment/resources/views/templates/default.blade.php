<div x-data="{ selectedProvider: null }">
    <div class="mw-checkout-payment-methods alert alert-info">
        Please select a payment method
    </div>
    <select x-model="selectedProvider" class="form-control">
        <option value="" disabled>Select a provider</option>
        @foreach ($providers as $provider)
            <option value="{{ $provider['id'] }}">{{ $provider['name'] }}</option>
        @endforeach
    </select>
    <div x-show="selectedProvider" class="mt-3">
        <p>Selected Provider ID: <span x-text="selectedProvider"></span></p>
    </div>
</div>
