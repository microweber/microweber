@extends('admin::layouts.app')

@section('content')

<div class="mx-5">

<div class="card pb-4 pt-4">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="contact_form" />
    </div>
    <div class="card-body">

        <div class="d-flex justify-content-end">
            <button type="button" class="btn btn-outline-primary mb-3">
                <i class="mdi mdi-cogs"></i> Settings
            </button>
            &nbsp;
            &nbsp;

            <button type="button" class="btn btn-outline-primary mb-3">
                <i class="mdi mdi-envelope"></i> Integrations
            </button>
        </div>

    </div>
</div>
</div>



@endsection
