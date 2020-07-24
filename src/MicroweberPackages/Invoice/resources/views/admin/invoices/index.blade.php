@extends('invoice::admin.layout')

@section('content')

    @if (session('status'))
        <div class="alert alert-success">
           <i class="fa fa-check"></i> {{ session('status') }}
        </div>
    @endif

    <table class="table">
        <thead>
        <tr>
            <th><input type="checkbox"></th>
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
            <td>{{ $invoice->user()->first()->email }}</td>
            <td><span class="badge badge-warning">{{ $invoice->status }}</span></td>
            <td><span class="badge badge-warning">{{ $invoice->paid_status }}</span></td>
            <td>{{ number_format($invoice->due_amount, 2) }}</td>
            <td>
                <div class="btn-group">
                    <a type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     Action
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('invoices.edit', $invoice->id) }}"><i class="fa fa-pen"></i> &nbsp; Edit</a>
                        <a class="dropdown-item" href="{{ route('invoices.show', $invoice->id) }}"><i class="fa fa-eye"></i> &nbsp; View</a>
                        <a class="dropdown-item" href="{{ route('invoices.send', $invoice->id) }}"><i class="fa fa-envelope"></i> &nbsp; Resend Invocie</a>
                        <form method="post" action="{{ route('invoices.clone') }}">
                         <input type="hidden" value="{{ $invoice->id }}" name="id">
                        <button type="submit" class="dropdown-item"><i class="fa fa-credit-card"></i> &nbsp; Record Payment</button>
                        <button type="submit" class="dropdown-item"><i class="fa fa-copy"></i> &nbsp; Clone Invoice</button>
                        </form>
                        <a class="dropdown-item" href="{{ route('invoices.edit', $invoice->id) }}"><i class="fa fa-times"></i> &nbsp; Delete</a>
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

</div>
@endsection