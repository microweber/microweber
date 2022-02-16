@extends('user::layout')

@section('content')


    <center>
        <h3>  {{ _e('Please confirm you want to logout') }} </h3>

        <form class="form-horizontal" role="form" method="POST"
              action="{{ route('logout.submit') }}">

            @csrf

            <br />

            <button type="submit" class="btn btn-primary submit">
                {{ _e('Confirm') }}
            </button>

        </form>
    </center>

@endsection
