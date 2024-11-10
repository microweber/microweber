<div 
    class="tab-pane fade{{ $active ? ' show active' : '' }}"
    id="{{ $id() }}"
    role="tabpanel"
    aria-labelledby="{{ $id() }}-tab"
>
    {{ $slot }}
</div>
