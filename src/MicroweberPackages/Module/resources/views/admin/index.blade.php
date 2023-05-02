@extends('admin::layouts.app')

@section('content')
    <div id="module-admin-wrapper" class="px-5">

        @if (isset($_REQUEST['ddd']))
          <module type="admin/modules" />
        @endif

        <livewire:admin-modules-list />

    </div>
@endsection
