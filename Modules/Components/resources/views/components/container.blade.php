<div {{ $attributes->merge(['class' => 'container' . ($fluid ? '-fluid' : '')]) }}>
    {{ $slot }}
</div>
