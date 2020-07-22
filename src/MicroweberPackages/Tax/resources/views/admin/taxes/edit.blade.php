@extends('invoice::admin.layout')

@section('content')

    <h3>Edit Tax Type</h3>

    <br/>

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }} <br/>
            @endforeach
        </div><br/>
    @endif

    <form method="post" action="{{ route('tax-types.update', $taxType->id) }}">

        @method('PATCH')
        @csrf

        <div class="row">

            <div class="col-md-12">
                <div class="form-group">
                    <label>Name:</label>
                    <input type="text" class="form-control" name="name" value="{{ $taxType->name }}"/>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label>Prcent:</label>
                    <input type="text" class="form-control" name="percent" value="{{ $taxType->percent }}"/>
                </div>
            </div>


            <div class="col-md-12">
                <div class="form-group">
                    <label>Description:</label>
                    <textarea class="form-control" name="description">{{ $taxType->description }}</textarea>
                </div>
            </div>

            <div class="col-md-12">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="compound_tax" value="{{ $taxType->compound_tax }}" class="custom-control-input">
                    <label class="custom-control-label">Compound Tax</label>
                </div>
                <br />
            </div>


        </div>


        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update Tax</button>
    </form>

@endsection