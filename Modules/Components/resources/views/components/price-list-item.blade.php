<div {{ $attributes->merge(['class' => 'cloneable element safe-mode py-4']) }}>
    @if($title)
        <div class="py-2"><h6 class="safe-element">{{ $title }}</h6></div>
    @endif
    @if($description || $price)
        <div class="d-flex">
            <span class="price-list-content col-8 safe-element px-0">{{ $description }}</span>
            <span class="col-4 justify-content-end text-end text-right px-0">{{ $price }}</span>
        </div>
    @endif
    @if($slot->isNotEmpty())
        {{ $slot }}
    @endif
    @if($showDivider)
        <hr class="price-list-hr">
    @endif
</div>