<div>
    @php
        $shippingMethod = $this->data['shipping_method'] ?? null;
    @endphp

    @if($shippingMethod)
        <div class="mt-4">
            {!!  $shippingMethod  !!}
        </div>
    @endif
</div>
