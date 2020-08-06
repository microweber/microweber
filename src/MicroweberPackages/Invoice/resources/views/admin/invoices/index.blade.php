@extends('invoice::admin.layout')

@section('title', 'Invoices')

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

    <form method="get" class="js-invoice-filter-form">
        <input type="hidden" value="true" name="filter">
        <div class="row d-flex justify-content-between">
            <div class="col-auto">
                <div class="form-inline">
                    <div class="form-group mr-1 mb-2">
                        <label for="inputInvoices2" class="sr-only">Invoices</label>
                        <input type="text" name="search" value="@if(request()->get('search')){{request()->get('search')}}@endif" class="form-control" id="inputInvoices2" placeholder="Search Invoices..">
                    </div>
                    <button type="submit" class="btn btn-outline-primary btn-icon mb-2"><i class="mdi mdi-magnify"></i> Search</button>
                </div>
            </div>

            <div class="col-auto">
                @if(request()->get('filter') == 'true')
                  <a href="{{route('invoices.index')}}" class="btn btn-outline-primary">Filter <i class="fa fa-times"></i></a>
                @endif
                <button type="submit" class="btn btn-outline-primary">Filter <i class="fa fa-filter"></i></button>
                <a href="{{ route('invoices.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> New Invoice</a>
             </div>
        </div>
        <div class="invoices-search-box bg-info mt-4 pr-2 pl-2 pt-3 pb-3">
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

            $('.js-delete-all').click(function (e) {

                var id = [];
                $("input[name='id']:checked").each (function() {
                    id.push($(this).val());
                });

                $.ajax({
                    type: "POST",
                    url: '{{ route('invoices.delete') }}',
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

    <table class="table table-hover">
        <thead>
        <tr>
            <th>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input js-select-all" id="customCheck__">
                        <label class="custom-control-label" for="customCheck__"></label>
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
                    <span class="badge badge-primary">{{ $invoice->status }}</span>
                @elseif($invoice->status == 'ORIGINAL')
                    <span class="badge badge-primary">{{ $invoice->status }}</span>
                @elseif($invoice->status == 'PROFORMA')
                    <span class="badge badge-secondary">{{ $invoice->status }}</span>
                @elseif($invoice->status == 'DRAFT')
                    <span class="badge badge-secondary">{{ $invoice->status }}</span>
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
            <td>{{ currency_format($invoice->due_amount) }}</td>
            <td>
                <div class="btn-group">
                    <a type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     Action
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('invoices.edit', $invoice->id) }}"><i class="fa fa-pen"></i> &nbsp; Edit</a>
                        <a class="dropdown-item" href="{{ route('invoices.show', $invoice->id) }}"><i class="fa fa-eye"></i> &nbsp; View</a>
                        <a class="dropdown-item" href="{{ route('invoices.send', $invoice->id) }}"><i class="fa fa-envelope"></i> &nbsp; Resend Invocie</a>

                        <a href="{{ route('payments.create') }}?invoice_id={{$invoice->id}}&amount={{$invoice->due_amount}}" class="dropdown-item"><i class="fa fa-credit-card"></i> &nbsp; Record Payment</a>

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