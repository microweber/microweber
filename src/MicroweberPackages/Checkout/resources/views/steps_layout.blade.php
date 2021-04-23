@hasSection('steps_content')
<div class="col-lg-6 col-12 order-lg-0 order-1">
    <div class="col-lg-8 col checkout-v2-left-column float-lg-right p-xl-5 p-md-3 p-3">

        <div class="d-flex">
            @include('checkout::logo')
        </div>

        @yield('steps_content')
    </div>
</div>
@endif
