@extends('admin::layouts.app')

@section('content')

<div class="mx-5">

    <div class="card pb-4 pt-4">
        <div class="card-header d-flex align-items-center justify-content-between">
            <div>
                <module type="admin/modules/info_module_title" for-module="contact_form" />
            </div>
            <div>
                <button type="button" onclick="Livewire.emit('openModal', 'contact-form.settings-modal')" class="btn btn-outline-primary mb-3">
                    <i class="mdi mdi-cogs"></i> {{_e('Settings')}}
                </button>
                &nbsp; &nbsp;
                <button  type="button" onclick="Livewire.emit('openModal', 'contact-form.integrations-modal')" class="btn btn-outline-primary mb-3">
                    <i class="mdi mdi-cogs"></i> {{_e('Integrations')}}
                </button>
            </div>

        </div>
        <div class="card-body">
            @livewire('contact-form.list')
        </div>
    </div>
</div>

@endsection
