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
                        <h5 class="font-weight-bold"><?php _e('Upload your content feed file'); ?></h5>
                    </div>

                    <div>
                        
                        <div class="mb-3 mt-2">
                            <small class="text-muted d-block mb-3"><?php _e("Upload your feed file (allowed file format is xml, xls, xlsx or csv)"); ?></small>
                            <span id="mw_uploader" class="btn btn-primary btn-rounded"><i class="mdi mdi-cloud-upload-outline"></i>&nbsp; <?php _e("Upload file"); ?></span>

                            <div id="mw_uploader_loading" class="progress mb-3" style="display:none;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                            </div>
                        </div>

                        <script wire:ignore>

                            mw.require("files.js");
                            mw.require("uploader.js");

                            $(document).ready(function () {

                                var uploader = mw.upload({
                                    url: route('admin.import-export-tool.upload-feed'),
                                    filetypes: "zip",
                                    multiple: false,
                                    element:$("#mw_uploader")
                                });

                                $(uploader).bind("FileUploaded", function (obj, data) {

                                    window.Livewire.emit('uploadFeedFile', data.name);

                                    mw.$("#mw_uploader_loading").hide();
                                    mw.$("#upload_file_info").html("");
                                    mw.$("#mw_uploader_loading .progress-bar").css({'width': "0%"});

                                    mw.notification.success('File uploaded');
                                });


                                $(uploader).bind('progress', function (up, file) {
                                    mw.$("#mw_uploader_loading").show();
                                    $('#mw_uploader_loading .progress-bar').html('Uploading file...' + file.percent + "%");
                                    mw.$("#mw_uploader_loading .progress-bar").css({'width': file.percent + "%"});
                                });

                                $(uploader).bind('error', function (up, file) {
                                    mw.$("#mw_uploader_loading").hide();
                                    mw.$("#upload_file_info").html("");
                                    mw.$("#mw_uploader_loading .progress-bar").css({'width': "0%"});
                                    mw.notification.error("The backup must be sql or zip.");
                                });
                            });
                        </script>

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
