@extends('admin::layout')

@section('title', 'Comments')

@section('icon')
    <i class="mdi mdi-comment-search-outline module-icon-svg-fill"></i>
@endsection
<script>mw.lib.require('mwui_init');</script>

@section('content')

    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{ $error }} <br/>
            @endforeach
        </div><br/>
    @endif

    @include('comment::admin.comments.partials.list', ['contents' => $contents])

@endsection
