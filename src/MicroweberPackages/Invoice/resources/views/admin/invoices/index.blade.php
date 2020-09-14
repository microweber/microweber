@extends('invoice::admin.layout')

@section('card-style')
    bg-light
@endsection

@section('icon')
    <i class="mdi mdi-cash-register module-icon-svg-fill"></i>
@endsection

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

    <script type="text/javascript">
        $(document).ready(function () {
            $(".js-invoice-filter-form").change(function () {
                $('.js-invoice-filter-form').submit();
            });

            $(".js-select-all").click(function () {
                $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
            });

            $('.js-delete-all').click(function (e) {
                var id = [];
                $("input[name='id']:checked").each(function () {
                    id.push($(this).val());
                });

                $.ajax({
                    type: "POST",
                    url: '{{ route('invoices.delete') }}',
                    data: {id: id},
                    success: function (data) {
                        if (data.status == 'success') {
                            window.location = window.location;
                        }
                        if (data.status == 'danger') {
                            $('.actions-messages').html('<div class="alert alert-danger">' + data.message + '</div>');
                        }
                    }
                });
            });
        });
    </script>

    @if(count($invoices) > 0)
        <form method="get" class="js-invoice-filter-form">
            <input type="hidden" value="true" name="filter">
            <div class="row d-flex justify-content-between">
                <div class="col-auto">
                    <div class="form-inline">
                        <div class="form-group mr-1 mb-0">
                            <label for="inputInvoices2" class="sr-only">Invoices</label>
                            <input type="text" name="search" value="@if(request()->get('search')){{request()->get('search')}}@endif" class="form-control" id="inputInvoices2" placeholder="Search Invoices..">
                        </div>
                        <button type="submit" class="btn btn-outline-primary">Search</button>
                    </div>
                </div>

                <div class="col-auto">
                    @if(request()->get('filter') == 'true')
                        <a href="{{route('invoices.index')}}" class="btn btn-outline-primary icon-left btn-md"><i class="mdi mdi-close"></i> Filter</a>
                    @else
                        <button type="button" data-toggle="collapse" data-target="#show-filter" class="btn btn-outline-primary icon-left btn-md js-show-filter"><i class="mdi mdi-filter-outline"></i> Filter</button>
                    @endif
                    <a href="{{ route('invoices.create') }}" class="btn btn-primary icon-left"><i class="mdi mdi-plus"></i> New Invoice</a>
                </div>
            </div>

            <div class="collapse @if(request()->get('filter') == 'true') show @endif" id="show-filter">
                <div class="invoices-search-box bg-primary-opacity-1 rounded px-3 py-2 pb-3 mt-3">
                    <div class="row d-flex flex-nowrap">
                        <div class="col">
                            <label>Client</label>
                            <select class="form-control selectpicker" data-live-search="true" name="customer_id" placeholder="Start typing something to search customers...">
                                <option value="">Select customer..</option>
                                @foreach($customers as $customer)
                                    <option value="{{$customer->id}}">{{$customer->first_name}} {{$customer->last_name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col">
                            <label>Status</label>
                            <select name="status" class="form-control selectpicker">
                                <option @if(request()->get('status') == '') selected="selected" @endif value="">ALL</option>
                                <option disabled="disabled">Status</option>
                                <option @if(request()->get('status') == 'ANNULED') selected="selected" @endif value="ANNULED">ANNULED</option>
                                <option @if(request()->get('status') == 'REVERSAL') selected="selected" @endif value="REVERSAL">REVERSAL</option>
                                <option @if(request()->get('status') == 'PROFORMA') selected="selected" @endif value="PROFORMA">PROFORMA</option>
                                <option @if(request()->get('status') == 'ORIGINAL') selected="selected" @endif value="ORIGINAL">ORIGINAL</option>
                                <option disabled="disabled">Paid Status</option>
                                <option @if(request()->get('status') == 'UNPAID') selected="selected" @endif value="UNPAID">UNPAID</option>
                                <option @if(request()->get('status') == 'PAID') selected="selected" @endif value="PAID">PAID</option>
                                <option @if(request()->get('status') == 'PARTIALLY_PAID') selected="selected" @endif value="PARTIALLY_PAID">PARTIALLY PAID</option>
                            </select>
                        </div>

                        <div class="col">
                            <label>From</label>
                            <input type="date" class="form-control" value="@if(request()->get('from_date')){{request()->get('from_date')}}@else @endif" name="from_date">
                        </div>

                        <div class="col">
                            <label>To</label>
                            <input type="date" class="form-control" value="@if(request()->get('to_date')){{request()->get('to_date')}}@else @endif" name="to_date">
                        </div>

                        <div class="col">
                            <label>Invoice</label>
                            <div class="input-group mb-3 prepend-transparent">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-muted bg-white">#</span>
                                </div>
                                <input type="text" class="form-control" value="@if(request()->get('invoice_number')){{request()->get('invoice_number')}}@endif" name="invoice_number">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <br/>

        <div class="actions">
            <div class="actions-messages"></div>
            <button class="btn btn-danger btn-sm js-delete-all"><?php _e('Delete all'); ?></button>
        </div>

        <table class="table mt-3 small vertical-align-middle">
            <thead>
            <tr>
                <th class="border-0">
                    <div class="custom-control custom-checkbox mb-0">
                        <input type="checkbox" class="custom-control-input js-select-all" id="customCheck__">
                        <label class="custom-control-label" for="customCheck__"></label>
                    </div>
                </th>
                <th class="border-0 font-weight-bold"><?php _e('Date'); ?></th>
                <th class="border-0 font-weight-bold">Number</th>
                <th class="border-0 font-weight-bold">Customer</th>
                <th class="border-0 font-weight-bold">Status</th>
                <th class="border-0 font-weight-bold">Paid Status</th>
                <th class="border-0 font-weight-bold">Amount Due</th>
                <th class="border-0 font-weight-bold text-center"><?php _e('Action'); ?></th>
            </tr>
            </thead>
            <tbody>
            @foreach($invoices as $invoice)
                <tr class="bg-white">
                    <th>
                        <div class="custom-control custom-checkbox mb-0">
                            <input type="checkbox" class="custom-control-input js-selected-invoice" id="check-{{$invoice->id}}" name="id" value="{{$invoice->id}}">
                            <label class="custom-control-label" for="check-{{$invoice->id}}"></label>
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

    @else
        <div class="row">
            <div class="col-12">
                <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_invoices.svg'); ">
                    <h4>Manage your invoices</h4>
                    <p>You have no invoices issued yet.<br/>
                        All your invoices will appear here soon</p>
                    <br/>
                    <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-rounded">Add new invoice</a>
                </div>
            </div>
        </div>
    @endif
@endsection