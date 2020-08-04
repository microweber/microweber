

<link rel="stylesheet" href="http://ui.microweber.com/grunt/plugins/ui/css/main.css">
<!--    <link rel="stylesheet" id="main-css-style" href="http://ui.microweber.com/grunt/plugins/ui/css/main.php">-->

<!-- MW UI changes CSS -->
<!--<link rel="stylesheet" href="http://ui.microweber.com/grunt/plugins/ui/css/main.css">-->

<!-- MW UI plugins CSS -->
<link rel="stylesheet" href="http://ui.microweber.com/assets/ui/plugins/css/plugins.min.css"/>

<style>
    .form-control {
        height: 3.3rem !important;
    }
</style>


<div class="container" style="margin-top: 50px">

    <h1 style="margin-bottom: 20px"><?php _e('Invoices'); ?> @yield('title')</h1>

   {{-- <a href="{{ route('invoices.index') }}" class="btn btn-primary"><i class="fa fa-list"></i> Manage Invoice</a>

     |
    <a href="{{ route('tax-types.index') }}" class="btn btn-primary"><i class="fa fa-list"></i> Manage Taxes</a>
    <a href="{{ route('tax-types.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> New Tax</a>

    |
    <a href="{{ route('customers.index') }}" class="btn btn-primary"><i class="fa fa-users"></i> Manage customers</a>
    <a href="{{ route('customers.create') }}" class="btn btn-primary"><i class="fa fa-user"></i> New customer</a>
    |
    <a href="{{ route('payments.index') }}" class="btn btn-primary"><i class="fa fa-list"></i> Manage payments</a>
    <a href="{{ route('payments.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> New Payment</a>
--}}
    <br />
    <br />

    @yield('content')

</div>


<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="http://ui.microweber.com/assets/ui/plugins/js/jquery-3.4.1.min.js"></script>
<script src="http://ui.microweber.com/assets/ui/plugins/js/plugins.js"></script>
<script>
    $(document).ready(function () {

    });
</script>