@extends('invoice::admin.layout')

@section('title', 'View Invoice')


@section('content')


  <a href="{{ $shareable_link }}">View PDF</a>
    <br />

    <iframe src="{{ $shareable_link }}" style="width: 100%;height:636px;border:0px">
    </iframe>


@endsection