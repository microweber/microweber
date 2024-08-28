<?php
/*
type: layout
name: Button dialog
description: Opens video in a popup
*/
?>

<?php
    if(!isset($params['width'])) {
        $params['width'] = 1200;
    }

?>

<script>
    $(document).ready(function () {

        $('#video-dialog-button-<?php echo $params['id']; ?>').on('click', function (){
            $('#video-dialog-template-<?php echo $params['id']; ?>').mwDialog({
                header: false,
                skin: 'video',
                closeButtonAppendTo: '.mw-dialog-holder',
                width: <?php print $params['width']  ?>,
                height: 100%,
                top: 50%;
            });
            var dialog = mw.dialog.get()

            mw.spinner(({element: dialog.dialogContainer, size: 30})).show();
            $('iframe,img', dialog.dialogContainer).on('load', function (){
                mw.spinner(({element: dialog.dialogContainer, size: 30})).remove();
            })
        })

    });
</script>
<style>




    #video-dialog-button-<?php echo $params['id']; ?>{
        font-size: 0;

    }
    #video-dialog-button-<?php echo $params['id']; ?>:after{
        content: "\eb86";
        font-family: 'icomoon-solid';
        speak: none;
        font-style: normal;
        font-weight: normal;
        font-variant: normal;
        text-transform: none;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        font-size: 100px;
        color: var(--mw-primary-color);
    }
</style>



<template id="video-dialog-template-<?php echo $params['id']; ?>" style="display: none"><?php echo $code; ?></template>
<span id="video-dialog-button-<?php echo $params['id']; ?>"></span>

