<div {{ $attributes->merge(['class' => ($vertical ? 'd-flex align-items-start' : '')]) }}>
    @if(isset($navItems))
        <ul class="nav {{ $pills ? 'nav-pills' : 'nav-tabs' }}{{ $vertical ? ' flex-column me-3' : '' }}" id="{{ $tabId }}" role="tablist">
            {{ $navItems }}
        </ul>
    @endif

    <div class="tab-content{{ $vertical ? ' flex-grow-1' : '' }}" id="{{ $tabId }}-content">
        {{ $slot }}
    </div>
</div>