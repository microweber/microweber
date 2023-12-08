<div>

    <div class="d-flex justify-content-end px-2 py-2">
        <button type="button" class="btn-close" wire:click="$emit('closeModal', true)"></button>
    </div>

    <div class="text-center">
        <h3>
            Import Subscribers
        </h3>
    </div>

    <div class="mt-4 px-1 pb-5">

        @if (session()->has('successMessage'))
            <script id="js-success-message-{{time()}}">
                mw.notification.success('{{ session('successMessage') }}');
            </script>
        @endif

        @if (session()->has('errorMessage'))
            <script id="js-error-message-{{time()}}">
                mw.notification.error('{{ session('errorMessage') }}');
            </script>
        @endif

        <script wire:ignore type="text/javascript">

        mw.require("files.js");
        mw.require("uploader.js");

        function initJsUploader() {

            var uploader = mw.upload({
                url: route('admin.import-export-tool.upload-feed'),
                filetypes: "zip",
                multiple: false,
                element:$("#mw_uploader")
            });

            $(uploader).bind("FileUploaded", function (obj, data) {

                window.Livewire.emit('uploadFeedReadFile', data.name);

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
        }

        window.addEventListener('initJsUploader', event => {
            initJsUploader();
        });
    </script>

    <div class="row">

        <div class="mx-auto col-md-10">
            <div>

                <div class="input-group mb-3">
                    <span class="input-group-text">Upload File Type</span>
                    <select class="form-select" wire:model="importSubscribers.sourceType">
                        <option value="downloadLink">Download subscibers list from link</option>
                        <option value="uploadFile">Upload subscribers list from your computer</option>
                    </select>
                </div>

                <div style="background: #edf9ff;border-radius:8px;padding: 30px;">

                    @if($this->importSubscribers['sourceType'] == 'uploadFile')
                        <div>
                            <h5 class="font-weight-bold settings-title-inside"><?php _e('Upload your subscribers list file'); ?></h5>
                        </div>

                        <div class="mb-3 mt-2">
                            <small class="text-muted d-block mb-3"><?php _e("Upload your subscribers list file (allowed file format is xls, xlsx or csv)"); ?></small>
                            <span id="mw_uploader" class="btn btn-primary btn-rounded"><i class="mdi mdi-cloud-upload-outline"></i>&nbsp; <?php _e("Upload file"); ?></span>

                            <div id="mw_uploader_loading" class="progress mb-3" style="display:none;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">

                                </div>
                            </div>
                        </div>
                    @endif

                    @if($this->importSubscribers['sourceType'] == 'downloadLink')
                        <div>
                            <b>Link to subscribers list file</b>
                        </div>
                        <div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" wire:model.defer="importSubscribers.sourceUrl"
                                       id="source_file" placeholder="https://site.com/subscriber-list.xlsx">
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

                    <div class="mt-2 js-read-subscribers-list-from-file" style="display: none">
                        <div class="spinner-border spinner-border-sm text-success" role="status"></div>
                        <span class="text-success">
                       Reading subscribers list data...
                   </span>
                    </div>

                    <script type="text/javascript">
                        window.addEventListener('read-subscribers-list-from-file', event => {
                            $('.js-read-subscribers-list-from-file').show();
                            window.livewire.emit('readSubscribersListFile');
                        });
                    </script>

                    <style>
                        .js-supported-file-formats {
                            width: 400px;
                        }
                        .js-supported-file-formats img {
                            width: 50px;
                            height: 50px;
                            border: 1px solid #aedcd0;
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
                        <div class="d-flex justify-content-end gap-3 js-supported-file-formats">

                            <a href="#">
                                <img src="{{module_url('admin\import_export_tool')}}images/supported-file-formats/csv.svg" />
                            </a>

                            <a href="#">
                                <img src="{{module_url('admin\import_export_tool')}}images/supported-file-formats/excel.svg" />
                            </a>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    </div>
</div>
