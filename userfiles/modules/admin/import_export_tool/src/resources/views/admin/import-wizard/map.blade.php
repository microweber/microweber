@extends('import_export_tool::admin.import-wizard.layout')

@section('content')
    <div class="w-75" style="
      margin: 0 auto;
      background: #fff;
      padding: 15px;
      border-radius: 4px;
      border: 1px solid #cfcfcfa1;
      box-shadow: 0px 0px 15px #0000001f;"
    >

        <livewire:import_export_tool_html_dropdown_mapping_preview importFeedId="{{$importFeedId}}" />

    </div>
@endsection
