@include('app::public.partials.header')

<main class="w-100 h-100vh ">
    <div class="row my-5 d-flex align-items-center ">
        <div class="col-12 col-sm-9 col-md-7 col-lg-5 col-xl-4 mx-auto">

            <div class="card">
                <div class="card-body py-4">

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    @hasSection('content')
                        @yield('content')
                    @endif


                </div>
            </div>

        </div>
    </div>

</main>

@include('app::public.partials.footer')

