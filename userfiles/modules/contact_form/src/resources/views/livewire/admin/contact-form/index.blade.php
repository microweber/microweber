@extends('admin::layouts.app')

@section('content')

<div class="mx-5">

<div class="card pb-4 pt-4">
    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="contact_form" />
    </div>
    <div class="card-body">

        <div class="d-flex justify-content-end">
            <button type="button" onclick="Livewire.emit('openModal', 'contact-form.settings-modal')" class="btn btn-outline-primary mb-3">
                <i class="mdi mdi-cogs"></i> {{_e('Settings')}}
            </button>
            &nbsp; &nbsp;
            <button  type="button" onclick="Livewire.emit('openModal', 'contact-form.integrations-modal')" class="btn btn-outline-primary mb-3">
                <i class="mdi mdi-cogs"></i> {{_e('Integrations')}}
            </button>
        </div>

        <div>
            <div class="card shadow-sm mb-4 bg-silver">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col" style="max-width:55px;">
                            <i class="mdi mdi-email text-primary mdi-24px"></i>
                        </div>
                        <div class="col-10 col-sm item-id">
                            <span class="text-primary">#0</span>
                        </div>

                        <div class="col-6 col-sm">
                            May 14, 2023
                            <small class="text-muted mb-2 font-weight-bold d-block">07:40h</small>
                        </div>
                        <div class="col-6 col-sm">5 days ago</div>
                        <div class="col-2 text-end">
                            <a href="#" class="btn btn-link" onclick="Livewire.emit('openModal', 'admin-marketplace-item-modal', {{ json_encode(['name'=>1]) }})">
                                View
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>



@endsection
