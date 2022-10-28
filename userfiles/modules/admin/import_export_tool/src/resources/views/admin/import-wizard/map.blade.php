@extends('import_export_tool::admin.import-wizard.layout')

@section('content')
    <div class="w-75" style="
      margin: 0 auto;
      background: #fff;
      padding: 15px;
      border-radius: 4px;
      background: #fbfbfb;
      border: 1px solid #cfcfcfa1;"
    >

        <livewire:import_export_tool_html_dropdown_mapping_preview importFeedId="{{$importFeedId}}" />

        <div class="text-center">
            <div>
            <button class="btn btn-outline-primary" wire:click="saveMapping"><i class="fa fa-arrow-right"></i> Save & Next Step</button>
            </div>
            <div wire:loading wire:target="saveMapping" class="mt-3">
                <div class="spinner-border spinner-border-sm text-success" role="status"></div>
                <span class="text-success">
                   Save mapping...
               </span>
            </div>
        </div>

    </div>
@endsection
