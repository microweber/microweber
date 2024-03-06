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
                height: 800,
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
        font-fa
    }
    #video-dialog-button-<?php echo $params['id']; ?>:after{
        content: "\F040C";
        font-family: "Material Design Icons";
        font-size: 60px;
        color: #2b2b2b;
    }
</style>



<template id="video-dialog-template-<?php echo $params['id']; ?>" style="display: none"><?php echo $code; ?></template>
<span id="video-dialog-button-<?php echo $params['id']; ?>">Play</span>

