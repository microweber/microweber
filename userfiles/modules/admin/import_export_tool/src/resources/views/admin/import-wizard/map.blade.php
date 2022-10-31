@extends('import_export_tool::admin.import-wizard.layout')

@section('content')

    <div class="row">
        <div class="mx-auto col-md-10">

            <table class="table table-borderless">
                <tbody>
                <tr>
                    <td>
                        @php
                            $fileExt = pathinfo($this->import_feed['source_file_realpath'], PATHINFO_EXTENSION);
                        @endphp
                        @if($fileExt == 'xlsx' || $fileExt == 'xlsx')
                            <label for="feed_content_tag"><b>Excel Sheet Tab</b></label><br>
                            <small>Select the excel sheet tab</small>
                        @else
                            <label for="feed_content_tag"><b>Content tag</b></label><br>
                            <small>Repeat content tag with elements</small>
                        @endif

                    </td>
                    <td>
                        <div class="input-group">
                            <select class="form-control" wire:model="import_feed.content_tag" id="feed_content_tag">

                                @if($fileExt == 'xlsx' || $fileExt == 'xlsx')
                                    <option>Select excel sheet</option>
                                @else
                                    <option>Select content tag</option>
                                @endif

                                @if(is_array($this->import_feed['detected_content_tags']))
                                    @foreach($this->import_feed['detected_content_tags'] as $contentTagKey=>$contentTagVal)
                                        <option @if($this->import_feed['content_tag'] == $contentTagKey) selected="selected" @endif value="{{$contentTagKey}}">{{$contentTagKey}}</option>
                                    @endforeach
                                @endif
                            </select>

                            @if($this->import_feed['content_tag'])
                                <button wire:click="changeContentTag" type="button" class="btn btn-outline-success">
                                    <i class="fa fa-file-import"></i> Read data

                                    <span wire:loading wire:target="changeContentTag">
                                <span class="spinner-border spinner-border-sm ml-2" role="status"></span>
                            </span>

                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>


    <div>


        @if($this->import_feed['source_content_count'])

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
        @endif

    </div>
    </div>
    </div>
@endsection
