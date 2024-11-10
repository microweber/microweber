<div {{ $attributes }}>
    <ul class="nav {{ $pills ? 'nav-pills' : 'nav-tabs' }}{{ $vertical ? ' flex-column' : '' }}" role="tablist">
        @foreach($slot->toArray() as $tabPane)
            <li class="nav-item" role="presentation">
                <button 
                    class="nav-link{{ $tabPane->attributes->get('active') ? ' active' : '' }}"
                    id="{{ $tabPane->id() }}-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#{{ $tabPane->id() }}"
                    type="button"
                    role="tab"
                    aria-controls="{{ $tabPane->id() }}"
                    aria-selected="{{ $tabPane->attributes->get('active') ? 'true' : 'false' }}"
                >
                    {{ $tabPane->title }}
                </button>
            </li>
        @endforeach
    </ul>

    <div class="tab-content{{ $vertical ? ' flex-grow-1' : '' }} mt-2">
        {{ $slot }}
    </div>
</div>
