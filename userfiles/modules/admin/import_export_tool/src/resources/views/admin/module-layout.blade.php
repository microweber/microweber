@extends('admin::layouts.app')

@section('content')

<?php $module_info = module_info('admin/import_export_tool'); ?>

<div class="col-xxl-10 col-lg-11 col-12 mx-auto">

    <h3 class="main-pages-title">
        <strong><?php _e($module_info['name']); ?></strong>
    </h3>

    <div class="card ">
        <div class=" card-body ">
            <div class="row">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="{{route('admin.import-export-tool.index')}}" class="nav-link
                        @if(route_is('admin.import-export-tool.index')) active @endif ">
                            {{'Imports'}}
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{route('admin.import-export-tool.index-exports')}}" class="nav-link
                        @if(route_is('admin.import-export-tool.index-exports')) active @endif ">
                            {{'Exports'}}
                        </a>
                    </li>
                </ul>

                @yield('module-content')

            </div>
        </div>
    </div>

</div>
@endsection
