@if($url)
    <a href="{{ $url }}" class="btn {{ $outline ? 'btn-outline-' . $type : 'btn-' . $type }} {{ $size ? 'btn-' . $size : '' }} {{ $block ? 'd-block'  : '' }} {{ $class }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes }}>
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $submit ? 'submit' : 'button' }}"
        class="btn {{ $outline ? 'btn-outline-' . $type : 'btn-' . $type }} {{ $size ? 'btn-' . $size : '' }} {{ $block ? 'd-block'  : '' }} {{ $class }}"
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes }}>
        {{ $slot }}
    </button>
@endif
