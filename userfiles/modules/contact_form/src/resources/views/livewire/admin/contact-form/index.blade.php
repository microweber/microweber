@extends('admin::layouts.app')

@section('content')

<div class=" col-xl-10 col-12 mx-xl-auto px-xl-0 px-3">
    <div class="card-header d-flex flex-wrap align-items-center justify-content-between">
        <div class="">
            <module type="admin/modules/info_module_title" for-module="contact_form" />
        </div>


    </div>
    <div class="card">
       <div class="card-body">
           @livewire('contact-form.list')
       </div>
    </div>

</div>

@endsection
