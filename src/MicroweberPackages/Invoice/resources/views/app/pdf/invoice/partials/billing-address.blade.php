@if($invoice->customer->billingaddress)
    <p class="billing-address-label">Bill To,</p>
    @if($invoice->customer->billingaddress->name)
        <p class="billing-address-name">
            {{$invoice->customer->billingaddress->name}}
        </p>
    @endif
    <p class="billing-address">
        @if($invoice->customer->billingaddress->address_street_1)
            {!! nl2br(htmlspecialchars($invoice->customer->billingaddress->address_street_1)) !!}<br>
        @endif
        @if($invoice->customer->billingaddress->address_street_2)
            {!! nl2br(htmlspecialchars($invoice->customer->billingaddress->address_street_2)) !!}<br>
        @endif
        @if($invoice->customer->billingaddress->city && $invoice->customer->billingaddress->city)
            {{$invoice->customer->billingaddress->city}},
        @endif
        @if($invoice->customer->billingaddress->state && $invoice->customer->billingaddress->state)
            {{$invoice->customer->billingaddress->state}}.
        @endif
        @if($invoice->customer->billingaddress->zip)
            {{$invoice->customer->billingaddress->zip}}<br>
        @endif
        @if($invoice->customer->billingaddress->country && $invoice->customer->billingaddress->country->name)
            {{$invoice->customer->billingaddress->country->name}}<br>
        @endif
        @if($invoice->customer->billingaddress->phone)
            <p class="billing-address">
                Phone :{{$invoice->customer->billingaddress->phone}}
            </p>
        @endif
    </p>
@endif
