<?php
    /*
        type: layout
        name: Background video
        description: Use as video background
    */
?>


<style>

    #video-background-template-<?php echo $params['id']; ?> .mwembed,
    #video-background-template-<?php echo $params['id']; ?> iframe,
    #video-background-template-<?php echo $params['id']; ?> video{
        position: absolute;
        height: 100% !important;
        width: 100% !important;
        top: 0;
        left: 0;
        object-fit: cover;
    }
    #video-background-template-<?php echo $params['id']; ?>{
        z-index: 0;
        position: absolute;
        height: 100%;
        width: 100%;
        top: 0;
        left: 0;
    }
</style>

<div id="video-background-template-<?php echo $params['id']; ?>"><?php echo $code; ?></div>

<script>
    $(document).ready(function (){
        document.querySelector('#video-background-template-<?php echo $params['id']; ?> video').controls = false;
    })
</script>

