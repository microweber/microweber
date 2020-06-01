<?php $path = mw_includes_url() . "toolbar/editor_tools/rte_image_editor/"; ?>

<script type="text/javascript">
    parent.mw.require("external_callbacks.js");
    mw.lib.require('mwui');
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


        mw.$(".dropable-zone").each(function () {
            var li = $(this);
            var _li = this;
            var filetypes = '<?php print join(",", $types);; ?>';

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




            });
            $(frame).on("FileUploaded", function (frame, item) {
                console.log(filetypes)

                if (filetypes.indexOf('images') !== -1) {


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
                ProgressPercent.html('');
                ProgressInfo.html(ProgressErrorHTML(file.name));
            });

            li.append(frame);

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
                    if (hash === 'fileWindow') {

                        if(window.thismodal) {
                            thismodal.result(val)
                        }
                        $('body').trigger('change', [val]);
                        return false;
                    }
                    var type = mw.url.type(val);
                    GlobalEmbed = mw.embed.generate(type, val);
                    if (type !== 'link') {
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

        var selector = '#image_tabs option';
        $('#image_tabs select').on('change', function () {
            var selected = this.options[this.selectedIndex];
            $('.tab').hide().eq(this.selectedIndex).show()
            if (selected.id === 'browseTab') {

                if (!window.fileBrowserLoaded) {
                    window.fileBrowserLoaded = true;
                    mw.load_module('files/admin', '#file_module_live_edit_adm', function () {

                    }, {'filetype':'images'});
                }

            } else {
                mw.parent().tools.modal.resize(parent.mwd.getElementById('mw_rte_image'), 430, 230, true);
            }
            if(window.thismodal){
                if(this.selectedIndex === ($(selector).length - 1)){
                    thismodal.resize(800)
                } else if(this.selectedIndex === 2){
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

    body, html {
        overflow: hidden;
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

    .tab {
        display: none;
    }

    #media_search_field {
        float: left;
        width: 275px;
        margin-right: 15px;
    }
    .image-tabs-header > div h6{
        margin-bottom: 0;
    }
    .image-tabs-header{
        display: flex;
        justify-content: space-between;
        align-content: center;
        align-items: center;
        padding-bottom: 10px;
    }


</style>
<div class="module-live-edit-settings">
    <div id="image_tabs">
        <div class="image-tabs-header">
            <div>
                <h6><strong>Media</strong></h6>
            </div>
            <div>
                <select class="selectpicker btn-as-link" data-style="btn-sm" data-width="auto">
                    <option selected><?php _e("My Computer"); ?></option>
                    <option><?php _e("URL"); ?></option>
                    <?php if (is_admin()): ?>
                        <option id="browseTab"><?php _e("Uploaded"); ?></option>
                    <?php endif; ?>
                    <?php if (is_admin()): ?>
                        <option id="unslashImagesTab"><?php _e("Media Library"); ?></option>
                    <?php endif; ?>
                </select>
            </div>
        </div>






         <div class="tab" id="drag_files_here" style="display: block">

                    <div >
                        <div class="row">
                            <div class="col-12">
                                <div class="dropable-zone">
                                    <div class="holder">
                                        <div class="dropable-zone-img"></div>

                                        <div class="progress progress-silver">
                                            <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>

                                        <button type="button" class="btn btn-primary btn-rounded">Add file</button>
                                        <p>or drop files to upload</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="drag_files_label" style="display: none;"><?php _e("Drag your files here"); ?></div>

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


    <div class="mw-ui-progress" id="mw-upload-progress" style="display: none">
        <div class="mw-ui-progress-bar" style="width: 0%;"></div>
        <div class="mw-ui-progress-info"></div>
        <span class="mw-ui-progress-percent"></span>
    </div>

</div>
