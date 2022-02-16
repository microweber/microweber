@extends('user::layout')

@section('content')


    <center>
        <h3>  {{ _e('Please confirm you want to logout') }} </h3>

        <form class="form-horizontal" role="form" method="POST"
              action="{{ route('logout.submit') }}">

            @csrf

            <br />

            @if(isset($_GET['redirect_to']))
                <input type="hidden" name="redirect_to" value="{{$_GET['redirect_to']}}">
            @endif

            <button type="submit" class="btn btn-primary submit">
                {{ _e('Confirm') }}
            </button>

        </form>
    </center>

@endsection
