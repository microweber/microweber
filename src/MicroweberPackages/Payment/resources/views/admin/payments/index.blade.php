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
            <th>Customer</th>
            <th>Payment Mode</th>
            <th>Payment Number</th>
            <th>Invoice</th>
            <th>Amount</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($payments as $payment):
        <tr>
            <th><input type="checkbox"></th>
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