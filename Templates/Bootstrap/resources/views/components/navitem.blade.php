<li {{ $attributes->merge(['class' => 'nav-item']) }}>
    <a class="nav-link {{ $active ? 'active' : '' }}" href="{{ $href }}">
        {{ $slot }}
    </a>
</li>
