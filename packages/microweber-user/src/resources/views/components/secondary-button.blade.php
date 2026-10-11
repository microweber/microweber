<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-outline-secondary text-uppercase']) }}>
    {{ $slot }}
</button>
