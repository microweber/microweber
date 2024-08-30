<?php must_have_access(); ?>

<script>
    mw.require('filepicker.js')
</script>

<div id="module-settings">
    <div id="file-picker">

    </div>

</div>

<input type="hidden" name="url" class="mw_option_field">

<script>

    var handleResult = function (url) {
        mw.element('[name="url"]').val(url).trigger('input')
    }

    var picker = new mw.filePicker({
        type: 'video',
        element: '#file-picker',
        label: false,
        autoSelect: true,
        footer: false,
        _frameMaxHeight: true,
        fileUploaded: function (file) {
            handleResult(file.src);
            dialog.remove()
        },
        onResult: handleResult,

    });


</script>



