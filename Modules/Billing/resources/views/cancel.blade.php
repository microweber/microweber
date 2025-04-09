@extends('template::layout')



@section('content')

    <div class="p-4 text-center w-100">

        <div class="p-5 text-center bg-image rounded-3">
            <div>
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="text-white">

                        @if (\Session::has('success'))
                            <div class="row">
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item-action list-group-item-success">{!! \Session::get('success') !!}</li>
                                </ul>
                            </div>
                        @endif

                        @if (\Session::has('error'))
                            <div class="row">
                                <ul class="list-group">
                                    <li class="list-group-item list-group-item-action list-group-item-danger">{!! \Session::get('error') !!}</li>
                                </ul>
                            </div>

                        @endif
                        @if ($backButtonUrl)
                            <h1 class="mb-3"><?php _e("Your order is canceled"); ?></h1>


                            <a href="{{ $backButtonUrl }}"
                               class=" mb-3 btn btn-outline-primary btn-lg"><?php _e("Continue"); ?></a>

                        @endif


                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection
