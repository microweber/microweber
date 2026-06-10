<div {{ $attributes->merge(['class' => 'social-links social-links-' . $size . ' social-links-' . $style]) }}>
    @if($slot->isNotEmpty())
        {{ $slot }}
    @else
        <module type="social_links"/>
    @endif
</div>