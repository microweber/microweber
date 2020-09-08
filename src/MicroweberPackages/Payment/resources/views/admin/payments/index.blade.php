@extends('invoice::admin.layout')

@section('content')

    @if (session('status'))
        <div class="alert alert-success">
           <i class="fa fa-check"></i> {{ session('status') }}
        </div>
    @endif

    <script type="text/javascript">
        $(document).ready(function () {
            $(".js-customers-filter-form").change(function() {
                $('.js-customers-filter-form').submit();
            });
        });
    </script>

    <form method="get" class="js-customers-filter-form">
        <input type="hidden" value="true" name="filter">
        <div class="row">
            <div class="col-md-8">
                <div class="form-inline">
                    <div class="form-group mr-1 mb-2">
                        <label for="inputInvoices2" class="sr-only">Payments</label>
                        <input type="text" name="search" value="@if(request()->get('search')){{request()->get('search')}}@endif" class="form-control" id="inputInvoices2" placeholder="Search Payments..">
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-icon mb-2"><i class="mdi mdi-magnify"></i> Search</button>
                </div>
            </div>

            @if(request()->get('filter') == 'true')
                <div class="col-md-1">
                    <a href="{{route('customers.index')}}" style="margin-top: 15px" class="btn btn-outline-primary">Filter <i class="fa fa-times"></i></a>
                </div>
            @else
                <div class="col-md-1">
                    <!-- No filter -->
                </div>
            @endif

            <div class="col-md-1">
                <button type="submit" style="margin-top: 15px" class="btn btn-outline-primary">Filter <i class="fa fa-filter"></i></button>
            </div>
            <div class="col-md-2 pull-right">
                <a href="{{ route('payments.create') }}" style="margin-top: 15px"  class="btn btn-primary btn-block"><i class="fa fa-plus"></i> New Payment</a>
            </div>
        </div>
        <div class="invoices-search-box">
            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Customer</label>
                        <select class="form-control selectpicker" data-live-search="true" name="customer_id"
                                placeholder="Start typing something to search customers...">
                            <option value="">Select customer..</option>
                            @foreach($customers as $customer)
                                <option value="{{$customer->id}}">{{$customer->first_name}} {{$customer->last_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <label>Payment Number</label>
                    <input type="text" class="form-control" value="@if(request()->get('payment_number')){{request()->get('payment_number')}}@endif" name="payment_number">
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Payment Method:</label>
                        <select class="form-control selectpicker" data-live-search="true" name="payment_method_id"
                                placeholder="Start typing something to search customers...">
                            <option value="">Select payment method..</option>
                            @foreach($paymentMethods as $paymentMethod)
                                <option @if($paymentMethod->id == request()->get('payment_method_id'))selected="selected"
                                        @endif value="{{$paymentMethod->id}}">{{$paymentMethod->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <br />
    <br />

    <script type="text/javascript">
        $(document).ready(function () {
            $(".js-select-all").click(function () {
                $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
            });


            $('.js-delete-all').click(function (e) {

                var id = [];
                $("input[name='id']:checked").each (function() {
                    id.push($(this).val());
                });

                $.ajax({
                    type: "POST",
                    url: '{{ route('payments.delete') }}',
                    data: {id:id},
                    success: function(data) {
                        if (data.status == 'success') {
                            window.location = window.location;
                        }
                        if (data.status == 'danger') {
                            $('.actions-messages').html('<div class="alert alert-danger">'+data.message+'</div>');
                        }
                    }
                });
            });
        });
    </script>


    <div class="actions">
        <div class="actions-messages"></div>
        <button class="btn btn-danger js-delete-all"><i class="fa fa-times"></i> Delete all</button>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input js-select-all" id="customCheck2">
                        <label class="custom-control-label" for="customCheck2"></label>
                    </div>
                </div>
            </th>
            <th style="text-transform: uppercase">Date</th>
            <th style="text-transform: uppercase">Customer</th>
            <th style="text-transform: uppercase">Payment Mode</th>
            <th style="text-transform: uppercase">Payment Number</th>
            <th style="text-transform: uppercase">Invoice</th>
            <th style="text-transform: uppercase">Amount</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($payments as $payment):
        <tr>
            <th>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input js-selected-payment" id="customCheck{{$payment->id}}" name="id" value="{{$payment->id}}">
                        <label class="custom-control-label" for="customCheck{{$payment->id}}"></label>
                    </div>
                </div>
            </th>
            <td>{{ $payment->payment_date }}</td>
            <td>
                {{ $payment->customer->first_name }}
                {{ $payment->customer->last_name }}
            </td>
            <td>
                @if ($payment->paymentMethod)
                {{ $payment->paymentMethod->name }}
                @else
                    No Payment Method
                @endif
            </td>
            <td>{{ $payment->payment_number }}</td>
            <td>{{ $payment->invoice->invoice_number }}</td>
            <td>{{ currency_format($payment->amount) }}</td>
            <td>
                <div class="btn-group">
                    <a type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     Action
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('payments.edit', $payment->id) }}"><i class="fa fa-pen"></i> &nbsp; Edit</a>
                        <a class="dropdown-item" href="{{ route('payments.show', $payment->id) }}"><i class="fa fa-eye"></i> &nbsp; View</a>

                        <form action="{{ route('payments.destroy', $payment->id)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button class="dropdown-item"><i class="fa fa-times"></i> Delete</button>
                        </form>
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

</div>
@endsection