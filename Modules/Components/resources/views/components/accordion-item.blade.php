<div {{ $attributes->merge(['class' => 'accordion-item']) }}>
    <h2 class="accordion-header" id="heading-{{ $itemId }}">
        <button class="accordion-button{{ $open ? '' : ' collapsed' }}" type="button"
            data-bs-toggle="collapse" data-bs-target="#collapse-{{ $itemId }}"
            aria-expanded="{{ $open ? 'true' : 'false' }}" aria-controls="collapse-{{ $itemId }}">
            {{ $title }}
        </button>
    </h2>
    <div id="collapse-{{ $itemId }}" class="accordion-collapse collapse{{ $open ? ' show' : '' }}"
        aria-labelledby="heading-{{ $itemId }}"@if($parent) data-bs-parent="#{{ $parent }}"@endif>
        <div class="accordion-body">
            {{ $slot }}
        </div>
    </div>
</div>