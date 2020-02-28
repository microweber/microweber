<?php $path = mw_includes_url() . "toolbar/editor_tools/rte_image_editor/"; ?>

<script type="text/javascript">
    parent.mw.require("external_callbacks.js");
    mw.require("events.js");
    mw.require("forms.js");
    mw.require("files.js");
    mw.require("url.js");
</script>

<?php

if (array_key_exists('types', $_GET)) {
    $types = explode(',', $_GET['types']);

} else {
    $types = array('files', 'images', 'videos');
}


?>
<script type="text/javascript">
    var hash = window.location.hash;
    var hash = hash.replace(/#/g, "");

    hash = hash !== '' ? hash : 'insert_html';

    UpdateImage = function (url) {
        if (parent.mw.image.currentResizing) {
            if (parent.mw.image.currentResizing[0].nodeName === 'IMG') {
                parent.mw.image.currentResizing.attr("src", url);
                parent.mw.image.currentResizing.css('height', 'auto');
            }
            else {
                parent.mw.image.currentResizing.css("backgroundImage", 'url(' + mw.files.safeFilename(url) + ')');
                mw.top().wysiwyg.bgQuotesFix(parent.mw.image.currentResizing[0])
            }
        }
        if(window.thismodal) {
            thismodal.result(url);
        }
    };

    afterMediaIsInserted = function (url, todo, eventType) { /* what to do after image is uploaded (depending on the hash in the url)    */

        todo = todo || false;

        if (url === false) {
            if (eventType === 'done') {

            }
            if (window.thismodal) {
                thismodal.remove();
            }
            return false;
        }

        if (hash === 'fileWindow') {
            $('body').trigger('change', [url]);
            return false;
        }
        if (!todo) {
            if (hash !== '') {
                if (hash === 'editimage') {
                    UpdateImage(url);
                    if(parent.mw.image.currentResizing){
                        parent.mw.wysiwyg.change(parent.mw.image.currentResizing[0])
                        parent.mw.image.resize.resizerSet(parent.mw.image.currentResizing[0]);
                        parent.mw.trigger('imageSrcChanged', [parent.mw.image.currentResizing[0], url])
                    }
                } else if (hash === 'set_bg_image') {
                    parent.mw.wysiwyg.set_bg_image(url);
                    parent.mw.wysiwyg.change(parent.mw.current_element);
                    parent.mw.askusertostay = true;
                } else {
                    if (typeof parent[hash] === 'function') {
                        parent[hash](url, eventType);
                    } else {
                        if(parent.mw.iframecallbacks['insert_image']) {
                            parent.mw.iframecallbacks['insert_image'](url, eventType);
                        }

                    }
                }
            } else {
                parent.mw.wysiwyg.restore_selection();
                parent.mw.wysiwyg.insert_image(url, true);


            }
        }
    };


    GlobalEmbed = false;


    $(document).ready(function () {

        /*
         mw.$(".body.browser-liveedit .mw-browser-list-file").click(function(){

         });  */


        mw.on.hashParam('select-file', function () {

            if (hash == 'fileWindow') {
                $('body').trigger('change', [this]);
                return false;
            }

            var type = mw.url.type(this);
            GlobalEmbed = mw.embed.generate(type, this);
            if (typeof parent.mw.iframecallbacks[hash] === 'function') {
                if (hash == 'editimage') {


                    parent.mw.iframecallbacks[hash](this);
                    if(parent.mw.image.currentResizing && parent.mw.image.currentResizing){
                        parent.mw.image.resize.resizerSet(parent.mw.image.currentResizing[0]);

                    }

                } else {

                    parent.mw.iframecallbacks[hash](GlobalEmbed);
                }

            } else if (typeof parent[hash] === 'function') {

                parent[hash](GlobalEmbed)
            }
            if(parent.mw.image.currentResizing && parent.mw.image.currentResizing[0]) {
                parent.mw.trigger('imageSrcChanged', [parent.mw.image.currentResizing[0], this]);
            }

            if(window.thismodal) {
                thismodal.result(this)
            }

            parent.mw.tools.modal.remove('mw_rte_image');

            mw.notification.success('<?php _ejs('The image is changed') ?>');


        });

        Progress = mw.$('#mw-upload-progress');

        ProgressBar = Progress.find('.mw-ui-progress-bar');
        ProgressInfo = Progress.find('.mw-ui-progress-info');
        ProgressPercent = Progress.find('.mw-ui-progress-percent');
        ProgressDoneHTML = '<span class="ico iDone" style="top:-6px;"></span>&nbsp;<?php _e("Done! All files have been uploaded"); ?>.';
        ProgressErrorHTML = function (filename) {
            return '<span class="ico iRemove" style="top:-6px;"></span>&nbsp;<?php _e("Error"); ?>! "' + filename + '" - <?php _e("Invalid filetype"); ?>.';
        }


        mw.$(".mw-upload-filetypes li").each(function () {
            var li = $(this);
            var _li = this;
            var filetypes = li.dataset('type');

            var frame = mw.files.uploader({
                filetypes: filetypes
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


                if (filetypes == 'images') {


                    afterMediaIsInserted(item.src, '', "FileUploaded");
                }
                else if (filetypes == 'videos' || filetypes == 'media') {
                    afterMediaIsInserted(item.src, 'video', "FileUploaded");
                }
                else if (filetypes == 'files') {
                    if (item.src.contains("base64")) {
                        afterMediaIsInserted(item.src, '', "FileUploaded");
                    } else {
                        afterMediaIsInserted(item.src, 'files', "FileUploaded");
                    }

                }
                if(window.thismodal) {
                    thismodal.result(item.src)
                }

            });

            $(frame).bind("done", function (frame, item) {
                Progress.hide();
                //ProgressBar.width('0%');
                ProgressPercent.html('');
                ProgressInfo.html(ProgressDoneHTML);
                afterMediaIsInserted(false, '', "done");

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
            li.append(frame);
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


        urlSearcher.bind('keyup paste', function (e) {
            GlobalEmbed = false;
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

                    GlobalEmbed = mw.embed.generate(type, val);
                });
            } else {
                setTimeout(function () {
                    var val = urlSearcher.val();
                    if (hash == 'fileWindow') {

                        if(window.thismodal) {
                            thismodal.result(val)
                        }
                        $('body').trigger('change', [val]);
                        return false;
                    }
                    var type = mw.url.type(val);
                    GlobalEmbed = mw.embed.generate(type, val);
                    if (type != 'link') {
                        if (typeof parent.mw.iframecallbacks[hash] === 'function') {
                            if (hash.contains("edit")) {
                                parent.mw.iframecallbacks[hash](val);
                            } else {
                                parent.mw.iframecallbacks[hash](GlobalEmbed);
                            }
                        } else if (typeof parent[hash] === 'function') {
                            parent[hash](GlobalEmbed);

                        }
                        if(window.thismodal) {
                            thismodal.result(GlobalEmbed)
                        }
                        parent.mw.tools.modal.remove('mw_rte_image');
                    }
                }, 500);
            }

        });


        submit.click(function () {


            var val = urlSearcher.val();
            if (hash == 'fileWindow') {
                $('body').trigger('change', [val]);
                return false;
            }
            var type = mw.url.type(val);
            if (type != 'link') {
                if (typeof parent.mw.iframecallbacks[hash] === 'function') {
                    parent.mw.iframecallbacks[hash](GlobalEmbed);
                } else if (typeof parent[hash] === 'function') {
                    parent[hash](GlobalEmbed)
                }
            } else {

                if (typeof parent.mw.iframecallbacks[hash] === 'function') {
                    parent.mw.iframecallbacks[hash](val);
                } else if (typeof parent[hash] === 'function') {
                    parent[hash](val)
                }

            }
            if(window.thismodal) {
                thismodal.result(val)
            }
            if(parent.mw.image.currentResizing) {
                parent.mw.trigger('imageSrcChanged', [parent.mw.image.currentResizing[0], val]);

            }

            parent.mw.tools.modal.remove('mw_rte_image');
        });


        SetFileBrowserHeight = function () {
            var height = mw.$('#tabfilebrowser').height() + 500;
            var wh = $(parent.window).height() - 100;
            if (height > wh) {
                var height = wh;
            }
            if (height < 230) {
                var height = 230;
            }
            parent.mw.tools.modal.resize(parent.mwd.getElementById('mw_rte_image'), 730, height, true);
        };

        var selector = '#image_tabs .mw-ui-btn-nav a';

        MediaTabs = mw.tabs({
            nav: selector,
            tabs: '.tab',
            onclick: function (tab, e, i) {
                if (this.id == 'browseTab') {

                    if (!window.fileBrowserLoaded) {
                        window.fileBrowserLoaded = true;
                        mw.load_module('files/admin', '#file_module_live_edit_adm', function () {
                            setTimeout(function () {
                                SetFileBrowserHeight();
                                setTimeout(function () {
                                    SetFileBrowserHeight();
                                }, 222)
                            }, 222)

                        }, {'filetype':'images'});
                    } else {
                        SetFileBrowserHeight()
                    }


                } else {
                    parent.mw.tools.modal.resize(parent.mwd.getElementById('mw_rte_image'), 430, 230, true);
                }
                if(i === ($(selector).length - 1)){
                    thismodal.resize(800)
                } else if(i === 2){
                    thismodal.resize(600)
                } else {
                    thismodal.resize(460)
                }
            }
        })

    });
    /* end document ready  */


    mw.embed = {
        generate: function (type, url) {
            switch (type) {
                case 'link':
                    return mw.embed.link(url);
                    break;
                case 'image':
                    return mw.embed.image(url);
                    break;
                case 'youtube':
                    return mw.embed.youtube(url);
                    break;
                case 'vimeo':
                    return mw.embed.vimeo(url);
                    break;
                default:
                    return false;
            }
        },
        link: function (url, text) {
            if (!!text) {
                return '<a href="' + url + '" title="' + text + '">' + text + '</a>';
            } else {
                return '<a href="' + url + '">' + url + '</a>';
            }
        },
        image: function (url, text) {
            if (!!text) {
                return '<img class="element" src="' + url + '"  alt="' + text + '" title="' + text + '" />';
            } else {
                return '<img class="element" src="' + url + '"  alt=""  />';
            }
        },
        youtube: function (url) {
            if (url.contains('youtu.be')) {
                var id = url.split('/').pop();
                if (id == '') {
                    var id = id.pop();
                }
                return '<div class="element mw-embed-iframe" style="height:315px;width:560px;"><iframe width="560" height="315" src="https://www.youtube.com/embed/' + id + '?v=1&wmode=transparent" frameborder="0" allowfullscreen></iframe></div>';
            } else {
                var id = mw.url.getUrlParams(url).v;
                return '<div class="element mw-embed-iframe" style="height:315px;width:560px;"><iframe width="560" height="315" src="https://www.youtube.com/embed/' + id + '?v=1&wmode=transparent" frameborder="0" allowfullscreen></iframe></div>';
            }
        },
        vimeo: function (url) {
            var id = url.split('/').pop();
            if (id == '') {
                var id = id.pop();
            }
            return '<div class="element mw-embed-iframe" style="height:315px;width:560px;"><iframe src="https://player.vimeo.com/video/' + id + '?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=bc9b6a&wmode=transparent" width="560" height="315" frameborder="0" allowFullScreen></iframe></div>';
        }
    }

</script>


<style type="text/css">

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
        margin-right: 15px;
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
