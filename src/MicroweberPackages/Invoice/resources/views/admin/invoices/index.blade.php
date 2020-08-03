@extends('invoice::admin.layout')

@section('content')

    @if (session('status'))
        <div class="alert alert-success">
           <i class="fa fa-check"></i> {{ session('status') }}
        </div>
    @endif


    <form method="get">
        <div class="row well">
            <div class="col-md-3">
                <b>Status</b>
                <select name="status" class="form-control">
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
                <b>From date</b>
                <input type="date" class="form-control" value="@if(request()->get('from_date')){{request()->get('from_date')}}@else{{date('Y-m-d')}}@endif" name="from_date">
            </div>
            <div class="col-md-2">
                <b>To date</b>
                <input type="date" class="form-control" value="@if(request()->get('to_date')){{request()->get('to_date')}}@else{{date('Y-m-d')}}@endif" name="to_date">
            </div>
            <div class="col-md-3">
                <b>Invoice Number</b>
                <input type="text" class="form-control" value="@if(request()->get('invoice_number')){{request()->get('invoice_number')}}@endif" name="invoice_number">
            </div>
            <div class="col-md-2">
                <button type="submit" style="margin-top: 15px" class="btn btn-success btn-block"><i class="fa fa-filter"></i> Filter results</button>
            </div>
        </div>
    </form>

    <br />
    <br />


    <script type="text/javascript">
        $(document).ready(function () {
            $(".js-select-all").click(function () {
                $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
                //$('.js-delete-all').toggle();
            });
        });
    </script>

    <div class="actions">
        <form method="POST" action="{{ route('invoices.delete') }}">
        {{method_field('DELETE')}}
        {{csrf_field()}}
        <button class="btn btn-danger js-delete-all"><i class="fa fa-times"></i> Delete all</button>
        </form>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th><input class="js-select-all" type="checkbox"></th>
            <th>Date</th>
            <th>Number</th>
            <th>Customer</th>
            <th>Status</th>
            <th>Paid Status</th>
            <th>Amount Due</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoices as $invoice):
        <tr>
            <th><input type="checkbox"></th>
            <td>{{ $invoice->invoice_date }}</td>
            <td>{{ $invoice->invoice_number }}</td>
            <td>
                {{ $invoice->customer->first_name }}
                {{ $invoice->customer->last_name }}
            </td>
            <td><span class="badge badge-warning">{{ $invoice->status }}</span></td>
            <td><span class="badge badge-warning">{{ $invoice->paid_status }}</span></td>
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