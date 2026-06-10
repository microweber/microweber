<div {{ $attributes->merge(['class' => 'row row-cols-1 row-cols-md-' . $columns . ' g-4 text-center ' . $class]) }}>
    {{ $slot }}
</div>