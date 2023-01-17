@extends('import_export_tool::admin.import-wizard.layout')

@section('ie_tool_content')
    <div>
        @if($tab == 'type')
            @include('import_export_tool::admin.import-wizard.import-type')
        @endif

        @if($tab == 'upload')
            @include('import_export_tool::admin.import-wizard.upload')
        @endif

        @if($tab == 'map')
            @include('import_export_tool::admin.import-wizard.map')
        @endif

        @if($tab == 'import')
            @include('import_export_tool::admin.import-wizard.import')
        @endif

        @if($tab == 'report')
            @include('import_export_tool::admin.import-wizard.report')
        @endif
    </div>

@endsection
