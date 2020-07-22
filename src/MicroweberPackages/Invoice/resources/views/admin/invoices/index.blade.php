@extends('invoice::admin.layout')

@section('content')
    <table class="table">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">First</th>
            <th scope="col">Last</th>
            <th scope="col">Handle</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoices as $invoice):
        <tr>
            <th scope="row">{{ $invoice->id }}</th>
            <td>{{ $invoice->id }}</td>
            <td>{{ $invoice->id }}</td>
            <td>{{ $invoice->id }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>

</div>
@endsection