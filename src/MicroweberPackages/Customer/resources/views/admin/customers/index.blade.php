@extends('invoice::admin.layout')

@section('icon')
    <i class="fa fa-user module-icon-svg-fill"></i>
@endsection

@section('title', _e('Clients', true))

@section('content')

    <script type="text/javascript">
        $(document).ready(function () {
            $(".js-select-all").click(function () {
                $("input[type=checkbox]").prop('checked', $(this).prop('checked'));
                //$('.js-delete-all').toggle();
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

    <form method="get">
        <div class="row bg-info pl-2 pr-2 pt-3 pb-3">
            <div class="col-md-3">
                <b><?php _e('Name'); ?></b>
                <input type="text" class="form-control" value="@if(request()->get('name')){{request()->get('name')}}@endif" name="name">
            </div>
            <div class="col-md-3">
                <b><?php _e('Contact Name'); ?></b>
                <input type="text" class="form-control" value="@if(request()->get('contact_name')){{request()->get('contact_name')}}@endif" name="contact_name">
            </div>
            <div class="col-md-3">
                <b><?php _e('Phone'); ?></b>
                <input type="text" class="form-control" value="@if(request()->get('phone')){{request()->get('phone')}}@endif" name="phone">
            </div>
            <div class="col-md-3">
                <button type="submit" style="margin-top: 17px" class="btn btn-success btn-block"><i class="fa fa-filter"></i> <?php _e('Filter results'); ?></button>
            </div>
        </div>
    </form>

    <br /> 

    <div class="actions">
        <form method="POST" class="js-delete-selected-form" action="{{ route('customers.delete') }}">
            {{csrf_field()}}
            <button class="btn btn-danger js-delete-all"><i class="fa fa-times"></i> <?php _e('Delete all'); ?></button>
        </form>
    </div>

    <table class="table">
        <thead>
        <tr>
            <th><input type="checkbox" class="js-select-all"></th>
            <th><?php _e('Customer Name'); ?></th>
            <th><?php _e('Email'); ?></th>
            <th><?php _e('Phone'); ?></th>
            <th><?php _e('Amount Due'); ?></th>
            <th><?php _e('Added on'); ?></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($customers as $customer):
        <tr>
            <th><input type="checkbox" name="id" class="js-selected-customer" value="{{$customer->id}}"></th>
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
                        <a class="dropdown-item" href="{{ route('customers.edit', $customer->id) }}"><i class="fa fa-pen"></i> &nbsp; <?php _e('Edit'); ?></a>
                        <a class="dropdown-item" href="{{ route('customers.show', $customer->id) }}"><i class="fa fa-eye"></i> &nbsp; <?php _e('View'); ?></a>
                        <a class="dropdown-item" href="{{ route('customers.edit', $customer->id) }}"><i class="fa fa-times"></i> &nbsp; <?php _e('Delete'); ?></a>
                    </div>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>


</div>
@endsection