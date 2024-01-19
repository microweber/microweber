<?php
if (is_admin() == false) {
    return;
}

$fileAppendRandomId = uniqid();
$option_key = 'append_files';
$option_group = 'file_append';

if (isset($params['option_key'])) {
    $option_key = $params['option_key'];

}
if (!isset($params['option_group'])) {
    _e('First you need to set option group.');
    return;
}
$option_group = $params['option_group'];
?>


<script type="text/javascript">

    function getAppendFiles() {
        var append_files = mw.$('#append_files<?php echo $fileAppendRandomId; ?>').val();
        if (append_files == '') {
            var append_files_array = [];
        } else {
            var append_files_array = append_files.split(',');
        }

        return append_files_array;
    }

    $(document).ready(function () {

        $('body').on('click', '.mw-append-file-delete<?php echo $fileAppendRandomId; ?>', function () {

            var append_files_array = getAppendFiles();

            for (var i = 0; i < append_files_array.length; i++) {
                if (append_files_array[i] === $(this).attr('file-url')) {
                    append_files_array.splice(i, 1);
                }
            }

            mw.$('#append_files<?php echo $fileAppendRandomId; ?>').val(append_files_array.join(',')).trigger('change');

            $(this).parent().parent().parent().parent().remove();
        });

        var uploader = mw.uploader({
            filetypes: "images,videos",
            multiple: false,
            element: "#mw_uploader<?php echo $fileAppendRandomId; ?>"
        });

        $(uploader).bind("FileUploaded", function (event, data) {

            var append_file = '<div class="form-group"> <div class="input-group mb-3 append-transparent"> <input type="text" class="form-control form-control-sm" value="' + data.src + '"> <div class="input-group-append"> <span class="input-group-text py-0 px-2"><a href="javascript:;" class="text-danger mw-append-file-delete<?php echo $fileAppendRandomId; ?>" file-url="' + data.src + '">X</a></span> </div> </div> </div>';
//            var append_file = '<div class="mw-append-file"><div>'+data.src+'</div><div class="mw-append-file-delete" file-url="'+data.src+'"><i class="mw-icon-close-round"></i></div></div>';


            mw.$("#mw_uploader_loading<?php echo $fileAppendRandomId; ?>").hide();
            mw.$("#mw_uploader<?php echo $fileAppendRandomId; ?>").show();
            mw.$("#upload_info<?php echo $fileAppendRandomId; ?>").html('');
            mw.$("#upload_files<?php echo $fileAppendRandomId; ?>").append(append_file);

            var append_files_array = getAppendFiles();
            append_files_array.push(data.src);

            mw.$('#append_files<?php echo $fileAppendRandomId; ?>').val(append_files_array.join(',')).trigger('change');

        });

        $(uploader).bind('progress', function (up, file) {
            mw.$("#mw_uploader<?php echo $fileAppendRandomId; ?>").hide();
            mw.$("#mw_uploader_loading<?php echo $fileAppendRandomId; ?>").show();
            mw.$("#upload_info<?php echo $fileAppendRandomId; ?>").html(file.percent + "%");
        });

        $(uploader).bind('error', function (up, file) {
            mw.notification.error("The file is not uploaded.");
        });

    });
</script>

<?php
$appendFiles = explode(",", get_module_option($option_key, $option_group));
?>
<input
    name="<?php echo $option_key; ?>"
    value="<?php print get_option('append_files', $option_group) ?>"
    class="form-select  mw_option_field w100" id="append_files<?php echo $fileAppendRandomId; ?>"
    option-group="<?php echo $option_group; ?>"
    data-option-group="<?php echo $option_group; ?>"

    <?php if(isset($params['lang'])): ?>
    lang="<?php echo $params['lang']; ?>"
    <?php endif; ?>

    module="<?php echo $params['module']; ?>"

    type="hidden"/>


<div class="form-group mb-4">
    <label class="form-label">
        <?php
        if (isset($params['title'])) {
            echo $params['title'];
        } else {
            _e("E-mail attachments");
        }
        ?>
    </label>
    <small class="text-muted d-block mb-2"><?php _e("You can attach a file to the automatic email"); ?></small>
    <button type="button" id="mw_uploader<?php echo $fileAppendRandomId; ?>" class="btn btn-sm btn-outline-primary"><?php _e("Upload file"); ?><span id="upload_info<?php echo $fileAppendRandomId; ?>"></span></button>
</div>

<div id="upload_files<?php echo $fileAppendRandomId; ?>">
    <?php
    foreach ($appendFiles as $file) {
        if (empty($file)) {
            continue;
        }
        ?>
        <div class="form-group">
            <div class="input-group mb-3 append-transparent">
                <input type="text" class="form-control form-control-sm" value="<?php echo $file; ?>">
                <div class="input-group-append">
                    <span class="input-group-text py-0 px-2">
                        <a href="javascript:;" class="text-danger mw-append-file-delete<?php echo $fileAppendRandomId; ?> m-0 float-none" file-url="<?php echo $file; ?>">X</a>
                    </span>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>
