@include('app::public.partials.header')

<main class="w-100 h-100vh ">

    @hasSection('content')
        @yield('content')
    @endif

</main>

@include('app::public.partials.footer')

