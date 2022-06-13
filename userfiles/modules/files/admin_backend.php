<div class="mw-module-admin-wrap<?php if (isset($params['from_admin'])): ?>-from-admin<?php endif; ?>">
    <?php if (isset($params['backend'])): ?>
        <module type="admin/modules/info"/>
    <?php endif; ?>


    <?php
    if (is_admin() == false) {
        return;
    }

    $exts = false;
    $filetype = false;

    if (isset($params['filetype']) and $params['filetype'] == 'images') {
        $exts = 'jpg,jpeg,png,gif,bmp,svg,webp';
    }
    ?>
    <script type="text/javascript">
        if (self !== parent) {
            document.body.className += ' browser-liveedit';
        }
    </script>
    <script type="text/javascript">
        mw.require("events.js");
        mw.require("forms.js");
        mw.require("url.js");
        mw.require("files.js");
        mw.lib.require("mwui");
        mw.lib.require("mwui_init");
    </script>

    <div class="card bg-none style-1 mb-0 card-settings">
        <div class="card-header px-0">
            <h5 class="w100"><i class="mdi mdi-file-cabinet text-primary mr-3"></i> <strong><?php _e("Files"); ?></strong></h5>
        </div>






    <style >
        .mw-file-browser-popup .modules-index-bar {
            padding-top: 12px;
        }

        .file-preview-holder {
            text-align: center;
        }

        .file-preview-holder img {
            max-width: 100%;
            max-height: 330px;
            margin-bottom: 20px;
        }

        .file-preview-holder video {
            margin-bottom: 20px;
        }

        .file-preview-holder .mw-ui-field {
            width: 100%;
            text-align: center;
        }

        .mw-browser-list .mdi-folder {
            font-size: 37px;
        }

        .mw-browser-list a {
            text-decoration: none !important;
        }

        .mw-browser-list .mw-fileico {
            height: 60px;
            width: 100%;
            display: flex;
            flex-flow: row;
            align-items: center;
            align-content: center;
            margin: 0 auto;
        }
        .mw-browser-list-big .as-image,
        .mw-browser-list-big .mw-fileico {
            height: 135px;
            text-align: center;
        }

        .mw-browser-list-big .mw-fileico{
            font-size: 24px;
        }
        .mw-browser-list .mw-fileico > span {
            display: block;
            margin: 0 auto;
            text-transform: uppercase;
        }



        .posts-selector span:hover {
            text-decoration: underline
        }

        /* Live Edit */

        body.browser-liveedit h2 {
            display: none;
        }

        body.browser-liveedit #files_ctrl_holder {
        }

        body.browser-liveedit #files_ctrl_holder_select_all_holder {
            display: none;

        }

        body.browser-liveedit .mw-ui-box-content {
            height: auto;
        }

        body.browser-liveedit .file-browser-multiple-download,
        body.browser-liveedit .delete_item {
            display: none;
        }

        body.browser-liveedit .mw-browser-list .mw-ui-check {
            display: none;
        }

        .mw-fileico {
            display: inline-block;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            padding: 10px 0;
            background-color: #CCCCCC;
            text-transform: lowercase;
            width: 100%;
            border-radius: 2px;
        }

        .posts-selector {
            margin: 11px 11px 0 0;
        }

        html[dir='rtl'] .posts-selector {
            margin: 11px 0 0 11px;
        }

        #mw_files_admin {
            margin-bottom: 20px;
        }

        /* /Live Edit */

        .browser-ctrl-bar {
            padding: 15px 0;
            display: flex;
            justify-content: space-between;
        }

        .image-item {
            max-height: 70px;
            max-width: 70px;
        }

        .image-item-big {
            max-height: 150px;
            max-width: 150px;
        }

        .image-item-huge {
            max-height: 250px;
            max-width: 250px;
        }

        #progressbar .mw-ui-progress-small {
            height: 8px;
            margin: 8px 0;

        }

        /* / View modes */

        body .mw-file-browser.mw-file-browser-basic #files_ctrl_holder {
            display: block;
        }

        body .mw-file-browser.mw-file-browser-basic #files_ctrl_holder_select_all_holder,
        body .mw-file-browser.mw-file-browser-basic #files_ctrl_holder_title_text {

            display: none;
        }

        body .mw-file-browser.mw-file-browser-basic .modules-index-bar,
        body .mw-file-browser.mw-file-browser-basic .browser-ctrl-bar {
            padding: 0px;
        }

        #mw-browser-list-holder{
            clear: both;
        }

        .mw-ui-btn.go-live:hover {
            background: #D3EEFE;
            border-color: #A1D1EF #589DC8 #4991C0;
        }

        .mw-file-browser .mw-ui-box-header a {
            margin: 0 3px;
        }

        .mw-file-browser .mw-ui-box-content {
            overflow-x: hidden;
            overflow: auto;
            clear: both;
        }

        .mw-file-browser .mw-ui-box-header {
            margin-bottom: 0;
        }

        .mw-file-browser .mw-ui-box-header a:hover {
            text-decoration: underline;
        }

        .mw-browser-list {
            display: flex;
            flex-wrap: wrap;
        }

        .mw-browser-list:after {
            display: block;
            clear: both;
            content: '';
        }

        .mw-browser-list li {
            list-style: none;
            position: relative;
            display: block;
            width: 10%;
            min-width: 80px;
            padding: 0 5px;
        }
        .mw-browser-list-big li {
            width: 20%;

        }

        .mw-browser-list-item{
            position: relative;
        }

        @media (max-width:1150px) {
            .mw-browser-list-big li {
                width: 25%;
            }
        }
        @media (max-width:700px) {
            .mw-browser-list-big li {
                width: 33.3333%;
            }
        }

        @media (max-width:550px) {
            .mw-browser-list-big li {
                width: 50%;
            }
        }


        .mw-browser-list li img{
            transition: .3s;
            position: relative;
        }


        .mw-browser-list a {
            display: inline-block;
            text-align: center;
            font-size: 10px;
            width: 100%;
            text-decoration: none;
            color: #434343;
            border-radius: 3px;
        }

        .mw-browser-list a:hover {
            text-decoration: underline;
        }

        .mw-browser-list a [class*='mw-icon-'] {
            float: none;
        }

        .mw-browser-list a input[type='text'] {
            font-size: 11px;
            width: 95%;
        }



        .mw-browser-list-name {
            display: block;
            clear: both;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
            white-space: nowrap;
            padding: 3px 0 0;
        }

        .mw-browser-list li .mw-icon-category {
            width: 100%;

        }

        .mw-browser-list-big .mw-file-item-check {
            margin: -10px 0 0;
        }
        .mw-file-item-check {

            padding:0px 0 10px;
            overflow: hidden;

        }

        @media (max-width: 300px) {
            .file_browser_sort_by, .browser-ctrl-bar .form-inline{
                display: none !important;
            }
        }
        @media (max-width: 800px) {
            .file-browser-multiple-delete, .file-browser-multiple-download, .mw-browser-uploader-path{
                display: none !important;
            }
        }


    </style>

    <script>
        MEDIA_UPLOADS_URL = '<?php echo media_uploads_url(); ?>';
        mw.require("<?php print $config['url_to_module']; ?>/files_admin.js");
    </script>

    <script type="text/javascript">
        gchecked = function () {
            var l = document.querySelectorAll(".mw-browser-list input:checked").length;
            if (l) {
                mw.$(".delete_item,.file-browser-multiple-download").attr("disabled", false);
            } else {
                mw.$(".delete_item,.file-browser-multiple-download").attr("disabled", true);
            }
        }

        _mw_admin_files_manage = function (param, value, callback) {
            var holder = mw.$('#mw_files_admin');
            holder.removeAttr('search');
            holder.attr('sort_by', 'filemtime DESC');

            if (param === 'all') {
                var attrs = mw.url.getHashParams(window.location.hash);
                for (var x in attrs) {
                    if (x == 'path') {
                        holder.attr(x, attrs[x]);
                    }
                    if (x == 'search') {
                        holder.attr(x, attrs[x]);
                    }
                    if (x == 'sort_by') {
                        holder.attr(x, attrs[x]);
                    }
                    if (x == 'sort_order') {
                        holder.attr(x, attrs[x]);
                    }
                    if (x == 'viewsize') {
                        holder.attr(x, attrs[x]);
                    }
                }
            } else {
                holder.attr(param, value);
            }
            $(document.body).addClass("loading")
            mw.load_module('files/browser', '#mw_files_admin', function () {
                $(document.body).removeClass("loading");
                $(".mw-ui-searchfield").removeClass("loading");
                if (typeof callback === 'function') {
                    callback.call()
                }
                gchecked()

                var dialog =  mw.top().dialog.get();
                if(dialog){
                    dialog.center();
                }

            }, {'extensions': '<?php print $exts ?>'});
        }

        $(window).bind("load", function () {

            <?php if(isset($params['start_path']) and $params['start_path'] == 'media_host_base') { ?>

            _mw_admin_files_manage('all');

            <?php } else { ?>
            _mw_admin_files_manage('all');
            <?php }  ?>
        });

        mw.on.hashParam('viewsize', function (pval) {
            _mw_admin_files_manage('viewsize', pval);
        });






        unsplash = function () {
            $('.mw_files_admin_search').toggle();
            $('#mw_files_admin').toggle();
            $('#mw_files_media_library').toggle();
            $('#mw_files_media_library').html('<?php _ejs("Loading"); ?>...');
            $('#mw_files_media_library').reload_module();
        }


        mw.on.hashParam('search', function (pval) {

            _mw_admin_files_manage('search', pval);

        });
        mw.on.hashParam('sort_by', function (pval) {
            if (pval != false && pval != '') {
                _mw_admin_files_manage('sort_by', pval);
            }
        });
        mw.on.hashParam('sort_order', function pval() {
            if (pval != false && pval != '') {
                _mw_admin_files_manage('sort_order', pval);
            }

        });


        $(document).ready(function () {
            _mw_admin_files_manage('all');

            ProgressBar = mw.progress({
                action: '<?php _ejs("Uploading"); ?>...',
                element: document.getElementById('progressbar'),
                skin: 'mw-ui-progress-small'
            });

            ProgressBar.hide()


            var path = mw.url.windowHashParam("path") != undefined ? mw.url.windowHashParam("path") : "";

            Uploader = mw.files.uploader({
                filetypes: "*",
                path: path,
                multiple: true,
                element: mw.$("#mw_uploader")
            });

            $(Uploader).bind("progress", function (frame, file) {
                ProgressBar.show()
                ProgressBar.set(file.percent);
            });

            $(Uploader).bind("done", function (frame, item) {
                ProgressBar.set(0);
                ProgressBar.hide();
            });

            $(Uploader).bind("responseError", function (e, json) {
                mw.alert(json.error.message);
                ProgressBar.set(0);
                ProgressBar.hide();
            });
            $(Uploader).bind("error", function (frame, file) {
                ProgressBar.set(0);
                ProgressBar.hide();

            });


            $(Uploader).bind("FileUploaded", function (obj, data) {
                _mw_admin_files_manage('all');
            });

            mw.$(".delete_item").click(function () {
                if (!$(this).hasClass("disabled")) {
                    var arr = [], c = document.querySelectorAll(".mw-browser-list input:checked"), i = 0, l = c.length;
                    for (; i < l; i++) {
                        arr.push(c[i].value);
                    }
                    deleteItem(arr);
                }
            })


            mw.on.hashParam('path', function (pval) {
                _mw_admin_files_manage('path', pval);



                Uploader.urlParam('path', pval);


            });

        });

        var downloadSelected = function () {
            mw.$('.mw-browser-list-item').each(function () {
                if(this.querySelector('[name="fileitem"]').checked){
                    var item = this.querySelector('.mw-browser-list-file');
                    if(item) {
                        var a = $("<a>")
                            .attr("href", item.href)
                            .attr("download", item.href)
                            .appendTo("body");
                        a[0].click();
                        a.remove();
                    }

                }

            });

        }
        var search = function (event){
            if(event && !mw.event.is.enter(event)) {
                return;
            }
            var el = document.querySelector('[name="module_keyword"]');
            mw.url.windowHashParam('search', el.value)
        }
    </script>
    <?php
    $ui_order_control = 'dropdown';


    if (!isset($ui_order_control)) {
        $ui_order_control = 'auto';
    }

    if (isset($params['ui'])) {
        $ui_order_control = $params['ui'];
    }


    ?>
    <div class="mw-file-browser mw-file-browser-<?php print $ui_order_control; ?>">
        <div class="admin-side-content">
            <div id="files_ctrl_holder">
                <div class="modules-index-bar modules-index-bar-transparent">
                    <div class="browser-ctrl-bar">
                        <div>
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-success icon-left dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="mdi mdi-plus"></i> <?php _e("Add"); ?>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <span class="dropdown-item" id="mw_uploader"><?php _e("Upload File"); ?></span>
                                    <span class="dropdown-item position-relative" onclick="createFolder()"><?php _e("Create folder"); ?></span>
                                </div>
                            </div>
                            <button type="button" disabled class="btn btn-danger icon-left delete_item file-browser-multiple-delete"><i class="mdi mdi-trash-can-outline"></i> <?php _e("Delete"); ?></button>
                            <button type="button" disabled class="btn btn-primary icon-left file-browser-multiple-download" onclick="downloadSelected()"><i class="mdi mdi-cloud-download"></i> <?php _e("Download"); ?></button>
                        </div>

                        <div class="form-inline">
                            <div class="form-group mr-1">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" style="background-color: white"><i class="mdi mdi-magnify" style="font-size: 20px"></i></span>
                                    </div>
                                    <input
                                        name="module_keyword"
                                        onkeypress="search(event)"
                                        class="form-control  mw_files_admin_search"
                                        type="text" placeholder="<?php _e("Search"); ?>"
                                    />
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-primary align-self-baseline" onclick="search()"><?php _e("Search"); ?></button>
                        </div>
                    </div>
                    <div id="progressbar" style=""></div>
                </div>
            </div>
            <div id="mw_files_admin"></div>
            <div id="mw_files_media_library" style="display: none" type="pictures/media_library"></div>
            <div id="mw_user_edit_admin"></div>
        </div>
    </div>
</div>
</div>
