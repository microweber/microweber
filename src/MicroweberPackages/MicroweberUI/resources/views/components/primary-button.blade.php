<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-primary text-uppercase']) }}>
    {{ $slot }}
</button>
