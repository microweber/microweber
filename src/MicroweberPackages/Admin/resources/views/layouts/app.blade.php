@include('admin::layouts.partials.header')

@hasSection('content')
    <main class="module-main-holder page-wrapper overflow-hidden">
        @include('admin::layouts.partials.topbar2')

       <div class="page-body">
           @yield('content')
       </div>
    </main>
@endif


@include('admin::layouts.partials.footer')

