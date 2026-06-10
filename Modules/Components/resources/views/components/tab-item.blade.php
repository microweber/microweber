<div {{ $attributes->merge(['class' => 'tab-pane fade' . ($active ? ' show active' : ''), 'id' => $itemId, 'role' => 'tabpanel', 'aria-labelledby' => $itemId . '-tab']) }}>
    {{ $slot }}
</div>