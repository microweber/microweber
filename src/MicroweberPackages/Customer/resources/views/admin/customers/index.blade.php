@extends('invoice::admin.layout')

@section('content')
    <table class="table">
        <thead>
        <tr>
            <th><input type="checkbox"></th>
            <th>Customer Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Amount Due</th>
            <th>Added on</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($customers as $customer):
        <tr>
            <th><input type="checkbox"></th>
            <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->phone }}</td>
            <td>{{ number_format($customer->due_amount, 2) }}</td>
            <td>{{ $customer->created_at }}</td>
            <td>
                <div class="btn-group">
                    <a type="button" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                     Action
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="{{ route('customers.edit', $customer->id) }}"><i class="fa fa-pen"></i> &nbsp; Edit</a>
                        <a class="dropdown-item" href="{{ route('customers.show', $customer->id) }}"><i class="fa fa-eye"></i> &nbsp; View</a>
                        <a class="dropdown-item" href="{{ route('customers.edit', $customer->id) }}"><i class="fa fa-times"></i> &nbsp; Delete</a>
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>

</div>
@endsection