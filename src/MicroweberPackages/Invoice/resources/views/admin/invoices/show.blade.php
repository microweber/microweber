@extends('invoice::admin.layout')

@section('title', 'View Invoice')


@section('content')


    <a href="{{ route('invoices.index') }}" class="btn btn-primary"><i class="fa fa-list"></i> Back to Invoices</a>
    <a href="{{ $shareable_link }}" class="btn btn-primary" target="_blank"><i class="fa fa-file-pdf"></i> View PDF</a>
    <br/>
    <br/>

    <iframe src="{{ $shareable_link }}" style="width: 100%;height:636px;border:0px">
    </iframe>


@endsection