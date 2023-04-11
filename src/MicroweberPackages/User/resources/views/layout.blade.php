@include('app::public.partials.header')

<main class="w-100 h-100vh ">
    <div class="row my-5 d-flex align-items-center ">
        <div class="col-12 col-sm-9 col-md-7 col-lg-5 col-xl-4 mx-auto">

            @php
            if (!isset(mw()->ui->admin_logo_login_link) or mw()->ui->admin_logo_login_link == false) {
                $link = site_url();
            } else {
                $link = mw()->ui->admin_logo_login_link;
            }
            @endphp

            <a href="<?php print $link; ?>" target="_blank" id="login-logo" class="mb-4 d-block text-center">
                <img src="<?php print mw()->ui->admin_logo_login(); ?>" alt="Logo" style="max-width: 70%;"/>
            </a>

            <div class="card mb-4">
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

