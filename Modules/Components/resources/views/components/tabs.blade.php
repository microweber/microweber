<div {{ $attributes }}>
    <ul class="nav {{ $pills ? 'nav-pills' : 'nav-tabs' }}{{ $vertical ? ' flex-column' : '' }}" role="tablist">
        @if(isset($slot) && !$slot->isEmpty())
            @foreach($slot as $slotKey => $tabPane)
                @if(isset($tabPane))


                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link{{ $tabPane['attributes']['active'] ?? false ? ' active' : '' }}"
                            id="{{ $tabPane['attributes']['id'] ?? false ? $tabPane['attributes']['id'] . '-tab' : $slotKey . '-tab' }}"
                            data-bs-toggle="tab"
                            data-bs-target="#{{ $tabPane['attributes']['id'] ?? false ? $tabPane['attributes']['id'] : $slotKey }}"
                            type="button"
                            role="tab"
                            aria-controls="{{ $tabPane['attributes']['id'] ?? false ? $tabPane['attributes']['id'] : $slotKey }}"
                            aria-selected="{{ $tabPane['attributes']['active'] ?? false ? 'true' : 'false' }}"
                        >
                            {{ $tabPane['attributes']['title'] ?? 'Tab ' . $slotKey }}
                       </button>
                   </li>
               @endif
           @endforeach
       @endif
   </ul>

   <div class="tab-content{{ $vertical ? ' flex-grow-1' : '' }} mt-2">
        {{ $slot }}
    </div>
</div>
