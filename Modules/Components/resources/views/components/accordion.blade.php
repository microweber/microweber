<div {{ $attributes->merge(['class' => 'accordion' . ($flush ? ' accordion-flush' : ''), 'id' => $accordionId]) }}>
    {{ $slot }}
</div>