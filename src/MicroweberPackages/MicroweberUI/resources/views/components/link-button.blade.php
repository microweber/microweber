<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-link']) }}>
    {{ $slot }}
</button>
