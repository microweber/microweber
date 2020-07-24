@extends('invoice::admin.layout')

@section('title', 'Manage Customers')

@section('content')

    @if ($errors->any())
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
                <h3>Basic Info</h3>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>First Name:</label>
                    <input type="text" class="form-control" value="@if($customer) {{$customer->first_name}} @endif" name="first_name"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Last Name:</label>
                    <input type="text" class="form-control" value="@if($customer) {{$customer->last_name}} @endif" name="last_name"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Email:</label>
                    <input type="email" class="form-control" value="@if($customer) {{$customer->email}} @endif" name="email"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Phone:</label>
                    <input type="text" class="form-control" value="@if($customer) {{$customer->phone}} @endif" name="phone"/>
                </div>
            </div>

            <div class="col-md-12">
                <h3>Billing Address</h3>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Address Name:</label>
                    <input type="text" class="form-control" value="@if(isset($customer->addresses[0])) {{$customer->addresses[0]->name}} @endif" name="addresses[0][name]"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Country:</label>
                    <input type="text" class="form-control" value="" name="addresses[0][country_id]"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>State:</label>
                    <input type="text" class="form-control" value="" name="addresses[0][state]"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>City:</label>
                    <input type="text" class="form-control" value="" name="addresses[0][city]"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Adress:</label>
                    <input type="text" class="form-control" value="" placeholder="Street 1" name="addresses[0][address_street_1]"/>
                    <input type="text" class="form-control" value="" placeholder="Street 2" name="addresses[0][address_street_2]"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Phone:</label>
                    <input type="text" class="form-control" value="" name="addresses[0][phone]"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Zip Code:</label>
                    <input type="text" class="form-control" value="" name="addresses[0][zip]"/>
                </div>
            </div>
            <input type="text" class="form-control" value="billing" name="addresses[0][type]"/>

            <div class="col-md-12" style="margin-top:15px;">
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Customer</button>
            </div>


        </div>
    </form>

@endsection