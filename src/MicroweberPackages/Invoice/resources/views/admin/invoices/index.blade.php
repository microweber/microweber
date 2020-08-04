@extends('invoice::admin.layout')

@section('content')

    @if (session('status'))
        <div class="alert alert-success">
           <i class="fa fa-check"></i> {{ session('status') }}
        </div>
    @endif

    @if (session('status_danger'))
        <div class="alert alert-danger">
           <i class="fa fa-times"></i> {{ session('status_danger') }}
        </div>
    @endif

    <style>
        .invoices-search-box {
            margin-top: 15px;
            background-color: #d6e5fc;
            border-radius: 4px;
            padding: 9px;
            padding-top: 35px;
            padding-bottom: 35px;
        }
        .btn {
            line-height: 1.3;
        }
    </style>

    <form method="get" class="js-invoice-filter-form">
        <input type="hidden" value="true" name="filter">
        <div class="row">
            <div class="col-md-8">
                <div class="form-inline">
                    <div class="form-group mr-1 mb-2">
                        <label for="inputInvoices2" class="sr-only">Invoices</label>
                        <input type="text" name="search" value="@if(request()->get('search')){{request()->get('search')}}@endif" class="form-control" id="inputInvoices2" placeholder="Search Invoices..">
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-icon mb-2"><i class="mdi mdi-magnify"></i> Search</button>
                </div>
            </div>

            @if(request()->get('filter') == 'true')
            <div class="col-md-1">
              <a href="{{route('invoices.index')}}" style="margin-top: 15px" class="btn btn-outline-primary">Filter <i class="fa fa-times"></i></a>
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
                <a href="{{ route('invoices.create') }}" style="margin-top: 15px"  class="btn btn-primary btn-block"><i class="fa fa-plus"></i> New Invoice</a>
            </div>
        </div>
        <div class="invoices-search-box">
        <div class="row">

            <div class="col-md-3">
                <div class="form-group">
                    <label>Customer:</label>
                    <select class="form-control selectpicker" data-live-search="true" name="customer_id"
                            placeholder="Start typing something to search customers...">
                        <option value="">Select customer..</option>
                        @foreach($customers as $customer)
                            <option value="{{$customer->id}}">{{$customer->first_name}} {{$customer->last_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-2">
                <label>Status</label>
                <select name="status" class="form-control selectpicker">
                    <option disabled="disabled">Status</option>
                    <option @if(request()->get('status') == '') selected="selected"@endif value="">ALL</option>
                    <option @if(request()->get('status') == 'DUE') selected="selected"@endif value="DUE">DUE</option>
                    <option @if(request()->get('status') == 'DRAFT') selected="selected"@endif value="DRAFT">DRAFT</option>
                    <option @if(request()->get('status') == 'SENT') selected="selected"@endif value="SENT">SENT</option>
                    <option @if(request()->get('status') == 'VIEWED') selected="selected"@endif value="VIEWED">VIEWED</option>
                    <option @if(request()->get('status') == 'OVERDUE') selected="selected"@endif value="OVERDUE">OVERDUE</option>
                    <option @if(request()->get('status') == 'COMPLETED') selected="selected"@endif value="COMPLETED">COMPLETED</option>
                    <option disabled="disabled">Paid Status</option>
                    <option @if(request()->get('status') == 'UNPAID') selected="selected"@endif value="UNPAID">UNPAID</option>
                    <option @if(request()->get('status') == 'PAID') selected="selected"@endif value="PAID">PAID</option>
                    <option @if(request()->get('status') == 'PARTIALLY_PAID') selected="selected"@endif value="PARTIALLY_PAID">PARTIALLY PAID</option>
                </select>
            </div>

            <div class="col-md-2">
                <label>From date</label>
                <input type="date" class="form-control" value="@if(request()->get('from_date')){{request()->get('from_date')}}@else @endif" name="from_date">
            </div>
            <div class="col-md-2">
                <label>To date</label>
                <input type="date" class="form-control" value="@if(request()->get('to_date')){{request()->get('to_date')}}@else @endif" name="to_date">
            </div>
            <div class="col-md-3">
                <label>Invoice Number</label>
                <div class="form-group">
                    <div class="input-group mb-3 prepend-transparent append-transparent">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-muted">#</span>
                        </div>
                        <input type="text" class="form-control" value="@if(request()->get('invoice_number')){{request()->get('invoice_number')}}@endif" name="invoice_number">
                        <div class="input-group-append">
                            <span class="input-group-text" data-toggle="tooltip" title="" data-original-title="To put a product on sale, makeCompare at price the original price and enter the lower amount into Price."><i class="mdi mdi-help-circle"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </form>

    <br />
    <br />


    <script type="text/javascript">
        $(document).ready(function () {

            $(".js-invoice-filter-form").change(function() {
                $('.js-invoice-filter-form').submit();
            });

            $(".js-select-all").click(function () {
                $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
            });
            $('.js-delete-selected-form').submit(function (e) {
                e.preventDefault();

                var id = [];
                $("input[name='id']:checked").each (function() {
                    id.push($(this).val());
                });

                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: {id:id},
                    success: function(data) {
                       window.location = window.location;
                    }
                });
            });
        });
    </script>

    <div class="actions">
        <form method="POST" class="js-delete-selected-form" action="{{ route('invoices.delete') }}">
        {{csrf_field()}}
        <button class="btn btn-danger js-delete-all"><i class="fa fa-times"></i> Delete all</button>
        </form>
    </div>

    <table class="table table-hover">
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
            <th style="text-transform: uppercase">Number</th>
            <th style="text-transform: uppercase">Customer</th>
            <th style="text-transform: uppercase">Status</th>
            <th style="text-transform: uppercase">Paid Status</th>
            <th style="text-transform: uppercase">Amount Due</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoices as $invoice):
        <tr>
            <th>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input js-selected-invoice" id="customCheck1" name="id" value="{{$invoice->id}}">
                        <label class="custom-control-label" for="customCheck1"></label>
                    </div>
                </div>
            </th>
            <td>{{ $invoice->invoice_date }}</td>
            <td>{{ $invoice->invoice_number }}</td>
            <td>
                {{ $invoice->customer->first_name }}
                {{ $invoice->customer->last_name }}
            </td>
            <td>
                @if($invoice->status == 'COMPLETED')
                    <span class="badge badge-success">{{ $invoice->status }}</span>
                @elseif($invoice->status == '')
                    <span class="badge badge-warning">{{ $invoice->status }}</span>
                @elseif($invoice->status == '')
                    <span class="badge badge-warning">{{ $invoice->status }}</span>
                @else
                    <span class="badge badge-warning">{{ $invoice->status }}</span>
                @endif
            </td>
            <td>

                @if($invoice->paid_status == 'PAID')
                    <span class="badge badge-success">{{ $invoice->paid_status }}</span>
                @elseif($invoice->paid_status == 'UNPAID')
                    <span class="badge badge-danger">{{ $invoice->paid_status }}</span>
                @elseif($invoice->paid_status == 'PARTIALLY_PAID')
                    <span class="badge badge-warning">PARTIALLY PAID</span>
                @else
                    <span class="badge badge-warning">{{ $invoice->paid_status }}</span>
                @endif

            </td>
            <td>{{ currency_format($invoice->due_amount / 100) }}</td>
            <td>
                <div class="btn-group">
                    <a type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     Action
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('invoices.edit', $invoice->id) }}"><i class="fa fa-pen"></i> &nbsp; Edit</a>
                        <a class="dropdown-item" href="{{ route('invoices.show', $invoice->id) }}"><i class="fa fa-eye"></i> &nbsp; View</a>
                        <a class="dropdown-item" href="{{ route('invoices.send', $invoice->id) }}"><i class="fa fa-envelope"></i> &nbsp; Resend Invocie</a>

                        <a href="{{ route('payments.create', $invoice->id) }}" class="dropdown-item"><i class="fa fa-credit-card"></i> &nbsp; Record Payment</a>

                        <form method="post" action="{{ route('invoices.clone') }}">
                         <input type="hidden" value="{{ $invoice->id }}" name="id">
                        <button type="submit" class="dropdown-item"><i class="fa fa-copy"></i> &nbsp; Clone Invoice</button>
                        </form>
                        <form action="{{ route('invoices.destroy', $invoice->id)}}" method="post">
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