<?php $path = mw_includes_url() . "toolbar/editor_tools/file_picker/"; ?>

<script>
    mw.require("events.js");
    mw.require("forms.js");
    mw.require("files.js");
   // mw.require("tools.js");
    mw.require("url.js");
</script>

<?php

if (array_key_exists('types', $_GET)) {
    $types = explode(',', $_GET['types']);

} else {
    $types = array('files', 'images', 'videos');
}


?>
<script>


    var selectUrl = function(){
        mw.instrumentData.handler.trigger('change', Array.prototype.slice.call(arguments));
    };

    $(document).ready(function () {

        var Progress = mw.$('#mw-upload-progress');

        var ProgressBar = Progress.find('.mw-ui-progress-bar');
        var ProgressInfo = Progress.find('.mw-ui-progress-info');
        var ProgressPercent = Progress.find('.mw-ui-progress-percent');
        var ProgressDoneHTML = '<span class="ico iDone" style="top:-6px;"></span>&nbsp;<?php _e("Done! All files have been uploaded"); ?>.';
        var ProgressErrorHTML = function (filename) {
            return '<span class="ico iRemove" style="top:-6px;"></span>&nbsp;<?php _e("Error"); ?>! "' + filename + '" - <?php _e("Invalid filetype"); ?>.';
        };

        mw.on.hashParam('select-file', function (pval) {
            selectUrl(pval.toString());
        });


        mw.$(".mw-upload-filetypes li").each(function () {
            var li = $(this);
            var _li = this;
            var filetypes = li.dataset('type');

            var frame = mw.files.uploader({
                filetypes: filetypes,
                element: li
            });
            frame.width = li.width();
            frame.height = li.height();
            $(frame).bind("progress", function (frame, file) {
                Progress.show();
                Progress.width(file.percent + '%');
                ProgressPercent.html(file.percent + '%');
                ProgressInfo.html(file.name);
                li.parent().find("li").addClass('disabled');
            });
            $(frame).bind("FileUploaded", function (frame, item) {
                li.parent().find("li").removeClass('disabled');
                selectUrl(item.src);
            });

            $(frame).bind("done", function (frame, item) {
                Progress.hide();
                //ProgressBar.width('0%');
                ProgressPercent.html('');
                ProgressInfo.html(ProgressDoneHTML);

            });


            $(frame).bind("error", function (frame, file) {
                //ProgressBar.width('0%');
                ProgressPercent.html('');
                ProgressInfo.html(ProgressErrorHTML(file.name));
                li.parent().find("li").removeClass('disabled');
            });

            $(frame).bind("FilesAdded", function (frame, files_array, runtime) {
                if (runtime == 'html4') {
                    ProgressInfo.html('<?php _e("Uploading"); ?> - "' + files_array[0].name + '" ...');
                }
            });

            li.hover(function () {
                if (!li.hasClass('disabled')) {
                    li.parent().find("li").not(this).addClass('hovered');
                }

            }, function () {
                if (!li.hasClass('disabled')) {
                    li.parent().find("li").removeClass('hovered');
                }
            });
        });


        var urlSearcher = mw.$("#media_search_field");
        var submit = mw.$('#btn_insert');
        var status = mw.$("#image_status");


        urlSearcher.on('keyup paste', function (e) {
            if (e.type == 'keyup') {
                mw.on.stopWriting(urlSearcher[0], function () {
                    var val = urlSearcher.val();
                    var type = mw.url.type(val);
                    if (status[0]) {
                        status[0].className = type;
                        if (type != 'image') {
                            status.empty();
                        } else {
                            status.html('<img class="image_status_preview_image" src="' + val + '" />');
                        }
                    }
                });
            } else {
                setTimeout(function () {
                    var val = urlSearcher.val();
                    selectUrl(val);
                }, 500);
            }

        });


        submit.click(function () {
            var val = urlSearcher.val();
            selectUrl(val);
        });

        var selector = '#image_tabs .mw-ui-btn-nav a';
        MediaTabs = mw.tabs({
            nav: selector,
            tabs: '.tab',
            onclick: function (tab, e, i) {
                if (this.id === 'browseTab') {

                    if (!window.fileBrowserLoaded) {
                        window.fileBrowserLoaded = true;
                        mw.load_module('files/admin', '#file_module_live_edit_adm', function () {

                        }, {'filetype':'images'});
                    }
                }
                if(window.thismodal){
                    if(i === ($(selector).length - 1)){
                        thismodal.resize(800)
                    } else if(i === 2){
                        thismodal.resize(600)
                    } else {
                        thismodal.resize(460)
                    }
                }
            }
        })
    });
</script>


<style >

    .mw-ui-box-content {
        min-height: 138px;
    }

    .mw-upload-filetypes {
        list-style: none;
        overflow: hidden;
        position: relative;
        z-index: 1;
        text-align: center;
    }

    .mw-upload-filetypes li {
        margin: 0 3px;
        font-size: 11px;
        display: inline-block;
        position: relative;
        cursor: default;
        min-width: 100px;
        text-align: center;
        overflow: hidden;
        transition: opacity 0.12s;
        -moz-transition: opacity 0.12s;
        -webkit-transition: opacity 0.12s;
        -o-transition: opacity 0.12s;
    }

    .mw-upload-filetypes li:hover .mw-ui-btn {
        background-color: #262626;
        color: white;
        border-color: #262626;
    }

    .mw-upload-filetypes li.disabled, .mw-upload-filetypes li.hovered {
        opacity: 0.4;
    }

    .mw-upload-filetypes .mw-upload-frame {
        color: #333333;
    }

    .mw-upload-filetypes [class*='mw-icon-'] {
        font-size: 40px;
    }

    .mw-upload-filetypes li span {
        display: flex;
        white-space: nowrap;
        margin-top: 12px;
    }

    .mw-upload-filetypes li iframe {
        position: absolute;
        z-index: 1;
        top: 0;
        left: 0;
    }

    .mw-upload-filetypes li.disabled iframe, .mw-upload-filetypes li.hovered iframe {
        left: -9999px;
    }

    .mw_tabs_layout_simple .mw_simple_tabs_nav {
        padding-top: 0;
    }

    .tab {
        display: none;
    }

    #media_search_field {
        float: left;
        width: 275px;
        margin-inline-end: 15px;
    }


</style>
<div class="module-live-edit-settings">
    <div id="image_tabs">


        <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
            <a href="javascript:;" class="mw-ui-btn active"><?php _e("My Computer"); ?></a>
            <a href="javascript:;" class="mw-ui-btn"><?php _e("URL"); ?></a>
            <?php if (is_admin()): ?>
                <a href="javascript:;" class="mw-ui-btn" id="browseTab"><?php _e("Uploaded"); ?></a>
            <?php endif; ?>
             <?php if (is_admin()): ?>
                <a href="javascript:;" class="mw-ui-btn" id="unslashImagesTab"><?php _e("Media Library"); ?></a>
            <?php endif; ?>
        </div>


        <div class="mw-ui-box mw-ui-box-content">
            <div class="tab" id="drag_files_here" style="display: block">
                <div class="text-center" style="padding: 10px 0;">
                    <ul class="mw-upload-filetypes" id="">
                        <?php if (in_array('images', $types)) { ?>
                            <li class="mw-upload-filetype-image" data-type="images">
                                <div class="mw-icon-image"></div>
                                <span class="mw-ui-btn mw-ui-btn mw-ui-btn-small"><?php _e("Upload Image"); ?></span>
                            </li>
                        <?php }
                        if (in_array('videos', $types) or in_array('media', $types)) { ?>
                            <li class="mw-upload-filetype-video" data-type="media">
                                <div class="mw-icon-video"></div>
                                <span class="mw-ui-btn mw-ui-btn mw-ui-btn-small"><?php _e("Upload Media"); ?></span></li>
                        <?php }
                        if (in_array('files', $types)) { ?>
                            <li class="mw-upload-filetype-file">
                                <div class="mw-icon-file"></div>
                                <span class="mw-ui-btn mw-ui-btn mw-ui-btn-small"><?php _e("Upload File"); ?></span>
                            </li>
                        <?php } ?>
                    </ul>

                    <div class="drag_files_label" style="display: none;"><?php _e("Drag your files here"); ?></div>
                </div>
            </div>
            <div class="tab" id="get_image_from_url">


                <div id="media-search-holder" style="margin: 35px 0;">
                    <div class="mw-ui-row-nodrop">
                        <div class="mw-ui-col">
                            <div class="mw-ui-col-container">
                                <input type="text" id="media_search_field" placeholder="<?php _e("URL"); ?>" class="mw-ui-field" name="get_image_by_url" onfocus="event.preventDefault()"/>
                            </div>
                        </div>
                        <div class="mw-ui-col">
                            <div class="mw-ui-col-container">
                                <button type="button" class="mw-ui-btn" id="btn_insert"><?php _e("Insert"); ?></button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="tab" id="tabfilebrowser">
                <div id="file_module_live_edit_adm"></div>
                <?php event_trigger('live_edit_toolbar_image_search'); ?>

            </div>
            <div class="tab">
            	<module type="pictures/media_library" />
            </div>
        </div>

    </div>


    <div class="mw-ui-progress" id="mw-upload-progress" style="display: none">
        <div class="mw-ui-progress-bar" style="width: 0%;"></div>
        <div class="mw-ui-progress-info"></div>
        <span class="mw-ui-progress-percent"></span>
    </div>

</div>
