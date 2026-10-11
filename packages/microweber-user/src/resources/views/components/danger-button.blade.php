<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn btn-danger text-white text-uppercase']) }}>
    {{ $slot }}
</button>
