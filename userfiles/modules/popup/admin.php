<?php must_have_access(); ?>

<?php
$from_live_edit = false;
if (isset($params["live_edit"]) and $params["live_edit"]) {
    $from_live_edit = $params["live_edit"];
}
?>

<?php if (isset($params['backend'])): ?>
    <module type="admin/modules/info"/>
<?php endif; ?>

<div class="card-body mb-3 <?php if ($from_live_edit): ?>card-in-live-edit<?php endif; ?>">
    <div class="card-header">
        <?php $module_info = module_info($params['module']); ?>

        <?php if (!isset($params['live_edit'])): ?>
            <h3 class="main-pages-title">
               <?php echo $module_info['name']; ?>
           </h3>
         <?php endif; ?>
    </div>

    <div class=" ">
        <?php
        $type = get_option('type', $params['id']);
        $link_text = get_option('link_text', $params['id']);
        $source = get_option('source', $params['id']);
        $page_id = get_option('page_id', $params['id']);
        $time_delay = get_option('time_delay', $params['id']);
        if (!$time_delay) {
            $time_delay = 3000;
        }

        // TODO: fix module refresh on closing settings
        ?>

        <script type="text/javascript">
            is_searching = false;
            mw.dd_autocomplete = function (id, selected_page_id = false) {
                var el = $(id);
                if (!is_searching) {
                    var val = el.val();
                    // NB: param is_active: 'y', - fails
                    mw.tools.ajaxSearch({keyword: val, content_type: 'page', order_by: 'title', limit: 20}, function () {
                        var lis = "";
                        var json = this;
                        var title = "";
                        var page_id = "";
                        var sel_title = ""
                        for (var item in json) {
                            var obj = json[item];
                            if (typeof obj === 'object') {
                                title = obj.title;
                                page_id = obj.id;
                                if (selected_page_id && selected_page_id == page_id) {
                                    sel_title = title;
                                }
                                lis += "<li class='mw-dd-list-result' value='" + title + "' onclick='setACValue(\"" + page_id + "\",\"" + title + "\")'><a href='javascript:;'>" + title + "</a></li>";
                            }
                        }
                        var ul = el.parent().find("ul");
                        ul.find("li:gt(0)").remove();
                        ul.append(lis);
                        if (sel_title) {
                            $("#dd_pages_search").val(sel_title);
                            $('#page_id_field').val(page_id);
                        }
                    });
                }
            }

            setACValue = function (page_id, title) {
                $("#dd_pages_search").val(title);
                $('#page_id_field').val(page_id).trigger('change');
            };

            function showHideFields(fieldset) {
                if (fieldset == 'type') {
                    var selectedType = $('input[name="type"]:checked').val();
                    if (selectedType == 'on_time') {
                        $('.js-time-delay').show();
                        $('.link_text').hide();
                    } else if (selectedType == 'on_click') {
                        $('.js-time-delay').hide();
                        $('.link_text').show();
                    } else {
                        $('.js-time-delay').hide();
                        $('.link_text').hide();
                    }
                } else if (fieldset == 'source') {
                    var selectedType = $('input[name="source"]:checked').val();
                    if (selectedType == 'existing_page') {
                        $('.popup_page_id').show();
                    } else {
                        $('.popup_page_id').hide();
                    }
                }
            }
            $(document).ready(function () {
                showHideFields('type');
                $('input[name="type"]').on('change', function () {
                    showHideFields('type');
                });
                showHideFields('source');
                $('input[name="source"]').on('change', function () {
                    showHideFields('source');
                });

                mw.tools.dropdown();

                <?php if(!empty($page_id)): ?>
                var selected_page_id = <?php print $page_id;?>
                <?php else: ?>
                var selected_page_id = false;
                <?php endif; ?>

                mw.dd_autocomplete('#dd_pages_search', selected_page_id);
            });
        </script>


        <nav class="nav nav-pills nav-justified btn-group btn-group-toggle btn-hover-style-3">
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs  active" data-bs-toggle="tab" href="#settings">  <?php _e('Settings'); ?></a>
            <a class="btn btn-link justify-content-center mw-admin-action-links mw-adm-liveedit-tabs " data-bs-toggle="tab" href="#templates">   <?php _e('Templates'); ?></a>
        </nav>

        <div class="tab-content py-3">
            <div class="tab-pane fade show active" id="settings">
                <div class="module-live-edit-settings module-popup-settings">
                    <div class="form-group">
                        <label class="form-label font-weight-bold mb-3 d-block"><?php _e("Show Pop-Up event"); ?></label>

                        <label class="form-check">
                            <input type="radio" id="type1" name="type" class="mw_option_field form-check-input" value="on_click" data-refresh="popup" <?php if ($type == 'on_click'): ?>checked<?php endif; ?>>
                            <span class="form-check-label"><?php _e("On button click"); ?></span>
                        </label>

                        <label class="form-check">
                            <input type="radio" id="type2" name="type" class="mw_option_field form-check-input" value="on_click_host" data-refresh="popup" <?php if ($type == 'on_click_host'): ?>checked<?php endif; ?>>
                            <span class="form-check-label">
                                <?php _e("On host link click"); ?>
                                <span data-bs-toggle="tooltip" title="Use nearest preceding link"><i class="mdi mdi-help-circle"></i></span>
                            </span>
                        </label>

                        <label class="form-check">
                            <input type="radio" id="type3" name="type" class="mw_option_field form-check-input" value="on_time" data-refresh="popup" <?php if ($type == 'on_time'): ?>checked<?php endif; ?>>
                            <span class="form-check-label"><?php _e("On time"); ?>
                            <span data-bs-toggle="tooltip" title="Only first time (if accept) with cookies"><i class="mdi mdi-help-circle"></i></span>

                            </span>
                        </label>

                        <label class="form-check">
                            <input type="radio" id="type4" name="type" class="mw_option_field form-check-input" value="on_leave_window" data-refresh="popup" <?php if ($type == 'on_leave_window'): ?>checked<?php endif; ?>>
                            <span class="form-check-label"><?php _e("On Leave window"); ?></span>
                        </label>

                    </div>

                    <div class="form-group link_text">
                        <label class="form-label font-weight-bold mb-3"><?php _e("Button text"); ?></label>
                        <input type="text" name="link_text" class="mw_option_field form-control" value="<?php print $link_text; ?>" data-refresh="popup"/>
                    </div>

                    <div class="form-group js-time-delay" style="display: none;">
                        <label class="form-label font-weight-bold mb-3"><?php _e("Time delay in MS"); ?></label>
                        <small class="text-muted d-block mb-2"><?php _e("Only if Show Pop-Up Event is On time"); ?></small>
                        <input type="text" name="time_delay" class="mw_option_field form-control" value="<?php print $time_delay; ?>" data-refresh="popup"/>
                    </div>

                    <div class="form-group">
                        <label class="form-label font-weight-bold mb-3 d-block"><?php _e("Content source"); ?></label>

                        <label class="form-check">
                            <input type="radio" id="type1" name="type" class="mw_option_field form-check-input" value="on_click" data-refresh="popup" <?php if ($type == 'on_click'): ?>checked<?php endif; ?>>
                            <span class="form-check-label"><?php _e("On button click"); ?></span>
                        </label>

                        <label class="form-check">
                            <input type="radio" id="source1" name="source" class="mw_option_field form-check-input" value="edited_text" data-refresh="popup" <?php if ($source == 'edited_text'): ?>checked<?php endif; ?>>
                            <span class="form-check-label"><?php _e("Edited text"); ?> (<span class="tip" data-tipposition="top-center" title="Open the popup to edit the text">?</span>)</span>
                        </label>

                        <label class="form-check">
                            <input type="radio" id="source2" name="source" class="mw_option_field form-check-input" value="existing_page" data-refresh="popup" <?php if ($source == 'existing_page'): ?>checked<?php endif; ?>>
                            <span class="form-check-label"><?php _e("Existing page content"); ?></span>
                        </label>
                    </div>

                    <div id="popup_url_holder" class="form-group popup_page_id">
                        <label class="form-label font-weight-bold mb-3"><?php _e("Select existing page for popup content"); ?></label>
                        <div>
                            <div data-value="<?php print site_url(); ?>" id="insert_link_list" class="mw-dropdown mw-dropdown-default active">
                                <input type="hidden" class="mw_option_field" name="page_id" id="page_id_field"/>
                                <input type="text" class="form-select  inactive" id="dd_pages_search" autocomplete="off" placeholder="<?php _e("Click to select"); ?>"/>
                                <span class="mw-icon-dropdown"></span>
                                <div class="mw-dropdown-content">
                                    <ul class="">
                                        <li class="other-action" value="-1" style="display: none;"></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="templates">
                <module type="admin/modules/templates"/>
            </div>
        </div>
    </div>
</div>
