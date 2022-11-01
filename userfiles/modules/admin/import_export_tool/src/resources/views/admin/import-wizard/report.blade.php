@extends('import_export_tool::admin.import-wizard.layout')

@section('content')
    <div class="row">
        <div class="mx-auto col-md-10">
            <livewire:import_export_tool::feed_report import_feed_id="{{$import_feed['id']}}" />
        </div>
    </div>
@endsection
