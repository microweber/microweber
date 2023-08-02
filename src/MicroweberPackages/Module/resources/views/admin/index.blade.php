@extends('admin::layouts.app')

@section('content')
    <div id="module-admin-wrapper" class="col-xxl-10 col-lg-11 col-12 mx-auto">

        @if (isset($_REQUEST['ddd']))
          <module type="admin/modules" />
        @endif

        <livewire:admin-modules-list />

    </div>
@endsection
