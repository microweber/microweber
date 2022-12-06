@extends('import_export_tool::admin.import-wizard.layout')

@section('content')

    <div class="row">
        <div class="mx-auto col-md-10">
            <div>

            <div class="input-group mb-3">
                <span class="input-group-text">Upload File Type</span>
                <select class="form-select" wire:model="import_feed.source_type">
                    <option value="download_link">Download feed from link</option>
                    <option value="upload_file">Upload feed from your computer</option>
                </select>
            </div>

            <div style="background: #f9f9f9;padding: 30px;">

                @if($this->import_feed['source_type'] == 'upload_file')
                    <div>
                        <b>Upload content feed file</b>
                    </div>
                    <div>
                        <form enctype="multipart/form-data" wire:submit.prevent="upload">
                            <div class="input-group mb-3 mt-2">
                                <input type="file" class="form-control" wire:model.defer="upload_file" style="line-height: 1.9;">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fa fa-upload"></i>
                                    Upload
                                </button>
                            </div>
                        </form>
                        @error('upload_file') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                @endif

                @if($this->import_feed['source_type'] == 'download_link')
                    <div>
                        <b>Link to content feed file</b>
                    </div>
                    <div>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" wire:model.defer="import_feed.source_url"
                                   id="source_file" placeholder="https://site.com/feed.xml">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-primary" id="source_file"
                                        wire:click="download"
                                        wire:loading.attr="disabled">
                                    Download
                                </button>
                            </div>
                        </div>
                        <div wire:loading wire:target="download">
                            <div class="spinner-border spinner-border-sm text-success" role="status"></div>
                            <span class="text-success">
                               Downloading the source file...
                           </span>
                        </div>
                    </div>
                @endif

                <div class="mt-2 js-read-feed-from-file" style="display: none">
                    <div class="spinner-border spinner-border-sm text-success" role="status"></div>
                    <span class="text-success">
                       Reading feed data...
                   </span>
                </div>

                <script type="text/javascript">
                    window.addEventListener('read-feed-from-file', event => {
                        $('.js-read-feed-from-file').show();
                        window.livewire.emit('readFeedFile');
                    });
                </script>

                    <style>
                        .js-supported-file-formats {
                            width: 400px;
                        }
                        .js-supported-file-formats img {
                            width: 50px;
                            height: 50px;
                            border: 1px solid #ccc;
                            padding: 8px;
                            border-radius: 3px;
                        }
                        .js-supported-file-formats img:hover {
                            background: #fff;
                        }
                    </style>
                <div class="d-flex align-items-center justify-content-between mt-5">
                     <div>
                         <span class="text-muted">Supported formats</span>
                     </div>
                    <div class="d-flex justify-content-between js-supported-file-formats">

                        <a href="#">
                            <img src="{{module_url('admin\import_export_tool')}}images/supported-file-formats/csv.svg" />
                        </a>

                        <a href="#">
                        <img src="{{module_url('admin\import_export_tool')}}images/supported-file-formats/excel.svg" />
                        </a>

                        <a href="#">
                        <img src="{{module_url('admin\import_export_tool')}}images/supported-file-formats/xml.svg" />
                        </a>

                        <a href="#">
                        <img src="{{module_url('admin\import_export_tool')}}images/feed.svg" />
                        </a>

                        <a href="#">
                        <img src="{{module_url('admin\import_export_tool')}}images/shopify.svg" />
                        </a>

                        <a href="#">
                        <img src="{{module_url('admin\import_export_tool')}}images/woocommerce.svg" />
                        </a>

                        <a href="#">
                        <img src="{{module_url('admin\import_export_tool')}}images/wordpress.svg" />
                        </a>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </div>

@endsection
