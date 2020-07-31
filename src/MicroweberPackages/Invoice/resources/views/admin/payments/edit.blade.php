@extends('invoice::admin.layout')

@section('title', 'Manage Payments')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }} <br/>
            @endforeach
        </div><br/>
    @endif

    @if($payment)
        <form method="post" action="{{ route('payments.update', $payment->id) }}">
            @method('PUT')
        @else
         <form method="post" action="{{ route('payments.store') }}">
        @endif
        @csrf
        <div class="row">


            <div class="col-md-6">
                <div class="form-group">
                    <label>Date:</label>
                    <input type="date" class="form-control" value="@if($payment) {{$payment->date}} @endif" name="date"/>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Payment Number:</label>
                    <input type="text" class="form-control" value="@if(isset($payment) && $payment){{ $payment->payment_number }}@else{{ $nextPaymentNumber }} @endif" name="payment_number"/>
                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group">
                    <label>Customer:</label>
                    <select class="form-control typeahead border-primary" name="customer_id"
                            placeholder="Start typing something to search customers...">
                        @foreach($customers as $customer)
                            <option value="{{$customer->id}}">{{$customer->first_name}} {{$customer->last_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Invoice:</label>
                    <select class="form-control typeahead border-primary" name="invoice_id"
                            placeholder="Start typing something to search invoices...">
                        @foreach($invoices as $invoice)
                            <option value="{{$invoice->id}}">{{$invoice->invoice_number}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group">
                    <label>Amount:</label>
                    <input type="text" class="form-control" value="@if(isset($payment) && $payment){{ $payment->amount }}@endif" name="amount"/>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Payment Mode:</label>
                    <select class="form-control typeahead border-primary" name="payment_method_id"
                            placeholder="Start typing something to search customers...">
                        @foreach($paymentMethods as $paymentMethod)
                            <option value="{{$paymentMethod->id}}">{{$paymentMethod->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-md-12">
                <div class="form-group">
                    <label>Note:</label>
                    <textarea class="form-control" name="note">@if(isset($payment) && $payment){{ $payment->note }}@endif</textarea>
                </div>
            </div>


            <div class="col-md-12" style="margin-top:15px;">
                <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Payment</button>
            </div>


        </div>
    </form>

@endsection