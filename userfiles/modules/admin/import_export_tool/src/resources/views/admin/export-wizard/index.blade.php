@extends('import_export_tool::admin.export-wizard.layout')

@section('ie_tool_content')

    @if($tab == 'type')
        @include('import_export_tool::admin.export-wizard.export-type')
    @endif

    @if($tab == 'format')
        @include('import_export_tool::admin.export-wizard.export-format')
    @endif

    @if($tab == 'export')
        @include('import_export_tool::admin.export-wizard.export-file')
    @endif

@endsection
