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

        <div>
            <div class="my-3">
                <div class="form-group">
                    <label class="form-label d-block mb-2">Your form lists</label>
                    <select class="form-select" data-width="100%">
                        <option selected="selected">All lists</option>
                        <option>Default list (0)</option>
                    </select>
                </div>
            </div>

            <div class="form-list-toolbar my-4 d-flex align-items-center">
                <div class="col-sm-6">
                    <label class="form-label d-inline-block">Entries for list: <span>All lists</span></label>
                </div>
                <div class="col-sm-6 text-end text-right">
                    <div class="contact-form-export-search text-end text-right d-inline-block">
                        <div class="row g-2 p-0">
                            <div class="col">
                                <input autocomplete="off" class="form-control" type="text" placeholder="Search for data">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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


        <div class="row mt-4">
            <div class="col-sm-10 text-center">
                <div class="pagination justify-content-center">
                    <div class="pagination-holder">
                        pagination
                    </div>
                </div>
            </div>
            <div class="col-sm-2 text-center text-sm-right">
                <div class="form-group">
                    <form method="get">
                        <small>Show items per page</small>
                        <select class="form-select" data-size="5" data-width="100px" data-style="btn-sm" onchange="this.form.submit()">
                            <option value="">Select</option>
                            <option value="10">10</option>
                            <option value="30" selected="">30</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="200">200</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>


        <div class="row mt-1">
            <div class="col-sm-6">
                <div class="export-label">
                    <span>Export data</span>
                    <span class="btn btn-outline-primary btn-sm">Excel</span>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="text-end text-right">
                    <strong>Total:</strong>
                    <span>0 messages in this list: </span>
                </div>
            </div>
        </div>

    </div>
</div>
</div>



@endsection
