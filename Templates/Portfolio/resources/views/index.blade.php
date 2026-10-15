@extends('templates.base::layouts.master')

@section('content')

    <div class="edit" data-layout-container field="content" rel="content">
        <h2>Hello World portfolio2</h2>
        <p>Template: {!! config('templates.portfolio2.name') !!}</p>
     </div>

@endsection
