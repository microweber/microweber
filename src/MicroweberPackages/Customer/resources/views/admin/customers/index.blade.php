@extends('customer::admin.layout')

@section('icon')
    <i class="mdi mdi-account-search module-icon-svg-fill"></i>
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
                $("input[name='id']:checked").each(function () {
                    id.push($(this).val());
                });

                $.ajax({
                    type: "POST",
                    url: $(this).attr('action'),
                    data: {id: id},
                    success: function (data) {
                        window.location = window.location;
                    }
                });
            });

        });
    </script>

    <form method="get">
        <input type="hidden" value="true" name="filter">

        <div class="row d-flex justify-content-between">
            <div class="col"></div>
            <div class="col text-right">
                @if(request()->get('filter') == 'true')
                    <a href="{{route('customers.index')}}" class="btn btn-outline-primary icon-left btn-md"><i class="mdi mdi-close"></i> Filter</a>
                @else
                    <button type="button" class="btn btn-outline-primary icon-left btn-md js-show-filter" data-toggle="collapse" data-target="#show-filter"><i class="mdi mdi-filter-outline"></i> Filter</button>
                @endif

                <a href="{{ route('customers.create') }}" class="btn btn-primary icon-left">
                    <i class="mdi mdi-plus"></i> New client
                </a>
            </div>
        </div>

        <div class="collapse @if(request()->get('filter') == 'true') show @endif" id="show-filter">
            <div class="bg-primary-opacity-1 rounded px-3 py-2 pb-3 mt-3">
                <div class="row">
                    <div class="col">
                        <label><?php _e('Search'); ?></label>
                        <input type="text" class="form-control" value="@if(request()->get('search')){{request()->get('search')}}@endif" name="search">
                    </div>

                    <div class="col">
                        <label><?php _e('Name'); ?></label>
                        <input type="text" class="form-control" value="@if(request()->get('name')){{request()->get('name')}}@endif" name="name">
                    </div>

                    <div class="col">
                        <label><?php _e('Phone'); ?></label>
                        <input type="text" class="form-control" value="@if(request()->get('phone')){{request()->get('phone')}}@endif" name="phone">
                    </div>

                    <div class="col">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-outline-primary icon-left btn-md d-block">Apply</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <br/>

    <div class="actions">
        <form method="POST" class="js-delete-selected-form" action="{{ route('customers.delete') }}">
            {{csrf_field()}}
            <button class="btn btn-danger btn-sm js-delete-all"><?php _e('Delete all'); ?></button>
        </form>
    </div>

    <table class="table mt-3 small vertical-align-middle">
        <thead>
        <tr>
            <th class="border-0">
                <div class="custom-control custom-checkbox mb-0">
                    <input type="checkbox" class="js-select-all custom-control-input" id="delete-all">
                    <label class="custom-control-label" for="delete-all">&nbsp;</label>
                </div>
            </th>
            <th class="border-0 font-weight-bold"><?php _e('Client'); ?></th>
            <th class="border-0 font-weight-bold"><?php _e('E-mail'); ?></th>
            <th class="border-0 font-weight-bold"><?php _e('Phone'); ?></th>
            <th class="border-0 font-weight-bold"><?php _e('City / Country'); ?></th>
            <th class="border-0 font-weight-bold"><?php _e('Amount Due'); ?></th>
            <th class="border-0 font-weight-bold text-center"><?php _e('Action'); ?></th>
        </tr>
        </thead>
        <tbody>
        @foreach($customers as $customer)
            <tr class="bg-white">
                <th>
                    <div class="custom-control custom-checkbox mb-0">
                        <input type="checkbox" name="id" class="js-selected-customer custom-control-input" id="delete-{{$customer->id}}" value="{{$customer->id}}">
                        <label class="custom-control-label" for="delete-{{$customer->id}}">&nbsp;</label>
                    </div>
                </th>
                <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->city }}</td>
                <td>{{ number_format($customer->due_amount, 2) }}</td>
                <td class="text-center">
                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-outline-primary btn-sm"><?php _e('View'); ?></a>
                    <a href="{{ route('customers.edit', $customer->id) }}" class="btn btn-text btn-sm text-danger"><i class="mdi mdi-trash-can-outline mdi-20px"></i></a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection