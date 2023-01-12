<?php
$uid = uniqid();
$here = mw_includes_url() . 'toolbar/editor_tools/uppy/';


exit('deprecated');
?>

<script>

    mw.require('url.js');
    mw.require('events.js');
    mw.lib.require('uppy');
</script>
<style >
    html, body, #container, #pickfiles_<?php print $uid  ?> {
        position: absolute;
        width: 100%;
        height: 100% !important;
        top: 0;
        left: 0;
        background: transparent;
    }

    * {
        cursor: pointer;
    }

</style>


<div id="container">

</div>
<script type="text/javascript"> mw.require('files.js'); </script>
<script type="text/javascript">
    Name = this.name;
    document.body.className += ' ' + Name;
    Params = mw.url.getUrlParams(window.location.href);


    urlparams = '';
    if (!!Params.path) {
        urlparams += 'path=' + Params.path + '&';
    }
	 if (!!Params.autopath) {

        urlparams += 'autopath=' + Params.autopath + '&';
    }

    urlparams += 'token=<?php print mw_csrf_token($uid); ?>';
    urlparams += '&engine=tus';

    $(document).ready(function () {
        $(document.body).mousedown(function (e) {
            e.preventDefault();
        });
        var multi = (Params.multiple === 'true');
        var filters = [
            {title: "", extensions: Params.filters}
        ]
        this_frame = mw.parent().$("iframe[name='" + Name + "']");

        var filtersarr = Params.filters.split(',');
        for(var i = 0; i < filtersarr.length; i++){
            filtersarr[i] = '.' + filtersarr[i]
        }

        console.log(filtersarr, Params.filters)


        var uploader = Uppy.Core({ autoProceed: true })
                .use(Uppy.Dashboard, {
                    inline: true,

                    target: '#container',
                    chunkSize: 5*1024*1024,
                    allowedFileTypes: filtersarr,
                    allowMultipleUploads: multi
                })
                .use(Uppy.Tus, {endpoint: '<?php print site_url('plupload'); ?>?' + urlparams})



        window.onmessage = function (event) {
            var data = JSON.parse(event.data);
            var base = mw.url.strip(uploader.settings.url);
            var params = mw.url.getUrlParams(uploader.settings.url);
            var u = base + "?" + json2url(params) + "&" + json2url(data);
            uploader.use(Uppy.Tus, {endpoint: u})
        }

        uploader.on('file-added', function (file) {
            this_frame.trigger("FilesAdded", [file]);
            if (Params.autostart != 'false') {
                uploader.start();
                $(document.body).addClass("loading");
            }
        });
        uploader.on('upload-progress', function (file, progress) {
            this_frame.trigger("progress", file);
        });
        uploader.on('upload-success', function (file, response) {
            if (response.status === 200) {
                response.src = response.uploadURL;
                console.log(response)
                this_frame.trigger("FileUploaded", response);
            }
            else {
                this_frame.trigger("responseError", response);
                $(document.body).removeClass("loading");
            }
        });
        uploader.on('complete', function (result) {
            this_frame.trigger("done", result);
            $(document.body).removeClass("loading");
        });
        uploader.on('upload-error', function (file, error, response) {
            this_frame.trigger("error", file);
            $(document.body).removeClass("loading");
        });
        $(document.body).click(function () {
            this_frame.trigger("click");
        });
    });
</script>
