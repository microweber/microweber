@extends('admin::layouts.app')

@section('content')

<div class=" col-xl-10 col-12 mx-xl-auto px-xl-0 px-3">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <div class="col-sm-7 col-12">
            <module type="admin/modules/info_module_title" for-module="contact_form" />
        </div>
        <div class="col-sm-5 col-12">
            <button type="button" onclick="Livewire.emit('openModal', 'contact-form.settings-modal')" class="btn btn-outline-primary mb-3 btn-sm">
                <i class="mdi mdi-cogs"></i> {{_e('Settings')}}
            </button>
            &nbsp; &nbsp;
            <button  type="button" onclick="Livewire.emit('openModal', 'contact-form.integrations-modal')" class="btn btn-outline-primary mb-3 btn-sm">
                <i class="mdi mdi-cogs"></i> {{_e('Integrations')}}
            </button>
        </div>

    </div>
    <div class="card pb-4 pt-4">

        <div class="card-body">
            @livewire('contact-form.list')
        </div>
    </div>

</div>

@endsection
