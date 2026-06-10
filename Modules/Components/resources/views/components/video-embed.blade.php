<div {{ $attributes->merge(['class' => 'video-embed']) }}>
    @if($url)
        <div class="ratio ratio-{{ $ratio }}">
            <module type="video" template="default" url="{{ $url }}" height="{{ $height }}" {{ $autoplay ? 'autoplay="true"' : '' }}/>
        </div>
    @elseif($slot->isNotEmpty())
        <div class="ratio ratio-{{ $ratio }}">
            {{ $slot }}
        </div>
    @endif
</div>