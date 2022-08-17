
mw.lib.require('xss');


createPopHTML = function (sourceUrl, type) {
    type = type || 'image';
    var h;
    if (type === 'image') {
        h = ""
            + "<div class='file-preview-holder'>"
            + "<img src='" + sourceUrl + "' />"
            + "<div class='input-group mb-3'><input type='text' class='form-control' value='" + sourceUrl + "' readonly>"
            + "<span class='input-group-append'><span class='btn btn-primary btn-icon tip' data-tip='Copy'  onclick='mw.tools.copy(\"" + sourceUrl + "\")'><i class='mdi mdi-content-copy'></i></span></span>"
            + "</div></div>";
    } else if (type === 'media') {
        h = ""
            + "<div class='file-preview-holder'>"
            + '<video autoplay="true" class="w100" src="' + sourceUrl + '" controls></video>'
            + "<div class='input-group mb-3'><input type='text' class='form-control' value='" + sourceUrl + "' readonly>"
            + "<span class='input-group-append'><span class='btn btn-primary btn-icon tip' data-tip='Copy' onclick='mw.tools.copy(\"" + sourceUrl + "\")'><i class='mdi mdi-content-copy'></i></span></span></div>"
            + "</div>";
    } else if (type === 'pdf') {
        h = ""
            + "<div class='file-preview-holder'>"
            + '<iframe style="height:70vh;width:100%;border:0px;" src="' + sourceUrl + '"></iframe><br><br>'
            + "<div class='input-group mb-3'><input type='text' class='form-control' value='" + sourceUrl + "' readonly>"
            + "<span class='input-group-append'><span class='btn btn-primary btn-icon tip' data-tip='Copy' onclick='mw.tools.copy(\"" + sourceUrl + "\")'><i class='mdi mdi-content-copy'></i></span></span></div>"
            + "</div>";
    } else if (type === 'nopreview') {
        h = ""
            + "<div class='file-preview-holder'>"
            + '<h2>' + mw.tools.get_filename(sourceUrl) + '</h2>'
            + '<div class="mw-field" style="width:100%">'

            + "<input type='text' value='" + sourceUrl + "' onfocus='this.select()' readonly>"
            + "<span class='mw-ui-btn mw-field-append' onclick='mw.tools.copy(\"" + sourceUrl + "\")'>Copy</span>"
            + '<a class="mw-ui-btn mw-ui-btn-info mw-field-append" href="' + sourceUrl + '">Download</a>'
            + "</div>"
            + "</div>";
    }

    return h;
};

deleteItem = function (url, name, frommodal,removeSelectorOndelete) {

    var obj, msg;
    if (typeof url === 'string') {
        obj = {path: [url]};
        name = name || 'this';
        msg = "Are you sure you want to delete " + name + "?";
    } else if (url.constructor === [].constructor) {
        obj = {path: url}
        msg = "Are you sure you want to delete these files";
    } else {
        return false;
    }



    $('#mw_alert').remove();
    mw.tools.confirm(msg, function () {
        $(document.body).addClass("loading");
        if (frommodal === true) {
            mw.$("#prfile").remove()
        }
        $.post(mw.settings.api_url + "media/delete_media_file", obj, function (a) {
            $(document.body).removeClass("loading");

            if (typeof removeSelectorOndelete === 'string') {
                $(removeSelectorOndelete).fadeOut();
            } else {
                _mw_admin_files_manage('all');
            }

          //  _mw_admin_files_manage('all');
            mw.notification.msg(a);

        });
    })
}

if (self === parent) {
    mw.on.hashParam('select-file', function (pval) {
        var dialog;

        pval = filterXSS(pval);

        var checkUrlIsCorrect = pval.indexOf(MEDIA_UPLOADS_URL);
        if (checkUrlIsCorrect !== 0) {
            mw.notification.error('Wrong media file.');
            return false;
        }

        if (pval.valueOf()) {
            var type = pval.valueOf().split(".").pop();
            type = type.toLowerCase();

            var is = function (a) {
                a = a.trim();
                return type === a;
            };

            var isImage = is('jpg')
                || is('jpeg')
                || is('png')
                || is('bmp')
                || is('gif')
                || is('webp')
                || pval.indexOf('images.unsplash.com') !== -1;

            var isText = is('txt')
                || is('xml')
                || is('html')
                || is('htm')
                || is('xhtml')
                || is('log')

            if (isImage) {
                if (mw.$("#prfile").length === 0) {
                    dialog = mw.dialog({
                        html: createPopHTML(pval),
                        width: 500,
                        height: 'auto',
                        autoHeight: true,
                        name: "prfile",
                        title: pval.split("/").pop()
                    });
                    $(dialog).on('Remove', function () {
                        // mw.url.windowDeleteHashParam('select-file')
                    })
                } else {
                    mw.$("#prfile .mw_modal_container").html(createPopHTML(pval));
                    mw.$("#prfile .mw_modal_title").html(pval.split("/").pop())
                }
            } else if (type === 'mp3' || type === 'avi' || type === 'mp4' || type === 'wmv' || type === 'swf') {
                dialog = mw.dialog({
                    html: createPopHTML(this, 'media'),
                    width: 500,
                    height: 'auto',
                    autoHeight: true,
                    name: "prfile",
                    title: pval.split("/").pop()
                });
                $(dialog).on('Remove', function () {
                    // mw.url.windowDeleteHashParam('select-file')
                })
            } else {
                if (mw.$("#prfile").length > 0) {
                    mw.$("#prfile").remove()
                }

                if (type === 'pdf') {
                    dialog = mw.dialog({
                        html: createPopHTML(pval, 'pdf'),
                        width: '80vw',
                        height: 'auto',
                        autoHeight: true,
                        name: "prfile",
                        title: pval.split("/").pop()
                    });
                } else if (isText) {
                    dialog = mw.dialogIframe({
                        url: pval,
                        width: 500,
                        height: 'auto',
                        name: "prfile",
                        title: pval.split("/").pop()
                    });
                } else {
                    dialog = mw.dialog({
                        html: createPopHTML(pval, 'nopreview'),
                        width: 500,
                        height: 'auto',
                        autoHeight: true,
                        name: "prfile",
                        title: pval.split("/").pop()
                    });
                }
            }
        } else {
            if (mw.$("#prfile").length > 0) {
                mw.$("#prfile").remove();
            }
        }
    });
}



saveNewFolder = function (a) {


    if (a) {
        var path = mw.url.windowHashParam("path") != undefined ? mw.url.windowHashParam("path") : "";
        var obj = {
            path: path,
            name: a,
            new_folder: 1
        }
        $.post(mw.settings.api_url + "create_media_dir", obj, function (data) {
            if(data.error) {
                mw.notification.error(data.error);

            } else {
                mw.notification.success('Folder created');
            }
            _mw_admin_files_manage('all', false, function () {
                mw.$(".mw-browser-list span").each(function () {
                    if (this.innerHTML === a) {
                        mw.tools.highlight(this.parentNode, "#CDE1FB");
                        return false;
                    }
                });
            });
        });
    }
}

 function createFolder() {
    mw.prompt('Folder name', function (val, cc) {
        saveNewFolder(val);
    });
}
