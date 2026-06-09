<div {{ $attributes->merge(['class' => trim('container' . ($fluid ? '-fluid' : '') . ' ' . $class)]) }}>
    {{ $slot }}
</div>
