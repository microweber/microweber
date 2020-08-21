@extends('invoice::admin.layout')


@section('icon')
    <i class="mdi mdi-account-edit module-icon-svg-fill"></i>
@endsection

@if (isset($customer) && $customer)
    @section('title', 'Edit customer')
@else
    @section('title', 'Add new customer')
@endif

@section('content')

    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }} <br/>
            @endforeach
        </div><br/>
    @endif

    @if($customer)
        <form method="post" action="{{ route('customers.update', $customer->id) }}">
            @method('PUT')
        @else
         <form method="post" action="{{ route('customers.store') }}">
        @endif
        @csrf
        <div class="row">
            <div class="col-md-12">
            <a href="{{route('customers.index')}}" class="btn btn-outline-primary mb-4"><i class="mdi mdi-account-arrow-left"></i> Back to Customers</a>
            </div>

            <div class="col-md-12">
                <h3>Basic Info</h3>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label>Display Name:</label>
                    <input type="text" class="form-control" value="@if($customer){{$customer->name}}@endif" required="required" name="name"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>First Name:</label>
                    <input type="text" class="form-control" value="@if($customer){{$customer->first_name}}@endif" required="required" name="first_name"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Last Name:</label>
                    <input type="text" class="form-control" value="@if($customer){{$customer->last_name}}@endif" required="required" name="last_name"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" class="form-control" value="@if($customer){{$customer->email}}@endif" required="required" name="email"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Phone:</label>
                    <input type="text" class="form-control" value="@if($customer){{$customer->phone}}@endif" required="required" name="phone"/>
                </div>
            </div>

            <div class="col-md-12 mt-5">
                <h3>Shipping Address</h3>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Address Name:</label>
                    <input type="text" class="form-control" value="@if(isset($customer->addresses[0])){{$customer->addresses[0]->name}}@endif" name="addresses[0][name]"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Country:</label>
                    <select class="form-control selectpicker" data-live-search="true" name="addresses[0][country_id]">
                        @foreach($countries as $country)
                           <option @if(isset($customer->addresses[0]) && $customer->addresses[0]->country_id == $country->id)selected="selected"@endif value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>State:</label>
                    <input type="text" class="form-control" value="@if(isset($customer->addresses[0])){{$customer->addresses[0]->state}}@endif" name="addresses[0][state]"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>City:</label>
                    <input type="text" class="form-control" value="@if(isset($customer->addresses[0])){{$customer->addresses[0]->city}}@endif" name="addresses[0][city]"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Adress:</label>
                    <textarea class="form-control" placeholder="Street 1" name="addresses[0][address_street_1]">@if(isset($customer->addresses[0])){{$customer->addresses[0]->address_street_1}}@endif</textarea>
                    <br />
                    <textarea class="form-control" placeholder="Street 2" name="addresses[0][address_street_2]">@if(isset($customer->addresses[0])){{$customer->addresses[0]->address_street_2}}@endif</textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Phone:</label>
                    <input type="text" class="form-control" value="@if(isset($customer->addresses[0])){{$customer->addresses[0]->phone}}@endif" name="addresses[0][phone]"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Zip Code:</label>
                    <input type="text" class="form-control" value="@if(isset($customer->addresses[0])){{$customer->addresses[0]->zip}}@endif" name="addresses[0][zip]"/>
                </div>
            </div>
            <input type="hidden" class="form-control" value="shipping" name="addresses[0][type]"/>

            <div class="col-md-12" style="margin-top:15px;">
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Customer</button>
            </div>


        </div>
    </form>

@endsection