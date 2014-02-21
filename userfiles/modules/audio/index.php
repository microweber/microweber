
<?php
    $id = "mwaudio-" . uniqid();
    $prior =  get_option('prior', $params['id']);

    if($prior == '1'){
      $audio =  get_option('data-audio-upload', $params['id']);
    }
    else{
      $audio =  get_option('data-audio-url', $params['id']);
    }
?>

<div class="mw-audio" id="<?php print $id; ?>">
    <div style="padding: 10px 0">
        <?php if( $audio != '' ){ ?>
            <embed wmode="transparent" autoplay="false" autostart="false" type="audio/mpeg"  width="100%"  data="<?php print $audio; ?>" src="<?php print $audio; ?>"></embed>
        <?php } else{ ?>
            <?php print lnotif("Upload Audio File or paste URL.");   ?>
        <?php } ?>
    </div>
</div>






