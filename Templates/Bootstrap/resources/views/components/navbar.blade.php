<nav {{ $attributes->merge(['class' => 'navbar navbar-expand-' . $expand . ' ' . ($dark ? 'navbar-dark' : 'navbar-light') . ' ' . ($fixed ? 'fixed-' . $fixed : '')]) }}>
    <div class="container">
        <a class="navbar-brand" href="{{ $brandUrl }}">{{ $brand }}</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                {{ $slot }}
            </ul>
        </div>
    </div>
</nav>
