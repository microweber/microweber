@if($invoice->customer->shippingaddress)
    <p class="shipping-address-label">Ship To,</p>
    @if($invoice->customer->shippingaddress->name)
        <p class="shipping-address-name">
            {{$invoice->customer->shippingaddress->name}}
        </p>
    @endif
    <p class="shipping-address">
        @if($invoice->customer->shippingaddress->address_street_1)
            {!! nl2br(htmlspecialchars($invoice->customer->shippingaddress->address_street_1)) !!}<br>
        @endif

        @if($invoice->customer->shippingaddress->address_street_2)
            {!! nl2br(htmlspecialchars($invoice->customer->shippingaddress->address_street_2)) !!}<br>
        @endif

        @if($invoice->customer->shippingaddress->city && $invoice->customer->shippingaddress->city)
            {{$invoice->customer->shippingaddress->city}},
        @endif

        @if($invoice->customer->shippingaddress->state && $invoice->customer->shippingaddress->state)
            {{$invoice->customer->shippingaddress->state}}.
        @endif

        @if($invoice->customer->shippingaddress->zip)
            {{$invoice->customer->shippingaddress->zip}}<br>
        @endif

        @if($invoice->customer->shippingaddress->country && $invoice->customer->shippingaddress->country->name)
            {{$invoice->customer->shippingaddress->country->name}}<br>
        @endif

        @if($invoice->customer->shippingaddress->phone)
            <p class="shipping-address">
                Phone :{{$invoice->customer->shippingaddress->phone}}
            </p>
        @endif

    </p>
@endif