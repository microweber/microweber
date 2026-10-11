<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-primary text-uppercase']) }}>
    {{ $slot }}
</button>
