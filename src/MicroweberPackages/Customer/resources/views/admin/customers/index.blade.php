@extends('invoice::admin.layout')

@section('content')

    <script type="text/javascript">
        $(document).ready(function () {
            $(".js-select-all").click(function () {
                $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
                //$('.js-delete-all').toggle();
            });
        });
    </script>

    <form method="get">
        <div class="row well">
            <div class="col-md-3">
                <b>Name</b>
                <input type="text" class="form-control" value="@if(request()->get('name')){{request()->get('name')}}@endif" name="name">
            </div>
            <div class="col-md-3">
                <b>Contact Name</b>
                <input type="text" class="form-control" value="@if(request()->get('contact_name')){{request()->get('contact_name')}}@endif" name="contact_name">
            </div>
            <div class="col-md-3">
                <b>Phone</b>
                <input type="text" class="form-control" value="@if(request()->get('phone')){{request()->get('phone')}}@endif" name="phone">
            </div>
            <div class="col-md-2">
                <button type="submit" style="margin-top: 17px" class="btn btn-success btn-block"><i class="fa fa-filter"></i> Filter results</button>
            </div>
        </div>
    </form>

    <br />

    <div class="actions">
        <form method="POST" action="{{ route('customers.delete') }}">
            {{method_field('DELETE')}}
            {{csrf_field()}}
            <button class="btn btn-danger js-delete-all"><i class="fa fa-times"></i> Delete all</button>
        </form>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th><input type="checkbox" class="js-select-all"></th>
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