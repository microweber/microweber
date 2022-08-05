<?php $path = mw_includes_url() . "toolbar/editor_tools/rte_image_editor/"; ?>

<script>
    mw.parent().require("external_callbacks.js");
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

if (array_key_exists('title', $_GET)) {
    $title = $_GET['title'];

} else {
    $title = __('Media');
}


?>

<div id="filepick"></div>

<script>
    var hash = location.hash.replace(/#/g, "");

    hash = hash !== '' ? hash : 'insert_html';

    UpdateImage = function (url) {
        if (mw.parent().image.currentResizing) {
            if (mw.parent().image.currentResizing[0].nodeName === 'IMG') {
                mw.parent().image.currentResizing.attr("src", url);
                mw.parent().image.currentResizing.css('height', 'auto');
            }
            else {
                mw.parent().image.currentResizing.css("backgroundImage", 'url(' + mw.files.safeFilename(url) + ')');
                mw.top().wysiwyg.bgQuotesFix(mw.parent().image.currentResizing[0])
            }
        }
        if(window.thismodal) {
            thismodal.result(url);
        }
    };

    afterMediaIsInserted = function (url, todo, eventType) {

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
                    if(mw.parent().image.currentResizing){
                        mw.parent().wysiwyg.change(mw.parent().image.currentResizing[0])
                        mw.parent().image.resize.resizerSet(mw.parent().image.currentResizing[0]);
                        mw.parent().trigger('imageSrcChanged', [mw.parent().image.currentResizing[0], url])
                    }
                } else if (hash === 'set_bg_image') {
                    mw.parent().wysiwyg.set_bg_image(url);
                    mw.parent().wysiwyg.change(mw.parent().current_element);
                    mw.parent().askusertostay = true;
                } else {
                    if (typeof parent[hash] === 'function') {
                        parent[hash](url, eventType);
                    } else {
                        if(mw.parent().iframecallbacks['insert_image']) {
                            mw.parent().iframecallbacks['insert_image'](url, eventType);
                        }

                    }
                }
            } else {
                mw.parent().wysiwyg.restore_selection();
                mw.parent().wysiwyg.insert_image(url, true);


            }
        }
    };


    GlobalEmbed = false;


    $(document).ready(function () {


        mw.on.hashParam('select-file', function (pval) {

            if (hash === 'fileWindow') {
                $('body').trigger('change', [pval]);
                return false;
            }

            var type = mw.url.type(pval);
            GlobalEmbed = mw.embed.generate(type, pval);
            if (typeof mw.parent().iframecallbacks[hash] === 'function') {
                if (hash === 'editimage') {


                    mw.parent().iframecallbacks[hash](pval);
                    if(mw.parent().image.currentResizing && mw.parent().image.currentResizing){
                        mw.parent().image.resize.resizerSet(mw.parent().image.currentResizing[0]);

                    }

                } else {

                    mw.parent().iframecallbacks[hash](GlobalEmbed);
                }

            } else if (typeof parent[hash] === 'function') {

                parent[hash](GlobalEmbed)
            }
            if(mw.parent().image.currentResizing && mw.parent().image.currentResizing[0]) {
                mw.parent().trigger('imageSrcChanged', [mw.parent().image.currentResizing[0], this]);
            }

            if(window.thismodal) {
                thismodal.result(pval)
            }

            mw.parent().dialog.remove('mw_rte_image');

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

        mw.require('filepicker.js');

        var picker = new mw.filePicker({
            element:'#filepick',
            nav: 'tabs',
            footer: true,
            boxed: false,
            dropDownTargetMode: 'dialog',
            label: null
        });



        $(picker).on('Result', function (e, res) {
            var filetypes = '<?php print join(",", $types);; ?>';
            var url = res.src ? res.src : res;
            if (filetypes.indexOf('images') !== -1) {
                afterMediaIsInserted(url, '', "FileUploaded");
            }
            else if (filetypes === 'videos' || filetypes === 'media') {
                afterMediaIsInserted(url, 'video', "FileUploaded");
            }
            else if (filetypes === 'files') {
                if (item.src.contains("base64")) {
                    afterMediaIsInserted(url, '', "FileUploaded");
                } else {
                    afterMediaIsInserted(url, 'files', "FileUploaded");
                }

            }
            if(window.thismodal) {
                thismodal.result(url);
                thismodal.remove()
            }
        })


        mw.$("xc.dropable-zone").each(function () {
            var li = $(this);
            var _li = this;
            var filetypes = '<?php print join(",", $types);; ?>';

            var frame = mw.files.uploader({
                filetypes: filetypes,
                element: li
            });
            frame.width = li.width();
            frame.height = li.height();
            $(frame).on("progress", function (frame, file) {

                Progress.show();

                Progress.width(file.percent + '%');

                ProgressPercent.html(file.percent + '%');

                ProgressInfo.html(file.name);




            });
            $(frame).on("FileUploaded", function (frame, item) {
                if (filetypes.indexOf('images') !== -1) {
                    afterMediaIsInserted(item.src, '', "FileUploaded");
                }
                else if (filetypes === 'videos' || filetypes === 'media') {
                    afterMediaIsInserted(item.src, 'video', "FileUploaded");
                }
                else if (filetypes === 'files') {
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

            $(frame).on("done", function (frame, item) {
                Progress.hide();
                //ProgressBar.width('0%');
                ProgressPercent.html('');
                ProgressInfo.html(ProgressDoneHTML);
                afterMediaIsInserted(false, '', "done");

            });


            $(frame).on("error", function (frame, file) {
                ProgressPercent.html('');
                ProgressInfo.html(ProgressErrorHTML(file.name));
            });



        });


        var urlSearcher = mw.$("#media_search_field");
        var submit = mw.$('#btn_insert');
        var status = mw.$("#image_status");


        urlSearcher.bind('keyup paste', function (e) {
            GlobalEmbed = false;
            if (e.type === 'keyup') {
                mw.on.stopWriting(urlSearcher[0], function () {
                    var val = urlSearcher.val();
                    var type = mw.url.type(val);
                    if (status[0]) {
                        status[0].className = type;
                        if (type !== 'image') {
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
                        if (typeof mw.parent().iframecallbacks[hash] === 'function') {
                            if (hash.contains("edit")) {
                                mw.parent().iframecallbacks[hash](val);
                            } else {
                                mw.parent().iframecallbacks[hash](GlobalEmbed);
                            }
                        } else if (typeof parent[hash] === 'function') {
                            parent[hash](GlobalEmbed);

                        }
                        if(window.thismodal) {
                            thismodal.result(GlobalEmbed)
                        }
                        mw.parent().dialog.remove('mw_rte_image');
                    }
                }, 500);
            }

        });


        submit.click(function () {


            var val = urlSearcher.val();
            if (hash === 'fileWindow') {
                $('body').trigger('change', [val]);
                return false;
            }
            var type = mw.url.type(val);
            if (type !== 'link') {
                if (typeof mw.parent().iframecallbacks[hash] === 'function') {
                    mw.parent().iframecallbacks[hash](GlobalEmbed);
                } else if (typeof parent[hash] === 'function') {
                    parent[hash](GlobalEmbed)
                }
            } else {

                if (typeof mw.parent().iframecallbacks[hash] === 'function') {
                    mw.parent().iframecallbacks[hash](val);
                } else if (typeof parent[hash] === 'function') {
                    parent[hash](val)
                }

            }
            if(window.thismodal) {
                thismodal.result(val)
            }
            if(mw.parent().image.currentResizing) {
                mw.parent().trigger('imageSrcChanged', [mw.parent().image.currentResizing[0], val]);

            }

            mw.parent().dialog.remove('mw_rte_image');
        });

        var selector = '#image_tabs option';
        $('#image_tabs select').on('change', function () {
            var selected = this.options[this.selectedIndex];
            var mode = 'dialog';
            var index = this.selectedIndex;

            var active = $('.tab').eq(index).show();
            var rep = document.createElement('div');
            var dialog = mw.top().dialog({
                overlay: true,
                beforeRemove: function () {
                    $(rep).replaceWith(active)
                }
            })

            if(mode === 'dialog' && index > 0) {

            } else {
                $('.tab').hide()//.eq(index).show()
            }

            active.before(rep);


            active.appendTo(dialog.dialogContainer)

            if (selected.id === 'browseTab') {

                if (!window.fileBrowserLoaded) {
                    window.fileBrowserLoaded = true;
                    mw.top().load_module('files/admin', '#file_module_live_edit_adm', function () {

                    }, {'filetype':'images'});
                }

            } else {
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
            var id;
            if (url.contains('youtu.be')) {
                id = url.split('/').pop();
                if (!id) {
                    id = id.pop();
                }
                return '<div class="element mw-embed-iframe" style="height:315px;width:560px;"><iframe width="560" height="315" src="https://www.youtube.com/embed/' + id + '?v=1&wmode=transparent" frameborder="0" allowfullscreen></iframe></div>';
            } else {
                id = mw.url.getUrlParams(url).v;
                return '<div class="element mw-embed-iframe" style="height:315px;width:560px;"><iframe width="560" height="315" src="https://www.youtube.com/embed/' + id + '?v=1&wmode=transparent" frameborder="0" allowfullscreen></iframe></div>';
            }
        },
        vimeo: function (url) {
            var id = url.split('/').pop();
            if (!id) {
                id = id.pop();
            }
            return '<div class="element mw-embed-iframe" style="height:315px;width:560px;"><iframe src="https://player.vimeo.com/video/' + id + '?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=bc9b6a&wmode=transparent" width="560" height="315" frameborder="0" allowFullScreen></iframe></div>';
        }
    }

</script>


<style >

/*    body, html {
        overflow: hidden;
    }*/

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
        margin-inline-end: 15px;
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


    <div class="mw-ui-progress" id="mw-upload-progress" style="display: none">
        <div class="mw-ui-progress-bar" style="width: 0%;"></div>
        <div class="mw-ui-progress-info"></div>
        <span class="mw-ui-progress-percent"></span>
    </div>

</div>
