@extends('import_export_tool::admin.import-wizard.layout')

@section('content')
    <div style="width: 820px;margin:0 auto;background: #f9f9f9;padding: 15px;border-radius: 4px;">

        <livewire:import_export_tool_html_dropdown_mapping_preview importFeedId="{{$importFeedId}}" />

    </div>
@endsection
