@extends('customer::admin.layout')

@section('icon')
<i class="mdi mdi-account-search module-icon-svg-fill"></i>
@endsection

@section('title', _e('Clients', true))

@section('content')

<script type="text/javascript">
    $(document).ready(function () {
        $('.js-delete-all').hide();
        $(' input[type="checkbox"]').on('change', function () {
            var count = 0;
            $(' input[type="checkbox"]').each(function(){
                if($(this).prop('checked')) {
                    count++;
                    return;
                }

            })
            if(count > 0) {
                $('.js-delete-all').show();
            }
            else {
                $('.js-delete-all').hide();
            }
        });

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
        <div class="col text-end text-right">
            @if(request()->get('filter') == 'true')
            <a href="{{route('admin.customers.index')}}" class="btn btn-outline-primary icon-left btn-md"><i class="mdi mdi-close"></i> Filter</a>
            @else
            <button type="button" class="btn btn-outline-primary icon-left btn-md js-show-filter" data-bs-toggle="collapse" data-bs-target="#show-filter"><i class="mdi mdi-filter-outline"></i> <?php _e('Filter'); ?></button>
            @endif

            <a href="{{ route('admin.customers.create') }}" class="btn btn-primary icon-left">
                <i class="mdi mdi-plus"></i> <?php _e('New client'); ?>
            </a>
        </div>
    </div>

    <div class="collapse @if(request()->get('filter') == 'true') show @endif" id="show-filter">
        <div class="bg-primary-opacity-1 rounded px-3 py-2 pb-3 mt-3">
            <div class="row">
                <div class="col-md-10">
                    <label><?php _e('Search'); ?></label>
                    <input type="text" class="form-control" value="@if(request()->get('search')){{request()->get('search')}}@endif" name="search">
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-outline-primary btn-block icon-left btn-md d-block"><i class="fa fa-filter"></i> <?php _e('Apply'); ?></button>
                </div>
            </div>
        </div>
    </div>
</form>


@if($customers->count()>0)
<br/>

<div class="actions">
    <form method="POST" class="js-delete-selected-form" action="{{ route('admin.customers.delete') }}">
        {{csrf_field()}}
        <button class="btn btn btn-outline-danger js-delete-all" onclick="return confirm(mw.lang('Are you sure you want yo delete this?'))"><?php _e('Delete all'); ?></button>
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
            <th class="border-0 font-weight-bold">ID</th>
            <th class="border-0 font-weight-bold"><?php _e('Client'); ?></th>
            <th class="border-0 font-weight-bold"><?php _e('E-mail'); ?></th>
            <th class="border-0 font-weight-bold"><?php _e('Phone'); ?></th>
            <th class="border-0 font-weight-bold"><?php _e('City / Country'); ?></th>
            {{--<th class="border-0 font-weight-bold"><?php _e('Amount Due'); ?></th>--}}
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
            <td>{{ $customer->id }}</td>
            <td>{{ $customer->first_name }} {{ $customer->last_name }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->phone }}</td>
            <td>
            <?php
            $city = false;
            $country = false;
            if (isset($customer->addresses[0]->city)) {
                $city = $customer->addresses[0]->city;
            }
            if (isset($customer->addresses[0]->country_id)) {
                $findCountry = \MicroweberPackages\Country\Models\Country::where('id', $customer->addresses[0]->country_id)->first();
                if ($findCountry) {
                    $country = $findCountry->name;
                }
            }

            echo $city;
            if ($country) {
                echo ' / ' . $country;
            }
            ?>
            </td>
            {{--<td>{{ number_format($customer->due_amount, 2) }}</td>--}}
            <td class="text-center">
                <form action="{{ route('admin.customers.destroy', $customer->id)}}" method="post">
                    @csrf
                    @method('DELETE')
                    <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-outline-primary btn-sm"><?php _e('View'); ?></a>
                    <button type="submit" onclick="return confirm(mw.lang('Are you sure you want yo delete this?'))" class="btn btn-text btn-sm text-danger"><i class="mdi mdi-trash-can-outline mdi-20px"></i></button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
    @if(request()->get('filter') == 'true')

        <div class="no-items-found customers py-5">
            <div class="row">
                <div class="col-12">
                    <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_clients.svg'); ">
                        <h4><?php _e('No results found for this filter'); ?></h4>
                        <p><?php _e('Try with a different filter'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    @else

<div class="no-items-found customers py-5">
    <div class="row">
        <div class="col-12">
            <div class="no-items-box" style="background-image: url('<?php print modules_url(); ?>microweber/api/libs/mw-ui/assets/img/no_clients.svg'); ">
                <h4><?php _e('You don’t have clients yet'); ?></h4>
                <p><?php _e('Here you can mange your clients'); ?></p>
                <br/>
                <a href="{{ route('admin.customers.create') }}" class="btn btn-primary btn-rounded"><?php _e('Add client'); ?></a>
            </div>
        </div>
    </div>
</div>
    @endif
@endif
@endsection
