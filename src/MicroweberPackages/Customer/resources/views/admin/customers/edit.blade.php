@extends('customer::admin.layout')

@section('icon')
    <i class="mdi mdi-account-edit module-icon-svg-fill"></i>
@endsection

@if (isset($customer) && $customer)
    {{--@section('title', 'Edit customer')--}}
    @section('title', 'Client information')
@else
    {{--@section('title', 'Add new customer')--}}
    @section('title', 'Client information')
@endif

@section('content')

    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }} <br/>
            @endforeach
        </div><br/>
    @endif

    <form method="post" action="@if($customer){{ route('customers.update', $customer->id) }} @else {{ route('customers.store') }} @endif">
        @if($customer)
            @method('PUT')
        @endif
        @csrf
        <div class="row">
            <div class="col-md-6">
                <h6 class="small font-weight-bold">Client card</h6>
                <div class="card">
                    <div class="card-body">
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Display Name:</label>
                    <input type="text" class="form-control" value="@if($customer){{$customer->name}}@endif" required="required" name="name"/>
                </div>

                <div class="form-group">
                    <label class="control-label">First Name:</label>
                    <input type="text" class="form-control" value="@if($customer){{$customer->first_name}}@endif" required="required" name="first_name"/>
                </div>

                <div class="form-group">
                    <label class="control-label">Last Name:</label>
                    <input type="text" class="form-control" value="@if($customer){{$customer->last_name}}@endif" required="required" name="last_name"/>
                </div>

                <div class="form-group">
                    <label class="control-label">Email:</label>
                    <input type="email" class="form-control" value="@if($customer){{$customer->email}}@endif" required="required" name="email"/>
                </div>

                <div class="form-group">
                    <label class="control-label">Phone:</label>
                    <input type="text" class="form-control" value="@if($customer){{$customer->phone}}@endif" required="required" name="phone"/>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mt-5">
                <h3>Shipping Address</h3>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Address Name:</label>
                    <input type="text" class="form-control" value="@if(isset($customer->addresses[0])){{$customer->addresses[0]->name}}@endif" name="addresses[0][name]"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Country:</label>
                    <select class="form-control selectpicker" data-live-search="true" name="addresses[0][country_id]">
                        @foreach($countries as $country)
                            <option @if(isset($customer->addresses[0]) && $customer->addresses[0]->country_id == $country->id)selected="selected" @endif value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">State:</label>
                    <input type="text" class="form-control" value="@if(isset($customer->addresses[0])){{$customer->addresses[0]->state}}@endif" name="addresses[0][state]"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">City:</label>
                    <input type="text" class="form-control" value="@if(isset($customer->addresses[0])){{$customer->addresses[0]->city}}@endif" name="addresses[0][city]"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Adress:</label>
                    <textarea class="form-control" placeholder="Street 1" name="addresses[0][address_street_1]">@if(isset($customer->addresses[0])){{$customer->addresses[0]->address_street_1}}@endif</textarea>
                    <br/>
                    <textarea class="form-control" placeholder="Street 2" name="addresses[0][address_street_2]">@if(isset($customer->addresses[0])){{$customer->addresses[0]->address_street_2}}@endif</textarea>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Phone:</label>
                    <input type="text" class="form-control" value="@if(isset($customer->addresses[0])){{$customer->addresses[0]->phone}}@endif" name="addresses[0][phone]"/>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Zip Code:</label>
                    <input type="text" class="form-control" value="@if(isset($customer->addresses[0])){{$customer->addresses[0]->zip}}@endif" name="addresses[0][zip]"/>
                </div>
            </div>
            <input type="hidden" class="form-control" value="shipping" name="addresses[0][type]"/>
        </div>

        <hr class="thin"/>

        <div class="row d-flex justify-content-between">
            <div class="col">
                <a href="#" class="btn btn-outline-danger btn-sm">Delete</a>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success btn-sm">Save</button>
            </div>
        </div>
    </form>
@endsection

@section('order_content')
    <div class="card style-1 bg-light mb-3">
        <div class="card-header">
            <h5>
                <i class="mdi mdi-shopping text-primary mr-3"></i> <strong>Client orders</strong>
            </h5>
        </div>

        <div class="card-body pt-3">

            <div class="card style-1 mb-2 card-collapse" data-toggle="collapse" data-target="#order-item-5009">
                <div class="card-header no-border">
                    <h5><strong>Order #1</strong></h5>
                    <div>
                        <a href="#" class="btn btn-outline-primary btn-sm">Preview</a>
                        <a href="#" class="btn btn-primary btn-sm">Go to order</a>
                    </div>
                </div>

                <div class="card-body py-0">
                    <div class="collapse" id="order-item-5009">
                        dadsad
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection