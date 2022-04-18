<div>
<style>
    #xml_url_result {
        width: 24px;
        height: 24px;
        line-height: 24px;
        margin: 0px 0px -8px 0px;
        display: inline-block;
    }

    /*  .icon-result-loading {
          background: url(../image/icon_loading.gif) no-repeat center !important;
      }

      .icon-result-success {
          background: url(../image/icon_success.png) no-repeat center !important;
      }

      .icon-result-error {
          background: url(../image/icon_error.png) no-repeat center !important;
      }

      .icon-result-info {
          background: url(../image/icon_info.png) no-repeat center !important;
      }*/

    #xml_url_result_text {
        font-size: 11px;
        padding-top: 5px;
        display: block;
        width: 80%;
    }

    #import .nav-tabs {
        height: 55px;
        padding-left: 10px;
    }

    #import .nav-tabs li {
        height: 55px;
        line-height: 55px;
        margin: 0px 1px 0px 1px;
    }

    #import .nav-tabs li a {
        height: 55px;
        outline: none;
        cursor: pointer;
        width: 240px;
    }

    #import .nav-tabs li a {
        background: #f9f9f9;
        border: 1px solid #e9e9e9;
        border-bottom: 1px solid #dddddd;
    }

    #import .nav-tabs .nav-link.active {
        background: #fff;
        border-bottom: 1px solid #fff;
    }

    #import .nav-tabs .nav-link.active, #import .nav-tabs .nav-link.active i, #import .nav-tabs .nav-item.show .nav-link {
        color: #000;
    }

    #import .nav-tabs li a span.number {
        width: 25px;
        height: 15px;
        line-height: 15px;
        float: left;
        margin-right: 10px;
        margin-top: 10px;
        padding: 0px 5px;
        color: #BCBCBC;
        font-size: 32px;
        font-weight: bold;
    }

    #import .nav-tabs li a span.tab-name {
        display: block;
        font-weight: bold;
        min-width: 180px;
        line-height: 20px;
    }

    #import .nav-tabs > li > a {
        color: #a5a5a5;
    }

    #import .nav-tabs li a i {
        display: block;
        font-size: 10px;
        font-style: normal;
        line-height: 10px;
        font-weight: normal;
    }
</style>
<module type="admin/modules/info"/>

@if (session()->has('message'))
    <script>
        mw.notification.success('{{ session('message') }}', 3000);
    </script>
@endif

<div class="card style-1 mb-3">

    <div class="card-header">
        <module type="admin/modules/info_module_title" for-module="admin/import_export_tool" />
    </div>

    <div class="card-body pt-3">
<div id="import">
<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
           aria-selected="true">
            <span class="number">1</span>
            <span class="tab-name">Import setup</span>
            <i>Main feed settings</i>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
           aria-controls="profile" aria-selected="false">
            <span class="number">2</span>
            <span class="tab-name">Data Mapping</span>
            <i>Assign tags to content data types</i>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab"
           aria-controls="contact" aria-selected="false">
            <span class="number">3</span>
            <span class="tab-name">Import</span>
            <i>Start importing</i>
        </a>
    </li>
</ul>

<div class="tab-content" id="myTabContent">

    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">

        <div class="row">
            <div class="col-md-8">
                <div class="card mt-4">
                    <div class="card-header">
                        Main settings
                    </div>
                    <div class="card-body">

                        <form wire:submit.prevent="submit">
                        <table class="table table-borderless">
                            <tbody>
                            <tr>
                                <td>
                                    <label for="feed_url">
                                        <b>Content feed link</b>
                                    </label>
                                    <br>
                                    <small>Link to content feed file</small>
                                </td>
                                <td>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" wire:model.defer="import_feed.source_file"
                                               id="source_file" placeholder="https://site.com/feed.xml">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-primary" id="source_file" wire:click="download" wire:loading.attr="disabled">Download</button>
                                        </div>
                                    </div>
                                    <div wire:loading wire:target="download">
                                        <div class="spinner-border spinner-border-sm text-success" role="status"></div>
                                        <span class="text-success">
                                           Downloading the source file...
                                       </span>
                                    </div>
                                    <div class="text-muted">
                                        Last downloaded: {{$import_feed['last_downloaded_date']}}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="feed_download_image_1"><b>Download images</b></label><br>
                                    <small>Download and check images</small>
                                </td>
                                <td>
                                    <input type="radio" id="feed_download_image_1" value="1" wire:model="import_feed.download_images">
                                    <label for="feed_download_image_1">Yes</label>

                                    <input type="radio" id="feed_download_image_0" value="0" wire:model="import_feed.download_images">
                                    <label for="feed_download_image_0">No</label>
                                </td>
                            </tr>
                            <tr>
                                <td><label for="feed_parts"><b>Import parts</b></label><br>
                                    <small>Split importing</small>
                                </td>
                                <td>
                                    <select class="form-control" id="feed_parts" wire:model="import_feed.split_to_parts">
                                        <option value="1">1 part(s)</option>
                                        <option value="2">2 part(s)</option>
                                        <option value="3">3 part(s)</option>
                                        <option value="4">4 part(s)</option>
                                        <option value="5">5 part(s)</option>
                                        <option value="6">6 part(s)</option>
                                        <option value="7">7 part(s)</option>
                                        <option value="8">8 part(s)</option>
                                        <option value="9">9 part(s)</option>
                                        <option value="10">10 part(s)</option>
                                    </select>

                                </td>
                            </tr>
                            <tr>
                                <td><label for="feed_content_tag"><b>Content tag</b></label><br>
                                    <small>Repeat content tag with elements</small>
                                </td>
                                <td>
                                    <select class="form-control" wire:model="import_feed.content_tag" id="feed_content_tag">
                                        <option>Select content tag</option>
                                        @if(is_array($this->import_feed['detected_content_tags']))
                                            @foreach($this->import_feed['detected_content_tags'] as $contentTagKey=>$contentTagVal)
                                                <option @if($this->import_feed['content_tag'] == $contentTagKey) selected="selected" @endif value="{{$contentTagKey}}">{{$contentTagKey}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="feed_primary_key">
                                        <b>Primary key</b></label>
                                    <br>
                                    <small>Unique Content ID or Content Model</small>
                                </td>
                                <td>
                                    <select class="form-control" wire:model="import_feed.primary_key" id="feed_primary_key">
                                        <option value="content_id" selected="selected">Content ID</option>
                                        <option value="model">Content model</option>
                                        <option value="sku">SKU</option>
                                    </select>

                                </td>
                            </tr>


                            <tr>
                                <td><label for="feed_data_old_content_action"><b>Old
                                            content</b></label><br><small>Content which are in your site but not
                                        in xml anymore</small></td>
                                <td>
                                    <select class="form-control" wire:model="import_feed.old_content_action" id="feed_data_old_content_action">
                                        <option value="nothing" selected="selected">Do nothing</option>
                                        <option value="delete">Delete</option>
                                        <option value="invisible">Invisible</option>
                                    </select>

                                </td>
                            </tr>


                            <tr>
                                <td>
                                    <label for="feed_data_update-content_name">
                                        <b>Update</b></label><br>
                                    <small>Select what will be changed in update</small>
                                </td>
                                <td>
                                    <div id="update-items" class="well well-sm"
                                         style="height: 130px; overflow: auto;">

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model.defer="import_feed.update_items"
                                                   value="description" id="feed_data_update-description" >
                                            <label for="feed_data_update-description" class="form-check-label">Description</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model.defer="import_feed.update_items"
                                                   value="categories" id="feed_data_update-category" >
                                            <label for="feed_data_update-category" class="form-check-label">Categories</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model.defer="import_feed.update_items"
                                                   value="images" id="feed_data_update-image">
                                            <label for="feed_data_update-image" class="form-check-label">Images</label>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" wire:model.defer="import_feed.update_items"
                                                   value="visible" id="feed_data_update-visible">
                                            <label for="feed_data_update-visible" class="form-check-label">Visibility</label>
                                        </div>

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="feed_delete"><b>Delete feed</b></label><br>
                                    <small>
                                        Remove this xml setting
                                    </small>
                                </td>
                                <td>
                                    @if($confirming_delete_id === $this->import_feed['id'])
                                        <button type="button" wire:click="delete({{ $this->import_feed['id'] }})" class="btn btn-outline-danger mt-3">Sure?</button>
                                    @else
                                        <button type="button" wire:click="confirmDelete({{ $this->import_feed['id'] }})" class="btn btn-outline-danger mt-3">Delete import</button>
                                    @endif
                                    <br />

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="delete-import-products" value="1"
                                               onclick="switchDeleteLink($(this).prop('checked'));">
                                        <label class="form-check-label" for="delete-import-products"> Delete products from this
                                            import</label>
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mt-4">
                    <div class="card-header">
                        Import Feeds
                        <input type="button" class="btn btn-primary btn-sm" wire:loading.attr="disabled" wire:click="$emit('openModal', 'import_export_tool_new_import_modal')" id="addImport" value="Add new import">
                    </div>
                    <div class="card-body">

                        <label for="feed_type"><b>Select import:</b></label>

                        <select class="form-control form-control-sm" onchange="window.location.href=this.value">
                            <option value="0" disabled="disabled">- select -</option>
                            @foreach($import_feed_names as $feedId=>$feedName)
                                <option @if($this->import_feed['id'] == $feedId) selected="selected" @endif value="{{route('admin.import-export-tool.import',  $feedId)}}">{{$feedName}}</option>
                            @endforeach
                        </select>

                        <input type="button" class="btn btn-success btn-block mt-3 mr-3" wire:click="save" wire:loading.attr="disabled" value="Save import">

                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">
                        Information
                    </div>
                    <div class="card-body">
                        <div id="xml-import-info">
                            <table class="table table-condensed">
                                <tbody>
                                <tr>
                                    <td class="name">Total running</td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td class="name">Feed size</td>
                                    <td>{{round($import_feed['source_file_size'] / 1024, 1)}}MB</td>
                                </tr>
                                <tr>
                                    <td class="name">Content in Feed</td>
                                    <td>0</td>
                                </tr>
                                <tr>
                                    <td class="name">Last import time</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="name">Last import start</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="name">Last import end</td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <livewire:import_export_tool_html_dropdown_mapping_preview importFeedId="{{$import_feed_id}}" />
    </div>

    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
        <br />
        <button class="btn btn-primary" wire:click="startImport">Start Importing</button>
    </div>

</div>
</div>
</div>
</div>
</div>
