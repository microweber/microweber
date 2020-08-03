<script>
    mw.lib.require('bootstrap4');
</script>

<div class="container" style="margin-top: 50px">

    <h1 style="margin-bottom: 20px">Invoices - @yield('title')</h1>

    <a href="{{ route('invoices.index') }}" class="btn btn-primary"><i class="fa fa-list"></i> Manage Invoice</a>
    <a href="{{ route('invoices.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> New Invoice</a>

     |
    <a href="{{ route('tax-types.index') }}" class="btn btn-primary"><i class="fa fa-list"></i> Manage Taxes</a>
    <a href="{{ route('tax-types.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> New Tax</a>

    |
    <a href="{{ route('customers.index') }}" class="btn btn-primary"><i class="fa fa-users"></i> Manage customers</a>
    <a href="{{ route('customers.create') }}" class="btn btn-primary"><i class="fa fa-user"></i> New customer</a>
    |
    <a href="{{ route('payments.index') }}" class="btn btn-primary"><i class="fa fa-list"></i> Manage payments</a>
    <a href="{{ route('payments.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> New Payment</a>

    <br />
    <br />

    @yield('content')

</div>
