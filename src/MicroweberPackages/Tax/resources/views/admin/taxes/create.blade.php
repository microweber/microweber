@extends('invoice::admin.layout')

@section('title', 'Create New Tax Type')

@section('content')

    <h3>Create New Tax Type</h3>

    <br/>

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }} <br/>
            @endforeach
        </div><br/>
    @endif

    <form method="post" action="{{ route('tax-types.store') }}">
        @csrf

        <div class="row">

            <div class="col-md-12">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" class="form-control" name="name"/>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label>Prcent:</label>
                    <input type="text" class="form-control" name="percent" value="1.00"/>
                </div>
            </div>


            <div class="col-md-12">
                <div class="form-group">
                    <label>Description:</label>
                    <textarea class="form-control" name="description"></textarea>
                </div>
            </div>


            <div class="col-md-12">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="compound_tax" value="1" class="custom-control-input">
                    <label class="custom-control-label">Compound Tax</label>
                </div>
                <br />
            </div>


        </div>


        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Tax</button>
    </form>

@endsection