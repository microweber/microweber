<?php only_admin_access(); ?>

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
                            if(selected_page_id && selected_page_id == page_id){
                            	sel_title = title;
                            }
                            lis += "<li class='mw-dd-list-result' value='" + title + "' onclick='setACValue(\"" + page_id + "\",\"" + title + "\")'><a href='javascript:;'>" + title + "</a></li>";
                        }
                    }
                    var ul = el.parent().find("ul");
                    ul.find("li:gt(0)").remove();
                    ul.append(lis);
                    if(sel_title){
                    	$("#dd_pages_search").val(sel_title);
                        $('#page_id_field').val(page_id);
                    }
                });
            }
    }

    setACValue = function (page_id,title) {
		$("#dd_pages_search").val(title);
		$('#page_id_field').val(page_id).trigger('change');
    };

    function showHideFields(fieldset) {
        if(fieldset=='type') {
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
		} else if(fieldset=='source') {
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

<style>
    .module-popup-settings .mw-ui-inline-list li {
        list-style: none;
        margin-bottom: 10px;
    }
    #insert_link_list .mw-dropdown-content{ position: relative;}

    #insert_link_list {
        width: 100%;
    }
</style>
<div class="mw-modules-tabs">
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-gear"></i> <?php print _e('Settings'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <div class="module-live-edit-settings module-popup-settings">
                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Show Pop-Up event"); ?></label>
                    <ul class="mw-ui-inline-list">
                        <li>
                            <label class="mw-ui-check">
                                <input type="radio" name="type" class="mw_option_field" value="on_click" data-refresh="popup"
                                       <?php if ($type == 'on_click'): ?>checked<?php endif; ?>><span></span><span>On click link</span>
                            </label>
                        </li>
                        <li>
                            <label class="mw-ui-check">
                                <input type="radio" name="type" class="mw_option_field" value="on_click_host" data-refresh="popup"
                                       <?php if ($type == 'on_click_host'): ?>checked<?php endif; ?>><span></span><span>On click host link (<span class="tip" data-tipposition="top-center" title="Use nearest preceding link">?</span>)</span>
                            </label>
                        </li>
                        <li>
                            <label class="mw-ui-check">
                                <input type="radio" name="type" class="mw_option_field" value="on_time" data-refresh="popup"
                                       <?php if ($type == 'on_time'): ?>checked<?php endif; ?>><span></span><span>On time (<span class="tip" data-tipposition="top-center" title="Only first time (if accept) with cookies">?</span>)</span>
                            </label>
                        </li>
                        <li>
                            <label class="mw-ui-check">
                                <input type="radio" name="type" class="mw_option_field" value="on_leave_window" data-refresh="popup"
                                       <?php if ($type == 'on_leave_window'): ?>checked<?php endif; ?>><span></span><span>On Leave window</span>
                            </label>
                        </li>
                    </ul>
                </div>

                <div class="mw-ui-field-holder link_text">
                    <label class="mw-ui-label"><?php _e("Link text"); ?></label>
                    <input type="text" name="link_text" class="mw_option_field mw-ui-field mw-full-width" value="<?php print $link_text; ?>" data-refresh="popup"/>
                </div>

                <div class="mw-ui-field-holder js-time-delay" style="display: none;">
                    <label class="mw-ui-label"><?php _e("Time delay in MS"); ?></label>
                    <input type="text" name="time_delay" class="mw_option_field mw-ui-field mw-full-width" value="<?php print $time_delay; ?>" data-refresh="popup"/>
                    <small><?php _e("Only if Show Pop-Up Event is On time"); ?></small>
                </div>

                <div class="mw-ui-field-holder">
                    <label class="mw-ui-label"><?php _e("Content source"); ?></label>
                    <ul class="mw-ui-inline-list">
                        <li>
                            <label class="mw-ui-check">
                                <input type="radio" name="source" class="mw_option_field" value="edited_text" data-refresh="popup"
                                		<?php if ($source == 'edited_text'): ?>checked<?php endif; ?>><span></span><span>Edited text (<span class="tip" data-tipposition="top-center" title="Open the popup to edit the text">?</span>)</span>
                            </label>
                        </li>
                        <li>
                            <label class="mw-ui-check">
                                <input type="radio" name="source" class="mw_option_field" value="existing_page" data-refresh="popup"
                                		<?php if ($source == 'existing_page'): ?>checked<?php endif; ?>><span></span><span>Existing page content</span>
                            </label>
                        </li>
                    </ul>
                </div>

                <div id="popup_url_holder" class="mw-ui-field-holder popup_page_id">
                    <label class="mw-ui-label"><?php _e("Select existing page for popup content"); ?></label>
                    <div data-value="<?php print site_url(); ?>" id="insert_link_list" class="mw-dropdown mw-dropdown-default active">
                        <input type="hidden" class="mw_option_field" name="page_id" id="page_id_field"/>
                        <input type="text" class="mw-ui-field inactive" id="dd_pages_search" autocomplete="off" placeholder="<?php _e("Click to select"); ?>"/>
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
    <div class="mw-accordion-item">
        <div class="mw-ui-box-header mw-accordion-title">
            <div class="header-holder">
                <i class="mw-icon-beaker"></i> <?php print _e('Templates'); ?>
            </div>
        </div>
        <div class="mw-accordion-content mw-ui-box mw-ui-box-content">
            <module type="admin/modules/templates" />
        </div>
    </div>
</div>
