@extends('panel::user.layout')

@section('content')

    <div>
        <div class="container">

    <div class="p-4 text-center w-100">

        <div class="p-5 text-center bg-image rounded-3">
            <div>
                <div class="d-flex justify-content-center align-items-center h-100">
                    <div class="text-white">

                        @if (isset($transactionData) and isset($transactionData['value']))

                            <!-- Event snippet for Website traffic conversion page -->
                            <script>
                                setTimeout(() => {
                                    if (typeof gtag !== 'undefined') {
                                        gtag('event', 'purchase', {'send_to': 'AW-11084281555/_NM-CPGmpZoYENPtsqUp', 'value': '{{ $transactionData['value'] }}', 'currency': '{{ $transactionData['currency'] }}', 'transaction_id': '{{ $transactionData['transaction_id'] }}'});
                                    }
                                }, 500);
                            </script>

                        @endif


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
                            <h1 class="mb-3"><?php _e("Your order is complete"); ?></h1>


                            <a href="{{ $backButtonUrl }}"
                               class=" mb-3 btn btn-outline-primary btn-lg"><?php _e("Continue"); ?></a>

                        @endif


                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
    </div>

@endsection
