<div class="card style-1 mb-3">
    <div class="card-header">
        <h5>
            {{--<img src="http://localhost/microweber-12/userfiles/modules/captcha/captcha.svg" class="module-icon-svg-fill"> --}}
            @yield('icon')
            <strong>@yield('title')</strong>
        </h5>
    </div>

    {{--   <nav class="navbar navbar-expand-lg navbar-dark bg-primary" style="margin-bottom: 20px">
           <a class="navbar-brand" href="{{ route('invoices.index') }}">Invoices</a>
           <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
           </button>

           <div class="collapse navbar-collapse" id="navbarColor01">
               <ul class="navbar-nav mr-auto">
                   <li class="nav-item active">
                       <a class="nav-link" href="{{ route('invoices.index') }}"><i class="fa fa-list"></i> Invoice</a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link" href="{{ route('tax-types.index') }}"><i class="fa fa-globe"></i> Taxes</a>
                   </li>
                   <li class="nav-item">
                       <a class="nav-link" href="{{ route('customers.index') }}"><i class="fa fa-users"></i> Customers</a>
                   </li>
                   <li class="nav-itemx">
                       <a class="nav-linkx" href="{{ route('payments.index') }}"><i class="fa fa-money"></i> Payments</a>
                   </li>
               </ul>
           </div>
       </nav>--}}

    <div class="card-body pt-3">
        @yield('content')
    </div>
</div>