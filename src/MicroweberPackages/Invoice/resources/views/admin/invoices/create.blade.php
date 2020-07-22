@extends('invoice::admin.layout')

@section('title', 'Create Invoice')


@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }} <br/>
            @endforeach
        </div><br/>
    @endif

    <form method="post" action="{{ route('invoices.store') }}">
        @csrf

        <div class="row">

            <div class="col-md-12">
                <div class="form-group">
                    <label>Customer:</label>
                    <select class="form-control" name="user_id">
                        @foreach($users as $user):
                        <option value="{{$user->id}}">{{$user->email}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-md-4">
                <div class="form-group">
                    <label>Invoice Number:</label>
                    <input type="text" class="form-control" value="{{ $nextInvoiceNumber }}" name="nextInvoiceNumber"/>
                </div>
            </div>


            <div class="col-md-4">
                <div class="form-group">
                    <label>Invoice Date:</label>
                    <input type="date" class="form-control" value="{{ date('Y-m-d') }}" name="invoice_date"/>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label>Invoice Due Date:</label>
                    <input type="date" class="form-control"
                           value="{{ date('Y-m-d', strtotime('+6 days', strtotime(date('Y-m-d')))) }}" name="due_date"/>
                </div>
            </div>

            <div class="col-md-12">
                <div style="width:300px;float:right;background:#fff;border-radius: 3px;padding-top: 15px;padding-bottom: 15px;">

                    <div class="form-group col-md-12">
                        <label>Sub total:</label>
                        <input type="text" disabled="disabled" class="form-control" value="1.00" />
                    </div>

                    <div class="container">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Discount:</label>
                                <input type="text" class="form-control" value="0.00" name="discount_val"/>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Discount Type:</label>
                                <select class="form-control" name="discount">
                                    <option value="fixed">Fixed</option>
                                    <option value="precentage">Precentage</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="form-group col-md-12">
                        <label>Total:</label>
                        <input type="text" disabled="disabled" class="form-control" value="1.00" />
                    </div>

                </div>
            </div>
        </div>

        <input type="hidden" value="1.00" name="total"/>
        <input type="hidden" value="1.00" name="sub_total"/>

        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Invoice</button>
    </form>

    </div>
@endsection