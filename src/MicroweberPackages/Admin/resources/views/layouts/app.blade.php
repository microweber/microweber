@include('admin::layouts.partials.header')







to do ..

@section('sidebar')
    to do ..
@show
@section('content')

        {!! $content !!}

@endsection
<div class="container">
    @yield('content' )
</div>


@include('admin::layouts.partials.footer')

