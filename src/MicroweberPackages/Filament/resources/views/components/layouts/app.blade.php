@extends('admin::layouts.app', [
 'disableLivewireScripts' => true,
])

@section('content')

<main class="filament-layouts-app module-main-holder px-5">
   {{-- FILAMENT SCRIPTS START --}}

        {{ \Filament\Facades\Filament::renderHook('head.start') }}

        @foreach (\Filament\Facades\Filament::getMeta() as $tag)
            {{ $tag }}
        @endforeach

        {{ \Filament\Facades\Filament::renderHook('styles.start') }}


        @livewireStyles


        @foreach (\Filament\Facades\Filament::getStyles() as $name => $path)
            @if (\Illuminate\Support\Str::of($path)->startsWith(['http://', 'https://']))
                <link rel="stylesheet" href="{{ $path }}" />
            @elseif (\Illuminate\Support\Str::of($path)->startsWith('<'))
                {!! $path !!}
            @else
                <link rel="stylesheet" href="{{ route('filament.asset', [
                    'file' => "{$name}.css",
                ]) }}" />
            @endif
        @endforeach

        {{ \Filament\Facades\Filament::getThemeLink() }}

        {{ \Filament\Facades\Filament::renderHook('styles.end') }}

        @if (config('filament.dark_mode'))
            <script>
                const theme = localStorage.getItem('theme')

                if ((theme === 'dark') || (! theme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark')
                }
            </script>
        @endif

        {{ \Filament\Facades\Filament::renderHook('head.end') }}


    {{ \Filament\Facades\Filament::renderHook('body.start') }}


    <div>
            {{ \Filament\Facades\Filament::renderHook('content.start') }}

            {{ $slot }}

            {{ \Filament\Facades\Filament::renderHook('content.end') }}

    </div>


    {{ \Filament\Facades\Filament::renderHook('scripts.start') }}

    @livewireScripts

    <script>
        window.filamentData = @json(\Filament\Facades\Filament::getScriptData());
    </script>

    @foreach (\Filament\Facades\Filament::getBeforeCoreScripts() as $name => $path)
        @if (\Illuminate\Support\Str::of($path)->startsWith(['http://', 'https://']))
            <script defer src="{{ $path }}"></script>
        @elseif (\Illuminate\Support\Str::of($path)->startsWith('<'))
            {!! $path !!}
        @else
            <script defer src="{{ route('filament.asset', [
                    'file' => "{$name}.js",
                ]) }}"></script>
        @endif
    @endforeach

    @stack('beforeCoreScripts')

    <script defer src="{{ route('filament.asset', [
            'id' => Filament\get_asset_id('app.js'),
            'file' => 'app.js',
        ]) }}"></script>

    @if (config('filament.broadcasting.echo'))
        <script defer src="{{ route('filament.asset', [
                'id' => Filament\get_asset_id('echo.js'),
                'file' => 'echo.js',
            ]) }}"></script>

        <script>
            window.addEventListener('DOMContentLoaded', () => {
                window.Echo = new window.EchoFactory(@js(config('filament.broadcasting.echo')))

                window.dispatchEvent(new CustomEvent('EchoLoaded'))
            })
        </script>
    @endif

    @foreach (\Filament\Facades\Filament::getScripts() as $name => $path)
        @if (\Illuminate\Support\Str::of($path)->startsWith(['http://', 'https://']))
            <script defer src="{{ $path }}"></script>
        @elseif (\Illuminate\Support\Str::of($path)->startsWith('<'))
            {!! $path !!}
        @else
            <script defer src="{{ route('filament.asset', [
                    'file' => "{$name}.js",
                ]) }}"></script>
        @endif
    @endforeach

    @stack('scripts')

    {{ \Filament\Facades\Filament::renderHook('scripts.end') }}

    {{ \Filament\Facades\Filament::renderHook('body.end') }}


{{-- FILAMENT SCRIPTS END --}}
</main>

@endsection
