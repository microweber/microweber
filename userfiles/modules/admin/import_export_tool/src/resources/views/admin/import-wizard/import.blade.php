@extends('import_export_tool::admin.import-wizard.layout')

@section('content')

    <div class="row">
        <div class="mx-auto col-md-10">

            <table class="table">
                <tbody>
                <tr>
                    <td>
                        <b> Import {{ucfirst($import_feed['import_to']) }} to page</b>
                    </td>
                    <td>
                        <select class="form-control" id="feed_parent_page" wire:model="import_feed.parent_page">
                            <option value="0" <?php if ((0 == $import_feed['parent_page'])): ?> selected="selected" <?php endif; ?>><?php _e("None"); ?></option>
                            <?php
                            $ptOpts = array();
                            $ptOpts['link'] = "{empty}{title}";
                            $ptOpts['list_tag'] = " ";
                            $ptOpts['list_item_tag'] = "option";
                            $ptOpts['active_ids'] = $import_feed['parent_page'];
                            $ptOpts['active_code_tag'] = '   selected="selected"  ';

                            if ($import_feed['import_to'] == 'products') {
                                $ptOpts['is_shop'] = 1;
                            }
                            
                            pages_tree($ptOpts);
                            ?>
                        </select>
                    </td>
                </tr>
{{--
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
                </tr>--}}

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
                            <option value="20">20 part(s)</option>
                            <option value="30">30 part(s)</option>
                            <option value="100">100 part(s)</option>
                            <option value="200">200 part(s)</option>
                            <option value="500">500 part(s)</option>
                        </select>

                    </td>
                </tr>
{{--
                <tr>
                    <td>
                        <label for="feed_primary_key">
                            <b>Primary key</b>
                        </label>
                        <br>
                        <small>Unique Content ID or Content Model</small>
                    </td>
                    <td>
                        <select class="form-control" wire:model="import_feed.primary_key" id="feed_primary_key">
                            <option value="content_title">Content Title</option>
                            <option value="content_id">Content ID</option>
                            <option value="model">Content model</option>
                            <option value="sku">SKU</option>
                        </select>
                    </td>
                </tr>--}}


             {{--   <tr>
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
                </tr>--}}

              {{--  <tr>
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
                </tr>--}}

            </tbody>
           </table>

            <button class="btn btn-primary btn-rounded"
                    wire:click="$emit('openModal', 'import_export_tool::start_importing_modal',{importFeedId:{{$importFeedId}}})">
                <i class="fa fa-file-import"></i> Start Importing
            </button>

        </div>
    </div>
@endsection
