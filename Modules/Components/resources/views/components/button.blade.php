@if($url)
    <a href="{{ $url }}" {{ $attributes->merge(['class' => 'btn ' . ($outline ? 'btn-outline-' . $type : 'btn-' . $type) . ' ' . ($size ? 'btn-' . $size : '') . ' ' . ($block ? 'd-block' : '') . ' ' . $class]) }} {{ $disabled ? 'disabled' : '' }}>
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $submit ? 'submit' : 'button' }}"
        {{ $attributes->merge(['class' => 'btn ' . ($outline ? 'btn-outline-' . $type : 'btn-' . $type) . ' ' . ($size ? 'btn-' . $size : '') . ' ' . ($block ? 'd-block' : '') . ' ' . $class]) }} {{ $disabled ? 'disabled' : '' }}>
        {{ $slot }}
    </button>
@endif
