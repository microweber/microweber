
<?php
    $id = "mwaudio-" . uniqid();
    $prior =  mw('option')->get('prior', $params['id']);

    if($prior == '1'){
      $audio =  mw('option')->get('data-audio-upload', $params['id']);
    }
    else{
      $audio =  mw('option')->get('data-audio-url', $params['id']);
    }
?>

<div class="mw-audio" id="<?php print $id; ?>">
    <div style="padding: 10px 0">
        <?php if( $audio != '' ){ ?>
            <embed wmode="transparent" autoplay="false" autostart="false" type="audio/mpeg"  width="100%" height="30" data="<?php print $audio; ?>" src="<?php print $audio; ?>"></embed>
        <?php } else{ ?>
            <?php print mw_notif_le("Upload Audio File or paste URL.");   ?>
        <?php } ?>
    </div>
</div>






