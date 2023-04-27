@extends('admin::layouts.app')

@section('content')

    <div class="container-xl">

        <script>
            mw.require('<?php print modules_url() ?>microweber/api/libs/apexcharts/apexcharts.min.js', true, 'apexcharts');
        </script>
        <script>
            mw.lib.require('echarts');
        </script>

        @livewire('admin-shop-dashboard-sales')

    </div>
@endsection



