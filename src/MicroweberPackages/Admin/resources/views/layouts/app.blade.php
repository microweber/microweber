@include('admin::layouts.partials.header')

@hasSection('content')
    <main class="module-main-holder page-wrapper">
        @include('admin::layouts.partials.topbar2')

       <div class="page-body px-5">
           @yield('content' )
       </div>
    </main>
@endif


@include('admin::layouts.partials.footer')

