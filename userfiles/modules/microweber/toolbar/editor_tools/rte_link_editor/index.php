<script>
    parent.mw.require("external_callbacks.js");
    mw.require("forms.js");
    mw.require("files.js");
    mw.require("tools.js");

</script>

<script type="text/javascript">

    RegisterChange = function () {
        var args = Array.prototype.slice.call(arguments);

        if(parent.mw.iframecallbacks[hash]) {
            console.log(1)
            // parent.mw.iframecallbacks[hash].apply( this, arguments );
        }
        if(window.thismodal){
            console.log(2)
            thismodal.result({
                url: args[1],
                target: args[2],
                text: args[3]
            }, true);
        }

    };

    var is_searching = false;

    var hash = location.hash.replace(/#/g, "") || 'insert_link';
    mw.dd_autocomplete = function (id) {
        var el = $(id);

        el.on("input", function (event) {
            if (is_searching) {
                is_searching.abort()
            }
            var ul = el.parent().find("ul");
            ul.find("li:gt(0)").remove();
            var val = el.val().trim();
            if(!val){
                ul.hide();
                return;
            }
            is_searching = mw.tools.ajaxSearch({keyword: val, limit: 20}, function () {
                var lis = "";
                var json = this;
                for (var item in json) {
                    var obj = json[item];
                    if (typeof obj === 'object') {
                        var title = obj.title;
                        var url = obj.url;
                        lis += "<li class='mw-dd-list-result' value='" + url + "' onclick='setACValue(hash, \"" + url + "\")'><a href='javascript:;'>" + title + "</a></li>";
                    }
                }

                ul.append(lis).show();
            });

        });
    };


    setACValue = function (hash, val) {
        RegisterChange(hash, val);
    };


    $(document).ready(function () {


        Progress = mw.$('#mw-upload-progress');
        ProgressBar = Progress.find('.mw-ui-progress-bar');
        ProgressInfo = Progress.find('.mw-ui-progress-info');
        ProgressPercent = Progress.find('.mw-ui-progress-percent');
        ProgressDoneHTML = '<span class="ico iDone" style="top:-6px;"></span>&nbsp;<?php _e("Done! All files have been uploaded"); ?>.';
        ProgressErrorHTML = function (filename) {
            return '<span class="ico iRemove" style="top:-6px;"></span>&nbsp;<?php _e("Error"); ?>! "' + filename + '" - <?php _e("Invalid filetype"); ?>.';
        }

        mw.tools.dropdown();

        mw.dd_autocomplete('#dd_pages_search');


        var frame = mw.files.uploader({name: 'upload_file_link', filetypes: 'all', multiple: false});

        var frame_holder = mw.$("#upload_frame");

        frame.width = frame_holder.width();

        frame.height = frame_holder.height();

        frame.className += ' mw_upload_frame';
        $(frame).on("progress", function (frame, file) {
            Progress.show();
            ProgressBar.width(file.percent + '%');
            ProgressInfo.html(file.name);
            ProgressPercent.html(file.percent + '%');
        });
        $(frame).on("done", function (frame, item) {
            ProgressBar.width('0%');
            ProgressPercent.html('');
            ProgressInfo.html(ProgressDoneHTML);
            parent.mw.tools.modal.remove('mw_rte_link');
            Progress.hide();
        });

        $(frame).on("error", function (frame, file) {
            ProgressBar.width('0%');
            ProgressPercent.html('');
            ProgressInfo.html(ProgressErrorHTML(file.name));
            Progress.hide();
        });

        $(frame).on("FilesAdded", function (frame, files_array, runtime) {
            if (runtime == 'html4') {
                ProgressInfo.html('<?php _e("Uploading"); ?> - "' + files_array[0].name + '" ...');
            }
            Progress.show()
        });
        $(frame).on("FileUploaded", function (frame, item) {
            setACValue(hash, item.src)
        });


        frame_holder.append(frame);


        mw.$("#insert_email").click(function () {
            var val = mwd.getElementById('email_field').value;
            if (!val.contains('mailto:')) {
                val = 'mailto:' + val;
            }
            setACValue(hash, val)

            return false;
        });
        mw.$("#insert_url").click(function () {
            var val = mwd.getElementById('customweburl').value;
            var target = '_self';
            if (hash === 'insert_link') {
                if (mwd.getElementById('url_target').checked == true) {
                    target = '_blank';
                }
            }
            var link_text_val = mwd.getElementById('customweburl_text').value;
            RegisterChange(hash, val, target, link_text_val);


            return false;
        });

        $("#insert_from_dropdown").click(function () {
            var val = mw.$("#insert_link_list").getDropdownValue();
            RegisterChange(hash, val);

            return false;
        });


        LinkTabs = mw.tabs({
            nav: ".mw-ui-btn-nav-tabs a",
            tabs: ".tab"
        });


    });


</script>


<style type="text/css">


    #upload_frame {
        width: 100%;
        height: 100%;
        position: absolute;
        z-index: 1;
        top: 0;
        left: 0;
    }

    #tabs .tab {
        display: none;
    }

    #mw-popup-insertlink {
        padding: 10px;
        overflow:auto;
    }

    .mw-ui-row-nodrop, .media-search-holder {
        margin-bottom: 12px;
    }
    .media-search-holder .mw-dropdown-content { position: relative; }
    .mw-ui-box-content {
        padding-top: 20px;
    }

    #insert_link_list {
        width: 100%;
    }

    #email_field, #customweburl {
        width: 275px;
        margin-right: 15px;
        margin-bottom: 15px;
    }

    #available_elements {
        max-height: 400px;
        overflow: auto;
        border: 1px solid #eee;
    }

    #available_elements a {
        display: block;
        padding: 10px 12px;
        cursor: pointer;

    }

    #available_elements a:hover {
        background-color: #EEEEEE;
    }

    #available_elements a + a {
        border-top: 1px solid #eee;
    }

</style>


<div id="mw-popup-insertlink">
    <div class="mw-ui-field-holder" id="customweburl_text_field_holder" style="display:none">
        <label class="mw-ui-label"><?php _e("Link text"); ?></label>
        <textarea type="text" class="mw-ui-field w100" id="customweburl_text" placeholder="Link text"></textarea>
    </div>
    <div class="">
        <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
            <a class="mw-ui-btn active" href="javascript:;"><?php _e("Website URL"); ?></a>
            <a class="mw-ui-btn" href="javascript:;"><?php _e("Page from My Website"); ?></a>
            <a class="mw-ui-btn" href="javascript:;"><?php _e("File"); ?></a>
            <a class="mw-ui-btn" href="javascript:;"><?php _e("Email"); ?></a>
            <a class="mw-ui-btn available_elements_tab_show_hide_ctrl" href="javascript:;"><?php _e("Page Section"); ?></a>
            <a class="mw-ui-btn page-layout-btn" style="display: none;" href="javascript:;"><?php _e("Page Layout"); ?></a>
        </div>
        <div class="mw-ui-box mw-ui-box-content" id="tabs">
            <div class="tab" style="display: block">
                <div class="media-search-holder">
                    <div class="mw-ui-field-holder">
                        <label class="mw-ui-label"><?php _e("URL"); ?></label>
                        <input type="text" class="mw-ui-field" id="customweburl" autofocus=""/>
                        <span class="mw-ui-btn mw-ui-btn-notification" id="insert_url"><?php _e("Insert"); ?></span>
                        <div class="mw-full-width m-t-20">
                            <label class="mw-ui-check mw-clear"><input type="checkbox" id="url_target"><span></span><span><?php _e("Open link in new window"); ?></span></label>
                        </div>
                    </div>

                </div>
            </div>
            <div class="tab">
                <div class="media-search-holder">
                    <div data-value="<?php print site_url(); ?>" id="insert_link_list" class="mw-dropdown mw-dropdown-default active">
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
            <div class="tab">
                <div class="media-search-holder">
                    <div class="mw-ui-btn mw-ui-btn-big w100">
                        <div id="upload_frame"></div>
                        <span class="mw-icon-upload"></span><?php _e("Upload"); ?>
                    </div>
                </div>
                <div class="mw-ui-progress" id="mw-upload-progress" style="width: 100%;display: none">
                    <div class="mw-ui-progress-bar" style="width: 0%;"></div>
                    <div class="mw-ui-progress-info"></div>
                    <span class="mw-ui-progress-percent"></span>
                </div>
            </div>
            <div class="tab">
                <div class="media-search-holder">
                    <input type="text" class="mw-ui-field" id="email_field" placeholder="mail@example.com"/>
                    <span class="mw-ui-btn mw-ui-btn-info right insert_the_link" id="insert_email"><?php _e("Insert"); ?></span>
                </div>
            </div>
            <div class="tab available_elements_tab_show_hide_ctrl">

                <div id="available_elements"></div>
                <script>
                    $(document).ready(function () {
                        var available_elements_tab_show_hide_ctrl_counter = 0;
                        var html = [];
                        top.$("h1[id],h12[id],h3[id],h4[id],h5[id],h6[id]", top.document.body).each(function () {
                            available_elements_tab_show_hide_ctrl_counter++;
                            html.push({id: this.id, text: this.textContent});
                            mw.$('#available_elements').append('<a data-href="#' + this.id + '"><strong>' + this.nodeName + '</strong> - ' + this.textContent + '</a>')
                        });
                        mw.$('#available_elements a').on('click', function () {

                            parent.mw.iframecallbacks[hash](top.location.href.split('#')[0] + $(this).dataset('href'));
                            parent.mw.tools.modal.remove('mw_rte_link');
                        });
                        if (!available_elements_tab_show_hide_ctrl_counter) {
                            mw.$('.available_elements_tab_show_hide_ctrl').hide();
                        }
                    })
                </script>
            </div>
            <div class="tab page-layout-tab">

                <ul class="mw-ui-box mw-ui-box-content mw-ui-navigation" id="layouts-selector">

                </ul>
                <hr>
                <span class="mw-ui-btn" onclick="submitLayoutLink()"><?php _e('Insert'); ?></span>
                <script>
                    submitLayoutLink = function(){
                        var selected = $('#layouts-selector input:checked');
                        var val = top.location.href.split('#')[0] + '#mw@' + selected[0].id;
                        RegisterChange(hash, val, '_self', mw.$('#customweburl_text').val().trim() || undefined);
                    };
                    $(document).ready(function () {
                        var layoutsData = [];
                        var layouts = top.mw.$('.module[data-type="layouts"]');



                        layouts.each(function () {
                            layoutsData.push({
                                name: this.getAttribute('template').split('.')[0],
                                element: this,
                                id: this.id
                            })
                        });
                        var list = $('#layouts-selector');
                        $.each(layoutsData, function(){
                            var radio = '<input type="radio" name="layoutradio" id="' + this.id +' "><span></span>';
                            var li = $('<li><label class="mw-ui-check">' + radio + ' ' + this.name + '</label></li>');
                            var el = this.element;
                            li.on('click', function(){
                                top.mw.tools.scrollTo(el);
                            });
                            list.append(li);
                        });


                        if(layoutsData.length > 0){
                            $('.page-layout-btn').show()
                        }




                    });
                </script>

            </div>
        </div>
    </div>
</div>
